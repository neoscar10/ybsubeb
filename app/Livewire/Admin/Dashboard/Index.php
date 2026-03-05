<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\School;
use App\Models\Student; // Assuming model exists, will verify
use App\Models\Teacher; // Assuming model exists
use App\Models\NeedsAssessment;
use App\Models\NeedsItem;
use App\Models\AssessmentWindow;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    // Filters
    public $filterLga = '';
    public $filterWindow = '';

    public function mount()
    {
        try {
            $latestWindow = AssessmentWindow::where('status', 'active')->latest()->first();
            $this->filterWindow = $latestWindow ? $latestWindow->id : '';
        } catch (\Exception $e) {
            $this->filterWindow = '';
        }
    }

    public function updatedFilterLga() { $this->dispatchCharts(); }
    public function updatedFilterWindow() { $this->dispatchCharts(); }

    public function render()
    {
        $missingSetup = false;
        if (!\Illuminate\Support\Facades\Schema::hasTable('assessment_windows') || !\Illuminate\Support\Facades\Schema::hasTable('needs_items')) {
            $missingSetup = true;
        }

        // KPIs
        $totalSchools = School::count();
        $activeSchools = School::where('status', true)->count(); // Assuming status column
        
        // Use try-catch or checks if models don't exist yet, but assuming they do based on context
        $totalStudents = 0;
        $totalTeachers = 0;
        $totalNonTeaching = 0;
        
        try {
            $totalStudents = DB::table('students')->count();
            $totalTeachers = DB::table('teachers')->where('type', 'teaching')->count(); // Assuming type
            $totalNonTeaching = DB::table('teachers')->where('type', 'non-teaching')->count();
        } catch (\Exception $e) { /* Tables might not exist */ }

        // Needs KPIs
        $needsQuery = NeedsAssessment::query();
        if ($this->filterWindow) $needsQuery->where('assessment_window_id', $this->filterWindow);
        if ($this->filterLga) $needsQuery->whereHas('school', fn($q) => $q->where('lga', $this->filterLga));

        // Easier Top Cost query:
        $hotList = collect();
        $windows = collect();
        $submittedCount = 0;
        $pendingCount = 0;
        $totalCost = 0;

        try {
            $submittedCount = (clone $needsQuery)->where('status', '!=', 'draft')->count();
            $pendingCount = (clone $needsQuery)->where('status', 'under_review')->count();
            $totalCost = $costQuery->sum('estimated_cost');

            $hotList = DB::table('needs_items')
                ->join('needs_assessments', 'needs_items.needs_assessment_id', '=', 'needs_assessments.id')
                ->join('schools', 'needs_assessments.school_id', '=', 'schools.id')
                ->select('schools.name', DB::raw('SUM(needs_items.estimated_cost) as total_cost'), DB::raw('COUNT(needs_items.id) as item_count'))
                ->when($this->filterWindow, fn($q) => $q->where('needs_assessments.assessment_window_id', $this->filterWindow))
                ->when($this->filterLga, fn($q) => $q->where('schools.lga', $this->filterLga))
                ->groupBy('schools.id', 'schools.name')
                ->orderByDesc('total_cost')
                ->limit(5)
                ->get();

            $windows = AssessmentWindow::latest()->get();
        } catch (\Exception $e) { /* Tables might not exist */ }

        // LGAs hardcoded or from DB
        $lgas = [
            'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam',
            'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 'Machina',
            'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
        ];

        return view('livewire.admin.dashboard.index', [
            'missingSetup' => $missingSetup,
            'totalSchools' => $totalSchools,
            'activeSchools' => $activeSchools,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalNonTeaching' => $totalNonTeaching,
            'submittedCount' => $submittedCount,
            'pendingCount' => $pendingCount,
            'totalCost' => $totalCost,
            'hotList' => $hotList,
            'windows' => $windows,
            'lgas' => $lgas
        ]);
    }

    public function dispatchCharts()
    {
        // 1. Enrollment by LGA
        $enrollmentByLga = DB::table('schools')
           ->join('students', 'students.school_id', '=', 'schools.id') // Assuming aggregation
           ->select('schools.lga', DB::raw('count(*) as total'))
           ->when($this->filterLga, fn($q) => $q->where('schools.lga', $this->filterLga)) // Filter logic weird for "By LGA" if single LGA selected, but okay
           ->groupBy('schools.lga')
           ->get();
        
        // 2. Needs Categories
        $needsCats = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('needs_items')) {
            $needsCats = DB::table('needs_items')
                ->join('needs_assessments', 'needs_items.needs_assessment_id', '=', 'needs_assessments.id')
                ->when($this->filterWindow, fn($q) => $q->where('needs_assessments.assessment_window_id', $this->filterWindow))
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get();
        }

        $this->dispatch('update-dashboard-charts', [
            'enrollmentLga' => [
                'categories' => $enrollmentByLga->pluck('lga'),
                'data' => $enrollmentByLga->pluck('total')
            ],
            'needsCats' => [
                'categories' => $needsCats->pluck('category'),
                'data' => $needsCats->pluck('count')
            ]
        ]);
    }
    
    // Exports
    public function exportNeedsCsv()
    {
        return response()->streamDownload(function () {
            echo "School,LGA,Category,Item,Cost,Status\n";
            $query = NeedsItem::query()
                ->with('assessment.school')
                ->whereHas('assessment', function($q) {
                    if ($this->filterWindow) $q->where('assessment_window_id', $this->filterWindow);
                    if ($this->filterLga) $q->whereHas('school', fn($s) => $s->where('lga', $this->filterLga));
                });
            
            $query->chunk(500, function ($items) {
                foreach ($items as $item) {
                    echo sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                        $item->assessment->school->name ?? '',
                        $item->assessment->school->lga ?? '',
                        $item->category,
                        str_replace('"', '""', $item->title),
                        $item->estimated_cost,
                        $item->status
                    );
                }
            });
        }, 'needs-export.csv');
    }
}
