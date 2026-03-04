<?php

namespace App\Livewire\Admin\Assessment\Summary;

use App\Models\AssessmentWindow;
use App\Models\NeedsAssessment;
use App\Models\NeedsItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    public $filterWindow = '';
    
    public function mount()
    {
        $latestWindow = AssessmentWindow::latest()->first();
        if ($latestWindow) {
            $this->filterWindow = $latestWindow->id;
        }
    }

    public function render()
    {
        $windows = AssessmentWindow::latest()->get();
        
        $stats = [
            'schools_submitted' => 0,
            'total_items' => 0,
            'approved_items' => 0,
            'total_cost' => 0,
            'high_priority' => 0,
        ];

        $categoryStats = [];
        $lgaStats = [];

        if ($this->filterWindow) {
            // Base Queries
            $assessmentsQuery = NeedsAssessment::where('assessment_window_id', $this->filterWindow)
                ->where('status', '!=', 'draft');
                
            $itemsQuery = NeedsItem::whereHas('assessment', function($q) {
                $q->where('assessment_window_id', $this->filterWindow)
                  ->where('status', '!=', 'draft');
            });

            // KPI Stats
            $stats['schools_submitted'] = (clone $assessmentsQuery)->count();
            $stats['total_items'] = (clone $itemsQuery)->count();
            $stats['approved_items'] = (clone $itemsQuery)->where('status', 'approved')->count();
            // Sum cost of approved items? Or all? Let's show approved cost for budget planning
            $stats['total_cost'] = (clone $itemsQuery)->where('status', 'approved')->sum('estimated_cost');
            $stats['high_priority'] = (clone $itemsQuery)->whereIn('priority', ['high', 'critical'])->count();

            // Category Breakdown
            $categoryStats = (clone $itemsQuery)
                ->select('category', DB::raw('count(*) as count'), DB::raw('sum(estimated_cost) as cost'), DB::raw('sum(case when status="approved" then 1 else 0 end) as approved_count'))
                ->groupBy('category')
                ->get();

            // LGA Breakdown (requires joining schools)
            $lgaStats = NeedsAssessment::where('assessment_window_id', $this->filterWindow)
                ->where('needs_assessments.status', '!=', 'draft')
                ->join('schools', 'needs_assessments.school_id', '=', 'schools.id')
                ->join('needs_items', 'needs_assessments.id', '=', 'needs_items.needs_assessment_id')
                ->select('schools.lga', 
                        DB::raw('count(distinct needs_assessments.id) as school_count'),
                        DB::raw('count(needs_items.id) as item_count'),
                        DB::raw('sum(needs_items.estimated_cost) as total_cost'))
                ->groupBy('schools.lga')
                ->get();
        }

        return view('livewire.admin.assessment.summary.index', [
            'windows' => $windows,
            'stats' => $stats,
            'categoryStats' => $categoryStats,
            'lgaStats' => $lgaStats
        ]);
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Category', 'Item Title', 'Quantity', 'Cost', 'School', 'LGA', 'Status', 'Priority']);

            if ($this->filterWindow) {
                NeedsItem::with(['assessment.school'])
                    ->whereHas('assessment', fn($q) => $q->where('assessment_window_id', $this->filterWindow))
                    ->chunk(100, function ($items) use ($handle) {
                        foreach ($items as $item) {
                            fputcsv($handle, [
                                $item->category,
                                $item->title,
                                $item->quantity . ' ' . $item->unit,
                                $item->estimated_cost,
                                $item->assessment->school->name ?? 'N/A',
                                $item->assessment->school->lga ?? 'N/A',
                                $item->status,
                                $item->priority
                            ]);
                        }
                    });
            }

            fclose($handle);
        }, 'needs_assessment_export_' . date('Y-m-d') . '.csv');
    }
}
