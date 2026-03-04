<?php

namespace App\Livewire\Portal\Dashboard;

use App\Models\NeedsAssessment;
use App\Models\AssessmentWindow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    // Filters
    public $schoolId;
    public $filterClass = '';
    public $filterGender = '';

    public $classes = [];

    public function mount()
    {
        $this->schoolId = Auth::user()->school_id;
        if (!$this->schoolId) {
            // Handle no school case
        }
        
        // Load classes for filter
        // Assuming SubebClass model exists, otherwise DB query
        try {
            $this->classes = DB::table('subeb_classes')->orderBy('sort_order')->get(['id', 'name']);
        } catch (\Exception $e) { $this->classes = []; }
    }

    public function updatedFilterClass() { $this->dispatchCharts(); }
    public function updatedFilterGender() { $this->dispatchCharts(); }

    public function resetFilters()
    {
        $this->reset(['filterClass', 'filterGender']);
        $this->dispatchCharts();
    }

    public function render()
    {
        if (!$this->schoolId) {
            return view('livewire.portal.dashboard.index', ['noSchool' => true]);
        }

        // Snapshot Stats
        $studentCount = 0;
        $teacherCount = 0;
        
        try {
            $studentQuery = DB::table('students')->where('school_id', $this->schoolId);
            if ($this->filterClass) $studentQuery->where('class_id', $this->filterClass);
            if ($this->filterGender) $studentQuery->where('gender', $this->filterGender);
            
            $studentCount = $studentQuery->count();

            // Staff count is usually not filtered by student filters, but maybe staff type? 
            // Keeping staff count global for school unless requested otherwise.
            $teacherCount = DB::table('school_staff')->where('school_id', $this->schoolId)->count();
        } catch (\Exception $e) {}

        // Needs Stats (Current Window)
        $activeWindow = AssessmentWindow::where('status', 'active')->latest()->first();
        
        $needsStats = [
            'status' => 'No active window',
            'items_count' => 0,
            'pending_count' => 0
        ];

        if ($activeWindow) {
            $assessment = NeedsAssessment::where('school_id', $this->schoolId)
                ->where('assessment_window_id', $activeWindow->id)
                ->withCount(['items', 'items as pending_items_count' => function($q) {
                    $q->where('status', 'pending');
                }])
                ->first();
            
            if ($assessment) {
                $needsStats['status'] = $assessment->status;
                $needsStats['items_count'] = $assessment->items_count;
                $needsStats['pending_count'] = $assessment->pending_items_count;
            } else {
                $needsStats['status'] = 'Not Started';
            }
        }

        // Recent Needs Items
        $recentItems = [];
        if ($activeWindow) {
             $recentItems = DB::table('needs_items')
                ->join('needs_assessments', 'needs_items.needs_assessment_id', '=', 'needs_assessments.id')
                ->where('needs_assessments.school_id', $this->schoolId)
                ->where('needs_assessments.assessment_window_id', $activeWindow->id)
                ->orderByDesc('needs_items.created_at')
                ->limit(5)
                ->select('needs_items.title', 'needs_items.category', 'needs_items.status', 'needs_items.priority')
                ->get();
        }

        return view('livewire.portal.dashboard.index', [
            'noSchool' => false,
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'needsStats' => $needsStats,
            'recentItems' => $recentItems,
            'windowTitle' => $activeWindow->title ?? 'None'
        ]);
    }

    public function dispatchCharts()
    {
        if (!$this->schoolId) return;

        // 1. Students by Class
        $studentsByClass = DB::table('students')
            ->join('subeb_classes', 'students.class_id', '=', 'subeb_classes.id')
            ->where('students.school_id', $this->schoolId)
            ->when($this->filterGender, fn($q) => $q->where('students.gender', $this->filterGender))
            ->select('subeb_classes.name', 'subeb_classes.sort_order', DB::raw('count(*) as count'))
            ->groupBy('subeb_classes.id', 'subeb_classes.name', 'subeb_classes.sort_order')
            ->orderBy('subeb_classes.sort_order')
            ->get();

        // 2. Gender Distribution
        $genderDist = DB::table('students')
            ->where('school_id', $this->schoolId)
            ->when($this->filterClass, fn($q) => $q->where('class_id', $this->filterClass))
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        // 3. Staff Breakdown
        $staffBreakdown = DB::table('school_staff')
            ->where('school_id', $this->schoolId)
            ->select('staff_type', DB::raw('count(*) as count'))
            ->groupBy('staff_type') // Values like 'teacher', 'admin', etc.
            ->get();

        $this->dispatch('portal-dashboard-charts-updated', [
            'studentsByClass' => [
                'categories' => $studentsByClass->pluck('name'),
                'data' => $studentsByClass->pluck('count')
            ],
            'genderDist' => [
                'labels' => $genderDist->pluck('gender')->map(fn($g) => ucfirst($g)),
                'data' => $genderDist->pluck('count')
            ],
            'staffBreakdown' => [
                'labels' => $staffBreakdown->pluck('staff_type')->map(fn($t) => ucwords(str_replace('_', ' ', $t))),
                'data' => $staffBreakdown->pluck('count')
            ]
        ]);
    }

    public function exportStudentsCsv()
    {
        $schoolId = $this->schoolId;
        return response()->streamDownload(function () use ($schoolId) {
            echo "Name,Class,Gender\n";
            try {
                $query = DB::table('students')
                    ->leftJoin('subeb_classes', 'students.class_id', '=', 'subeb_classes.id') // Join for class name
                    ->where('students.school_id', $schoolId);
                
                if ($this->filterClass) $query->where('students.class_id', $this->filterClass);
                if ($this->filterGender) $query->where('students.gender', $this->filterGender);

                $query->select('students.first_name', 'students.last_name', 'students.other_name', 'subeb_classes.name as class_name', 'students.gender')
                    ->orderBy('students.id')
                    ->chunk(500, function ($rows) {
                    foreach ($rows as $row) {
                        $fullName = trim(($row->first_name ?? '') . ' ' . ($row->other_name ?? '') . ' ' . ($row->last_name ?? ''));
                        echo sprintf("\"%s\",\"%s\",\"%s\"\n", 
                            $fullName ?: 'N/A', 
                            $row->class_name ?? 'N/A',
                            $row->gender ?? 'N/A'
                        );
                    }
                });
            } catch (\Exception $e) { 
                // Log error if needed 
            }
        }, 'students.csv');
    }
}
