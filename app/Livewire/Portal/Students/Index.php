<?php

namespace App\Livewire\Portal\Students;

use App\Models\Student;
use App\Models\SubebClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $classFilter = '';
    public $statusFilter = 'active';

    // Modal
    public $showModal = false;
    public $editMode = false;
    public $studentId;

    // Form
    public $admission_no, $first_name, $last_name, $other_name, $gender;
    public $date_of_birth, $class_id, $enrollment_date, $status = 'active';
    public $guardian_name, $guardian_phone, $address;

    public function getFilteredStudentsQueryProperty()
    {
        $user = Auth::user();

        return Student::where('school_id', $user->school_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('admission_no', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter));
    }

    public function render()
    {
        $students = $this->filteredStudentsQuery
            ->with('subebClass')
            ->orderBy('class_id') // Group by class
            ->latest()
            ->paginate(15);

        $classes = SubebClass::orderBy('sort_order')->get();

        return view('livewire.portal.students.index', [
            'students' => $students,
            'classes' => $classes,
        ]);
    }

    public function exportCsv()
    {
        $user = Auth::user();
        $query = $this->filteredStudentsQuery
                    ->with('subebClass')
                    ->orderBy('class_id')
                    ->latest();
        
        $fileName = 'students_' . ($user->school->code ?? 'school') . '_' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($handle, [
                'Admission No', 
                'First Name', 
                'Last Name', 
                'Other Name', 
                'Gender', 
                'DOB', 
                'Class', 
                'Guardian Name', 
                'Guardian Phone', 
                'Status', 
                'Enrollment Date',
                'Created At'
            ]);

            $query->chunk(100, function ($students) use ($handle) {
                foreach ($students as $student) {
                    fputcsv($handle, [
                        $student->admission_no,
                        $student->first_name,
                        $student->last_name,
                        $student->other_name,
                        ucfirst($student->gender),
                        $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
                        $student->subebClass->name ?? 'N/A',
                        $student->guardian_name,
                        $student->guardian_phone,
                        ucfirst($student->status),
                        $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '',
                        $student->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $fileName);
    }
    
    // Reset pagination when filters update
    public function updatedSearch() { $this->resetPage(); }
    public function updatedClassFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function create()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = false;
        $this->dispatch('show-modal');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = true;
        $this->studentId = $id;

        $student = Student::where('school_id', Auth::user()->school_id)->findOrFail($id);

        $this->fill($student->only([
            'admission_no', 'first_name', 'last_name', 'other_name', 'gender',
            'date_of_birth', 'class_id', 'enrollment_date', 'status',
            'guardian_name', 'guardian_phone', 'address'
        ]));
        
        // Date handling for input
        $this->date_of_birth = $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : null;
        $this->enrollment_date = $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : null;

        $this->dispatch('show-modal');
    }

    public function store()
    {
        $this->validateRules();
        
        $data = $this->prepareData();
        $data['school_id'] = Auth::user()->school_id;
        $data['created_by'] = Auth::id();

        Student::create($data);

        $this->dispatch('hide-modal');
        session()->flash('message', 'Student registered successfully.');
    }

    public function update()
    {
        $this->validateRules();

        $student = Student::where('school_id', Auth::user()->school_id)->findOrFail($this->studentId);
        $data = $this->prepareData();
        $data['updated_by'] = Auth::id();

        $student->update($data);

        $this->dispatch('hide-modal');
        session()->flash('message', 'Student record updated.');
    }

    private function prepareData()
    {
        return [
            'admission_no' => $this->admission_no,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_name' => $this->other_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'class_id' => $this->class_id,
            'enrollment_date' => $this->enrollment_date,
            'status' => $this->status,
            'guardian_name' => $this->guardian_name,
            'guardian_phone' => $this->guardian_phone,
            'address' => $this->address,
        ];
    }

    private function validateRules()
    {
        $schoolId = Auth::user()->school_id;
        
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'class_id' => 'required|exists:subeb_classes,id',
            'status' => 'required|in:active,inactive,transferred,graduated',
            'admission_no' => 'nullable|string|max:50',
            // admission_no unique per school
        ];
        
        if ($this->admission_no) {
            // Unique check logic (commented out for now as before)
        }

        $this->validate($rules);
    }

    public function resetForm()
    {
        $this->reset([
            'admission_no', 'first_name', 'last_name', 'other_name', 'gender',
            'date_of_birth', 'class_id', 'enrollment_date', 'status',
            'guardian_name', 'guardian_phone', 'address', 'studentId'
        ]);
    }
}

