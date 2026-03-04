<?php

namespace App\Livewire\Portal\Staff;

use App\Models\SchoolStaff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $filterType = '';
    public $filterPermission = '';

    // Modal State
    public $showModal = false;
    public $editMode = false;
    public $staffId;

    // Form Fields
    public $staff_type, $first_name, $last_name, $other_name, $gender;
    public $phone, $email, $qualification, $designation;
    public $is_active = true;

    // Grant Access Modal State
    public $grantStaffId;
    public $grantEmail;
    public $grantPassword;
    public $grantPasswordConfirmation;
    public $sendCredentials = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterPermission' => ['except' => ''],
    ];

    public function getFilteredStaffQueryProperty()
    {
        $user = Auth::user();
        
        return SchoolStaff::where('school_id', $user->school_id)
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterType, fn($q) => $q->where('staff_type', $this->filterType))
            ->when($this->filterPermission !== '', function($q) {
                $q->where('can_upload_students', $this->filterPermission == '1');
            });
    }

    public function render()
    {
        $staff = $this->filteredStaffQuery
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.portal.staff.index', [
            'staffList' => $staff
        ]);
    }

    public function exportCsv()
    {
        $user = Auth::user();
        $query = $this->filteredStaffQuery->with('user')->latest();
        
        // Build filename
        $filenameParts = ['staff'];
        $filenameParts[] = $user->school->code ?? 'school';
        if ($this->filterType) $filenameParts[] = $this->filterType;
        if ($this->filterPermission === '1') $filenameParts[] = 'upload_permitted';
        $filenameParts[] = date('Y-m-d');
        
        $fileName = implode('_', $filenameParts) . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($handle, [
                'Full Name', 
                'Staff Type', 
                'Phone', 
                'Email', 
                'Designation', 
                'Qualification', 
                'Upload Permission', 
                'Has Portal Account',
                'Status',
                'Created At'
            ]);

            $query->chunk(100, function ($staffList) use ($handle) {
                foreach ($staffList as $staff) {
                    fputcsv($handle, [
                        $staff->full_name,
                        ucwords(str_replace('_', ' ', $staff->staff_type)),
                        $staff->phone ?? '',
                        $staff->email ?? '',
                        $staff->designation ?? '',
                        $staff->qualification ?? '',
                        $staff->can_upload_students ? 'Yes' : 'No',
                        $staff->user_id ? 'Yes' : 'No',
                        $staff->is_active ? 'Active' : 'Inactive',
                        $staff->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $fileName);
    }
    
    // Reset pagination when filters update
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterType() { $this->resetPage(); }
    public function updatedFilterPermission() { $this->resetPage(); }

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
        $this->staffId = $id;

        $staff = SchoolStaff::where('school_id', Auth::user()->school_id)->findOrFail($id);
        
        $this->fill($staff->only([
            'staff_type', 'first_name', 'last_name', 'other_name', 'gender',
            'phone', 'email', 'qualification', 'designation', 'is_active'
        ]));
        
        $this->dispatch('show-modal');
    }

    public function store()
    {
        $this->validateRules();

        $data = $this->prepareData();
        $data['school_id'] = Auth::user()->school_id;
        $data['created_by'] = Auth::id();

        SchoolStaff::create($data);

        $this->dispatch('hide-modal');
        session()->flash('message', 'Staff record created successfully.');
    }

    public function update()
    {
        $this->validateRules();

        $staff = SchoolStaff::where('school_id', Auth::user()->school_id)->findOrFail($this->staffId);
        $data = $this->prepareData();

        $staff->update($data);
        
        if ($staff->user_id) {
            $staff->user->update([
                'name' => $staff->full_name,
                'email' => $staff->email ?? $staff->user->email,
                'phone' => $staff->phone,
            ]);
        }

        $this->dispatch('hide-modal');
        session()->flash('message', 'Staff record updated successfully.');
    }

    public function togglePermission($id)
    {
        $staff = SchoolStaff::where('school_id', Auth::user()->school_id)->findOrFail($id);
        
        if ($staff->can_upload_students) {
            // Revoke Access Logic
            $staff->can_upload_students = false;
            $staff->save();
            
            // Optionally disable user logic here if needed
            session()->flash('message', 'Permission revoked.');
        } else {
            // Grant Access - Open Modal
            $this->openGrantAccessModal($id);
        }
    }

    public function openGrantAccessModal($id)
    {
        $staff = SchoolStaff::where('school_id', Auth::user()->school_id)->findOrFail($id);
        $this->grantStaffId = $id;
        $this->grantEmail = $staff->email;
        $this->grantPassword = null;
        $this->grantPasswordConfirmation = null;
        $this->sendCredentials = true;
        $this->resetValidation();
        
        $this->dispatch('show-grant-modal');
    }

    public function grantAccess()
    {
        // Validation
        $rules = [
            'grantEmail' => 'required|email|max:255',
            'grantPassword' => 'nullable|min:8|confirmed',
        ];
        
        // Ensure email is unique in users table, ignoring this staff's user if exists
        $staff = SchoolStaff::where('school_id', Auth::user()->school_id)->findOrFail($this->grantStaffId);
        if ($staff->user_id) {
            $rules['grantEmail'] = 'required|email|max:255|unique:users,email,' . $staff->user_id;
        } else {
            $rules['grantEmail'] = 'required|email|max:255|unique:users,email';
        }

        $this->validate($rules);

        // Logic
        $password = $this->grantPassword ?: Str::random(12);
        
        if (!$staff->user_id) {
            // Create New User
            $user = User::create([
                'name' => $staff->full_name,
                'email' => $this->grantEmail,
                'phone' => $staff->phone,
                'password' => Hash::make($password),
                'role' => User::ROLE_SCHOOL_STAFF, // school-staff
                'school_id' => $staff->school_id,
                'is_active' => true,
            ]);
            $staff->user_id = $user->id;
        } else {
            // Update Existing User
            $user = $staff->user;
            $user->email = $this->grantEmail;
            $user->password = Hash::make($password); // Reset password
            $user->is_active = true;
            $user->save();
        }

        // Update Staff
        $staff->email = $this->grantEmail;
        $staff->can_upload_students = true;
        $staff->is_active = true;
        $staff->save();

        // Send Email
        if ($this->sendCredentials) {
            try {
                 Mail::to($user->email)->send(new \App\Mail\NewUserCredentialsMail($user, $password));
            } catch (\Exception $e) {
                 // Log error or just notify user
                 $this->dispatch('hide-grant-modal');
                 session()->flash('message', 'Access granted, but failed to send email: ' . $e->getMessage()); // In prod, hide detailed error
                 return;
            }
        }

        $this->dispatch('hide-grant-modal');
        session()->flash('message', 'Access granted & credentials sent successfully.');
    }

    private function prepareData()
    {
        return [
            'staff_type' => $this->staff_type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_name' => $this->other_name,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email, // Nullable
            'qualification' => $this->qualification,
            'designation' => $this->designation,
            'is_active' => $this->is_active,
        ];
    }

    private function validateRules()
    {
        $rules = [
            'staff_type' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255', 
        ];

        if (!empty($this->email)) {
             // If creating new staff who might become a user, check unique email globally OR enforce uniqueness later at grant time?
             // Let's enforce it now if provided to avoid headaches later.
             // But if editing existing staff, ignore their linked user.
             if ($this->editMode && $this->staffId) {
                 $staff = SchoolStaff::find($this->staffId);
                 if ($staff && $staff->user_id) {
                     $rules['email'] = 'nullable|email|unique:users,email,' . $staff->user_id;
                 } else {
                     $rules['email'] = 'nullable|email|unique:users,email'; 
                 }
             } else {
                  $rules['email'] = 'nullable|email|unique:users,email'; 
             }
        }

        $this->validate($rules);
    }
    
    public function resetForm()
    {
        $this->reset([
            'staff_type', 'first_name', 'last_name', 'other_name', 'gender',
            'phone', 'email', 'qualification', 'designation', 'is_active', 'staffId'
        ]);
    }
}
