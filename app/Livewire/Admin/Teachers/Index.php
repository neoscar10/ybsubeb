<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\SchoolStaff;
use App\Models\School;
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
    public $staff_type = '';
    public $status = '';
    public $can_upload_students = ''; // 'yes' or 'no'

    // View Modal State
    public $showViewModal = false;
    public $viewStaff;

    protected $queryString = [
        'search' => ['except' => ''],
        'lga' => ['except' => ''],
        'school_id' => ['except' => ''],
        'staff_type' => ['except' => ''],
        'status' => ['except' => ''],
        'can_upload_students' => ['except' => ''],
    ];

    public function render()
    {
        $query = SchoolStaff::query()
            ->with(['school', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function($sub) {
                    $sub->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->lga, function($q) {
                $q->whereHas('school', fn($s) => $s->where('lga', $this->lga));
            })
            ->when($this->school_id, fn($q) => $q->where('school_id', $this->school_id))
            ->when($this->staff_type, fn($q) => $q->where('staff_type', $this->staff_type))
            ->when($this->status, function($q) {
                if ($this->status === 'active') $q->where('is_active', true);
                if ($this->status === 'inactive') $q->where('is_active', false);
            })
            ->when($this->can_upload_students, function($q) {
                if ($this->can_upload_students === 'yes') $q->where('can_upload_students', true);
                if ($this->can_upload_students === 'no') $q->where('can_upload_students', false);
            });

        // Clone query for stats to avoid pagination/ordering affecting counts
        $statsQuery = clone $query;

        $kpis = [
            'total' => (clone $statsQuery)->count(),
            'teachers' => (clone $statsQuery)->where('staff_type', 'teacher')->count(),
            'non_teaching' => (clone $statsQuery)->where('staff_type', '!=', 'teacher')->count(),
            'portal_users' => (clone $statsQuery)->whereNotNull('user_id')->count(),
            'upload_access' => (clone $statsQuery)->where('can_upload_students', true)->count(),
        ];

        $staffList = $query->latest()->paginate(15);
        
        $schools = School::orderBy('name')->get(['id', 'name']);
        $lgas = School::distinct()->orderBy('lga')->pluck('lga');

        return view('livewire.admin.teachers.index', [
            'staffList' => $staffList,
            'kpis' => $kpis,
            'schools' => $schools,
            'lgas' => $lgas,
        ]);
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedLga() { $this->resetPage(); }
    public function updatedSchoolId() { $this->resetPage(); }
    public function updatedStaffType() { $this->resetPage(); }
    public function updatedStatus() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'lga', 'school_id', 'staff_type', 'status', 'can_upload_students']);
        $this->resetPage();
    }

    public function openViewModal($id)
    {
        $this->viewStaff = SchoolStaff::with(['school', 'user'])->findOrFail($id);
        $this->showViewModal = true;
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'School', 'LGA', 'Type', 'Phone', 'Email', 'Designation', 'Status', 'Upload Permission', 'Connected User']);
            
            SchoolStaff::with(['school', 'user'])
                ->when($this->search, function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->when($this->lga, function($q) {
                    $q->whereHas('school', fn($s) => $s->where('lga', $this->lga));
                })
                ->when($this->school_id, fn($q) => $q->where('school_id', $this->school_id))
                ->when($this->staff_type, fn($q) => $q->where('staff_type', $this->staff_type))
                ->chunk(200, function($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->full_name,
                            $row->school->name ?? '',
                            $row->school->lga ?? '',
                            ucwords(str_replace('_', ' ', $row->staff_type)),
                            $row->phone,
                            $row->email,
                            $row->designation,
                            $row->is_active ? 'Active' : 'Inactive',
                            $row->can_upload_students ? 'Yes' : 'No',
                            $row->user ? 'Yes' : 'No',
                        ]);
                    }
                });
            fclose($handle);
        }, 'staff_export_' . date('Y-m-d') . '.csv');
    }
}
