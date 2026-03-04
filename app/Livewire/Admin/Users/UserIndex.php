<?php

namespace App\Livewire\Admin\Users;

use App\Mail\NewUserCredentialsMail;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class UserIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // Main Modal State
    public $showModal = false;
    public $editMode = false;
    
    // School Selection Modal State
    public $showSchoolModal = false;
    public $schoolSearch = '';

    public $userId;
    public $name;
    public $email;
    public $phone; // New field
    public $role;
    public $school_id; // New field for principal assignment
    public $selectedSchoolName; // For UI preview
    public $isActive = true;
    public $password;
    public $password_confirmation;

    public $userToToggleId;
    public $userToToggleAction;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->roleFilter = '';
        $this->statusFilter = '';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedSchoolSearch()
    {
        $this->resetPage('schoolsPage');
    }

    public function render()
    {
        $users = User::query()
            ->with('school') // Eager load school
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Schools list for the modal
        $schools = [];
        if($this->showSchoolModal) {
            $schools = School::query()
                ->when($this->schoolSearch, function($q) {
                    $q->where('name', 'like', '%'.$this->schoolSearch.'%')
                      ->orWhere('code', 'like', '%'.$this->schoolSearch.'%')
                      ->orWhere('lga', 'like', '%'.$this->schoolSearch.'%');
                })
                ->orderBy('name')
                ->paginate(5, ['*'], 'schoolsPage'); // Use different page name for checking
        }

        return view('livewire.admin.users.user-index', [
            'users' => $users,
            'schoolsList' => $schools
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->editMode = false;
        $this->password = '';
        $this->isActive = true;
        // Default role for new users in UI
        $this->role = User::ROLE_PRINCIPAL;
        $this->dispatch('show-modal');
    }

    public function store()
    {
        $this->validateRules();
        
        // Enforce role-based school logic
        $assignedSchoolId = ($this->role === User::ROLE_PRINCIPAL) ? $this->school_id : null;

        $rawPassword = $this->password;
        if (empty($rawPassword)) {
            $rawPassword = Str::random(12);
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'school_id' => $assignedSchoolId,
            'is_active' => $this->isActive,
            'password' => Hash::make($rawPassword),
        ]);

        $emailSent = false;
        try {
            Mail::to($user)->send(new NewUserCredentialsMail($user, $rawPassword));
            $emailSent = true;
        } catch (\Exception $e) {
            // Log error if needed: \Log::error($e->getMessage());
            $emailSent = false;
        }

        $this->dispatch('hide-modal');
        
        $msg = $emailSent 
            ? 'User created and credentials sent to email.' 
            : 'User created but email could NOT be sent. Check mail settings.';
        
        session()->flash('success', $msg);
        
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            session()->flash('error', 'Cannot edit Admin accounts.');
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->isActive = $user->is_active;
        $this->school_id = $user->school_id;
        $this->selectedSchoolName = $user->school ? $user->school->name . ' (' . $user->school->lga . ')' : null;
        
        $this->password = '';
        $this->password_confirmation = '';

        $this->editMode = true;
        $this->dispatch('show-modal');
    }

    public function update()
    {
        $user = User::findOrFail($this->userId);

        if ($user->isAdmin()) {
             $this->dispatch('hide-modal');
             session()->flash('error', 'Cannot create/edit seeded Admin accounts.');
             return;
        }

        $this->validateRules(true);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->role = $this->role;
        $user->is_active = $this->isActive;
        
        // Enforce logic
        if ($this->role === User::ROLE_PRINCIPAL) {
            $user->school_id = $this->school_id;
        } else {
            $user->school_id = null;
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->dispatch('hide-modal');
        session()->flash('success', 'User updated successfully.');
        $this->resetInputFields();
    }
    
    // Custom Validation
    private function validateRules($isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userId)],
            'phone' => [$isUpdate ? 'nullable' : 'required', 'string', 'max:20'], // Phone required on create, optional on edit
            'role' => ['required', Rule::in([User::ROLE_PRINCIPAL, User::ROLE_ICT])],
            'password' => $isUpdate ? 'nullable|min:8|confirmed' : 'nullable|min:8|confirmed',
        ];
        
        // Additional business logic validation for School Assignment
        if ($this->role === User::ROLE_PRINCIPAL) {
            $rules['school_id'] = 'required'; // Must select a school
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make(
            $this->all(), 
            $rules,
            ['school_id.required' => 'A Principal must be assigned to a school.']
        );
        
        // Custom check: One Principal per School
        $validator->after(function ($validator) {
            if ($this->role === User::ROLE_PRINCIPAL && $this->school_id) {
                // Check if another user is already principal of this school
                $query = User::where('role', User::ROLE_PRINCIPAL)
                             ->where('school_id', $this->school_id);
                             
                if ($this->userId) {
                    $query->where('id', '!=', $this->userId);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('school_id', 'This school already has an assigned principal.');
                }
            }
        });
        
        $validator->validate();
    }
    
    // School Modal Actions
    public function openSchoolModal()
    {
        $this->showSchoolModal = true;
        // Don't modify main modal state
    }
    
    public function selectSchool($id, $name, $lga)
    {
        $this->school_id = $id;
        $this->selectedSchoolName = $name . ' (' . $lga . ')';
        $this->showSchoolModal = false;
    }
    
    public function clearSchool()
    {
        $this->school_id = null;
        $this->selectedSchoolName = null;
    }

    public function confirmToggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
             session()->flash('error', 'Cannot deactivate Admin accounts.');
             return;
        }

        if ($user->id === auth()->id()) {
             session()->flash('error', 'Cannot deactivate your own account.');
             return;
        }

        $this->userToToggleId = $id;
        $this->userToToggleAction = $user->is_active ? 'Deactivate' : 'Activate';
        $this->dispatch('show-confirm-modal');
    }

    public function toggleStatus()
    {
        $user = User::findOrFail($this->userToToggleId);
        
        // Double check permissions
        if ($user->isAdmin() || $user->id === auth()->id()) {
            $this->dispatch('hide-confirm-modal');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'Activated' : 'Deactivated';
        session()->flash('success', "User successfully $status.");
        $this->dispatch('hide-confirm-modal');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->role = '';
        $this->school_id = null;
        $this->selectedSchoolName = null;
        $this->isActive = true;
        $this->password = '';
        $this->password_confirmation = '';
        $this->userId = null;
        $this->schoolSearch = '';
    }
}
