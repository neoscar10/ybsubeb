<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SchoolIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $filterLga = '';
    public $filterType = '';
    public $filterStatus = '';

    // Modal State
    public $showModal = false;
    public $editMode = false;
    public $schoolId;

    // Form Fields
    public $code, $name, $school_type, $ownership = 'public', $status = 'active';
    public $phone, $email, $year_established;
    public $lga, $ward, $community, $address;
    public $latitude, $longitude;
    public $notes;
    
    // Stats
    public $students_male = 0, $students_female = 0;
    public $teachers_male = 0, $teachers_female = 0;
    public $total_classrooms = 0;

    // Infrastructure
    public $has_water = false, $has_toilets = false, $has_electricity = false;
    // View Modal State
    public $showViewModal = false;
    public $viewSchool; // The school model instance for viewing

    // ... (existing properties)



    public function render()
    {
        $query = School::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('lga', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterLga, fn($q) => $q->where('lga', $this->filterLga))
            ->when($this->filterType, function($q) {
                // Flexible filter for "primary" vs "Primary" and "junior_secondary" vs "Junior Secondary"
                $type = $this->filterType;
                $humanType = ucwords(str_replace('_', ' ', $type));
                
                $q->where(function($sub) use ($type, $humanType) {
                    $sub->where('school_type', $type)
                        ->orWhere('school_type', $humanType);
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus));

        // KPIs
        $statsQuery = clone $query;
        $kpis = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('status', 'active')->count(),
            'inactive' => (clone $statsQuery)->where('status', '!=', 'active')->count(),
            'primary' => (clone $statsQuery)->where(fn($q) => $q->where('school_type', 'primary')->orWhere('school_type', 'Primary'))->count(),
            'jss' => (clone $statsQuery)->where(fn($q) => $q->where('school_type', 'junior_secondary')->orWhere('school_type', 'Junior Secondary'))->count(),
            'has_principal' => (clone $statsQuery)->has('principal')->count(),
        ];

        $schools = $query->latest()->paginate(10);

        return view('livewire.admin.schools.school-index', [
            'schools' => $schools,
            'kpis' => $kpis,
            'lgas' => $this->getLgas(), // You'd typically pull this from a config or DB
            'types' => ['primary', 'junior_secondary', 'basic', 'special'],
        ]);
    }

    public function create()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = true;
        $this->schoolId = $id;

        $school = School::findOrFail($id);
        $this->fill($school->only([
            'code', 'name', 'school_type', 'ownership', 'status',
            'phone', 'email', 'year_established', 'latitude', 'longitude',
            'lga', 'ward', 'community', 'address',
            'students_male', 'students_female', 'teachers_male', 'teachers_female',
            'total_classrooms', 'has_water', 'has_toilets', 'has_electricity', 'notes'
        ]));
        
        $this->showModal = true;
    }

    public function openViewModal($id)
    {
        $this->viewSchool = School::with('principal')->findOrFail($id);
        $this->showViewModal = true;
    }

    public function store()
    {
        $this->validateRules();

        $data = $this->prepareData();
        
        School::create($data);

        $this->showModal = false;
        session()->flash('message', 'School created successfully.');
    }

    public function update()
    {
        $this->validateRules();

        $school = School::findOrFail($this->schoolId);
        $data = $this->prepareData();
        
        // Audit update if stats changed? For now just standard update
        $school->update($data);
        
        // Update user who modified it
        $school->last_updated_by = Auth::id();
        $school->last_updated_at = now();
        $school->save();

        $this->showModal = false;
        session()->flash('message', 'School updated successfully.');
    }

    private function prepareData()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'school_type' => $this->school_type,
            'ownership' => $this->ownership,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'year_established' => $this->year_established,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'community' => $this->community,
            'address' => $this->address,
            'students_male' => $this->students_male,
            'students_female' => $this->students_female,
            'total_students' => $this->students_male + $this->students_female,
            'teachers_male' => $this->teachers_male,
            'teachers_female' => $this->teachers_female,
            'total_teachers' => $this->teachers_male + $this->teachers_female,
            'total_classrooms' => $this->total_classrooms,
            'has_water' => $this->has_water,
            'has_toilets' => $this->has_toilets,
            'has_electricity' => $this->has_electricity,
            'notes' => $this->notes,
        ];
    }

    private function validateRules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'lga' => 'required|string',
            'school_type' => 'required|string',
            'students_male' => 'required|integer|min:0',
            'students_female' => 'required|integer|min:0',
            'code' => 'nullable|string|unique:schools,code,' . ($this->schoolId ?? 'NULL'),
        ];

        $this->validate($rules);
    }

    private function resetForm()
    {
        $this->reset([
            'code', 'name', 'school_type', 'ownership', 'status',
            'phone', 'email', 'year_established', 'lga', 'ward', 'community', 'address',
            'students_male', 'students_female', 'teachers_male', 'teachers_female',
            'total_classrooms', 'has_water', 'has_toilets', 'has_electricity', 'notes', 'schoolId'
        ]);
        $this->students_male = 0;
        $this->students_female = 0;
        $this->teachers_male = 0;
        $this->teachers_female = 0;
    }

    private function getLgas()
    {
        // Simple list for now -> In real app, maybe from a config or DB
        return [
            'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 
            'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 
            'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
        ];
    }
}
