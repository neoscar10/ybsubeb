<?php

namespace App\Livewire\Admin\Enrollment;

use App\Models\School;
use App\Models\Student;
use App\Models\SubebClass;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $lga = '';
    public $school_id = '';
    public $class_id = '';
    public $gender = '';
    public $status = 'active'; // Default active

    // Breakdown Modal
    public $showBreakdownModal = false;
    public $breakdownSchool;
    public $breakdownStats = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'lga' => ['except' => ''],
        'school_id' => ['except' => ''],
        'class_id' => ['except' => ''],
        'gender' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatedSearch() { $this->resetPage(); }
    public function updatedLga() { $this->resetPage(); }
    public function updatedSchoolId() { $this->resetPage(); }
    public function updatedClassId() { $this->resetPage(); }
    public function updatedGender() { $this->resetPage(); }
    public function updatedStatus() { $this->resetPage(); }

    public function render()
    {
        // 1. KPIs (Global Aggregates)
        // We need a query that represents specific STUDENTS filtered by all criteria
        // But for School Count, we need unique schools derived from that, OR just school filters?
        // Requirement: "Total Pupils (students count) ... based on current filters"
        
        $studentQuery = Student::query();

        // Apply filters to student query
        $this->applyStudentFilters($studentQuery);

        // Apply School-level filters to student query
        if ($this->lga || $this->school_id || $this->search) {
             $studentQuery->whereHas('school', function($s) {
                 if ($this->lga) $s->where('lga', $this->lga);
                 if ($this->school_id) $s->where('id', $this->school_id);
                 if ($this->search) {
                     $s->where(function($sq) {
                         $sq->where('name', 'like', '%'.$this->search.'%')
                           ->orWhere('code', 'like', '%'.$this->search.'%');
                     });
                 }
             });
        }

        // To avoid running multiple optimized queries, we can do conditional sums in one go if DB supports it,
        // or just simple Counts. Status/Class/Gender are directly on student.
        // For KPIs:
        $totalPupils = (clone $studentQuery)->count();
        // If gender is filtered, male/female might be 0 or equal to total.
        // Calculated explicitly:
        $malePupils = (clone $studentQuery)->where('gender', 'male')->count();
        $femalePupils = (clone $studentQuery)->where('gender', 'female')->count();

        // School Count: Distinct schools in the filtered student set? 
        // Or Schools matching the school filters?
        // Usually, "Number of Schools with Enrollment" implies schools that have students matching criteria.
        $schoolsWithEnrollment = (clone $studentQuery)->distinct('school_id')->count('school_id');

        $avgPupils = $schoolsWithEnrollment > 0 ? round($totalPupils / $schoolsWithEnrollment, 1) : 0;

        // 2. Main Table (Schools)
        // We list SCHOOLS, with counts of students matching the filters inside them.
        $schoolsQuery = School::query();

        if ($this->lga) $schoolsQuery->where('lga', $this->lga);
        if ($this->school_id) $schoolsQuery->where('id', $this->school_id);
        if ($this->search) {
            $schoolsQuery->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        // Aggregates per school
        // We must apply the STUDENT-level filters (class, gender, status) to these counts
        $schoolsQuery->withCount([
            'students as filtered_total' => fn($q) => $this->applyStudentFilters($q),
            'students as filtered_male' => fn($q) => $this->applyStudentFilters($q)->where('gender', 'male'),
            'students as filtered_female' => fn($q) => $this->applyStudentFilters($q)->where('gender', 'female'),
        ]);

        // Sort by total (descending) so most populated schools are top
        $schools = $schoolsQuery->orderByDesc('filtered_total')->paginate(15);

        // Filter Data
        $lgas = School::distinct()->orderBy('lga')->pluck('lga');
        $allSchools = $this->lga 
            ? School::where('lga', $this->lga)->orderBy('name')->get(['id', 'name'])
            : []; // Load empty or all? For performance, empty until LGA selected, or limit? Let's just load simple list if <2000, usually fine.
                  // User said "filtered by LGA if selected". If not selected, maybe all? Or suggest selecting LGA.
                  // Let's load All if no LGA, but it might be large.
        if (!$this->lga) {
             // Limit to header search or lazy load? For simplicity in standard Livewire:
             $allSchools = School::orderBy('name')->get(['id', 'name']);
        }

        $classes = SubebClass::orderBy('sort_order')->get(['id', 'name']);

        return view('livewire.admin.enrollment.index', [
            'schools' => $schools,
            'kpis' => [
                'total' => $totalPupils,
                'male' => $malePupils,
                'female' => $femalePupils,
                'school_count' => $schoolsWithEnrollment,
                'avg' => $avgPupils
            ],
            'lgas' => $lgas,
            'filterSchools' => $allSchools,
            'classes' => $classes,
        ]);
    }

    private function applyStudentFilters($query)
    {
        if ($this->class_id) $query->where('class_id', $this->class_id);
        if ($this->gender) $query->where('gender', $this->gender);
        if ($this->status) {
            if ($this->status === 'all') {
                // no op
            } else {
                $query->where('status', $this->status);
            }
        }
        return $query;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'lga', 'school_id', 'class_id', 'gender', 'status']);
        $this->status = 'active';
        $this->resetPage();
    }

    public function openBreakdownModal($schoolId)
    {
        $this->breakdownSchool = School::find($schoolId);
        if (!$this->breakdownSchool) return;

        // Breakdown by Class
        $query = Student::where('school_id', $schoolId);
        $this->applyStudentFilters($query); // Apply global filters (gender, status)
        
        // Group by class 
        // We need class name. simpler to select class_id and count, then map to class names
        $stats = $query->selectRaw('class_id, count(*) as total, sum(case when gender="male" then 1 else 0 end) as male, sum(case when gender="female" then 1 else 0 end) as female')
            ->groupBy('class_id')
            ->get();
        
        // Map to class names
        $classNames = SubebClass::pluck('name', 'id');
        
        $this->breakdownStats = $stats->map(function($stat) use ($classNames) {
            return [
                'class_name' => $classNames[$stat->class_id] ?? 'Unknown',
                'order' => $stat->class_id, // Approximate sort by ID if classes seeded in order
                'total' => $stat->total,
                'male' => $stat->male,
                'female' => $stat->female,
            ];
        })->sortBy('order')->values();

        $this->showBreakdownModal = true;
    }

    public function exportBreakdown()
    {
        if (!$this->breakdownSchool || empty($this->breakdownStats)) return;

        $school = $this->breakdownSchool;
        
        return response()->streamDownload(function () use ($school) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['School: ' . $school->name]);
            fputcsv($handle, ['LGA: ' . $school->lga]);
            fputcsv($handle, []);
            fputcsv($handle, ['Class', 'Total', 'Male', 'Female']);

            foreach ($this->breakdownStats as $row) {
                fputcsv($handle, [
                    $row['class_name'],
                    $row['total'],
                    $row['male'],
                    $row['female'],
                ]);
            }
            
            // Totals
            fputcsv($handle, [
                'TOTAL',
                collect($this->breakdownStats)->sum('total'),
                collect($this->breakdownStats)->sum('male'),
                collect($this->breakdownStats)->sum('female'),
            ]);

            fclose($handle);
        }, 'breakdown_' . $school->code . '_' . date('Y-m-d') . '.csv');
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['School Code', 'School Name', 'LGA', 'Total Pupils', 'Male', 'Female']);

            $schoolsQuery = School::query();
             if ($this->lga) $schoolsQuery->where('lga', $this->lga);
             if ($this->school_id) $schoolsQuery->where('id', $this->school_id);
             if ($this->search) {
                $schoolsQuery->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('code', 'like', '%'.$this->search.'%');
                });
             }

             // We need to fetch with counts, filtering totals
             $schoolsQuery->withCount([
                'students as filtered_total' => fn($q) => $this->applyStudentFilters($q),
                'students as filtered_male' => fn($q) => $this->applyStudentFilters($q)->where('gender', 'male'),
                'students as filtered_female' => fn($q) => $this->applyStudentFilters($q)->where('gender', 'female'),
             ]);

             $schoolsQuery->chunk(200, function($schools) use ($handle) {
                 foreach ($schools as $school) {
                     // Only export schools with data? No, export all matching school filters, show 0s if empty
                     fputcsv($handle, [
                         $school->code,
                         $school->name,
                         $school->lga,
                         $school->filtered_total,
                         $school->filtered_male,
                         $school->filtered_female,
                     ]);
                 }
             });

            fclose($handle);
        }, 'enrollment_' . ($this->lga ?: 'all') . '_' . date('Y-m-d') . '.csv');
    }
}
