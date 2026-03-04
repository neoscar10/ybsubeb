<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Staff Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Portal</a></li>
                        <li class="breadcrumb-item active">Staff</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">School Staff Directory</h5>
                        <div class="flex-shrink-0">
                            {{-- Add Staff --}}
                            <button wire:click="create" class="btn btn-primary add-btn waves-effect waves-light">
                                <i class="ri-add-line align-bottom me-1"></i> Add Staff
                            </button>
                            
                            {{-- Export CSV --}}
                            <button wire:click="exportCsv" wire:loading.attr="disabled" class="btn btn-soft-success ms-1 waves-effect waves-light">
                                <i class="mdi mdi-download align-bottom me-1"></i> <span wire:loading.remove wire:target="exportCsv">Export CSV</span><span wire:loading wire:target="exportCsv">Exporting...</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="card-header border-0 border-bottom border-dashed">
                    <div class="row g-2">
                        <div class="col-xl-4 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, phone...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <select class="form-select" wire:model.live="filterType">
                                <option value="">All Staff Types</option>
                                <option value="teacher">Teacher</option>
                                <option value="non_teaching">Non-Teaching Staff</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <select class="form-select" wire:model.live="filterPermission">
                                <option value="">All Permissions</option>
                                <option value="1">Can Upload Students</option>
                                <option value="0">Restricted Access</option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-sm-6">
                             <button type="button" class="btn btn-ghost-secondary w-100" wire:click="$set('search', '')">
                                <i class="ri-refresh-line align-bottom me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive table-card mb-3">
                        <table class="table align-middle table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Permissions</th>
                                    <th scope="col" style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffList as $staff)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ substr($staff->first_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="fs-14 m-0">
                                                    <a href="javascript:void(0);" wire:click="edit({{ $staff->id }})" class="text-reset">{{ $staff->full_name }}</a>
                                                </h5>
                                                @if($staff->user_id)
                                                    <small class="text-success"><i class="ri-user-check-line align-bottom"></i> Portal User</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $staff->staff_type === 'teacher' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }} text-uppercase">
                                            {{ str_replace('_', ' ', $staff->staff_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fs-13"><i class="ri-phone-fill align-middle me-1 text-muted"></i> {{ $staff->phone ?? '-' }}</div>
                                        <div class="fs-13"><i class="ri-mail-fill align-middle me-1 text-muted"></i> {{ $staff->email ?? '-' }}</div>
                                    </td>
                                    <td>{{ $staff->designation ?? '-' }}</td>
                                    <td>
                                        @if($staff->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch form-switch-md" dir="ltr">
                                            <input type="checkbox" class="form-check-input" id="perm_{{ $staff->id }}" 
                                                wire:click="togglePermission({{ $staff->id }})"
                                                {{ $staff->can_upload_students ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $staff->id }}">
                                                {{ $staff->can_upload_students ? 'Allowed' : 'Denied' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0);" wire:click="edit({{ $staff->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                @if(!$staff->can_upload_students)
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0);" wire:click="togglePermission({{ $staff->id }})">
                                                        <i class="ri-shield-check-line align-bottom me-2 text-muted"></i> Grant Upload Permission
                                                    </a>
                                                </li>
                                                @elseif($staff->user_id)
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);" 
                                                            onclick="confirm('Are you sure you want to resend login credentials to this staff member?') || event.stopImmediatePropagation()"
                                                            wire:click="openGrantAccessModal({{ $staff->id }})">
                                                            <i class="ri-lock-password-line align-bottom me-2 text-muted"></i> Reset/Resend Login
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="noresult">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                <h5 class="mt-2">No Staff Records Found</h5>
                                                <p class="text-muted mb-0">Get started by adding teachers and staff members to your school.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        {{ $staffList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Staff Modal -->
    <div wire:ignore.self class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalLabel">{{ $editMode ? 'Edit Staff Member' : 'Add New Staff' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                        <div class="row g-3">
                            <!-- Type & Name -->
                            <div class="col-md-4">
                                <label class="form-label">Staff Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('staff_type') is-invalid @enderror" wire:model="staff_type">
                                    <option value="">Select Type</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="non_teaching">Non-Teaching Staff</option>
                                </select>
                                @error('staff_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8"></div> 

                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name" placeholder="First Name">
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name" placeholder="Surname">
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Name</label>
                                <input type="text" class="form-control" wire:model="other_name" placeholder="Middle Name">
                            </div>

                            <!-- Contact & Personal -->
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" wire:model="gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" wire:model="phone" placeholder="080...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="email@example.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Job Info -->
                            <div class="col-md-6">
                                <label class="form-label">Qualification</label>
                                <input type="text" class="form-control" wire:model="qualification" placeholder="e.g. NCE, B.Ed, SSCE">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation / Role</label>
                                <input type="text" class="form-control" wire:model="designation" placeholder="e.g. Class Teacher, Admin Officer">
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isActive" wire:model="is_active">
                                    <label class="form-check-label" for="isActive">Staff Account is Active</label>
                                </div>
                            </div>
                            
                            @if($email == '')
                            <div class="col-12">
                                <div class="alert alert-warning border-0" role="alert">
                                    <strong>Note:</strong> To grant this staff member access to manage students on the portal, you must provide a valid email address.
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Save Changes' : 'Add Staff' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Grant Access/Reset Password Modal -->
    <div wire:ignore.self class="modal fade" id="grantAccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Grant Portal Access & Setup Credentials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="grantAccess">
                        <div class="alert alert-info border-0">
                            <strong><i class="ri-information-line me-1"></i> Info:</strong> 
                            This action will create or update the portal user account for the staff member and email them their login credentials.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('grantEmail') is-invalid @enderror" wire:model="grantEmail" placeholder="Enter valid email">
                            <div class="form-text">Login credentials will be sent to this email.</div>
                            @error('grantEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-muted">(Optional)</span></label>
                            <input type="password" class="form-control @error('grantPassword') is-invalid @enderror" wire:model="grantPassword" placeholder="Leave blank to auto-generate">
                            @error('grantPassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if($grantPassword)
                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" wire:model="grantPasswordConfirmation" placeholder="Confirm password">
                        </div>
                        @endif

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendCreds" wire:model="sendCredentials" checked disabled> <!-- Force checked per requirements mostly, but let's keep it visible -->
                                <label class="form-check-label" for="sendCreds">
                                    Send credentials to email
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <span wire:loading.remove wire:target="grantAccess">Grant Access</span>
                                <span wire:loading wire:target="grantAccess">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-modal', () => {
            var modal = new bootstrap.Modal(document.getElementById('staffModal'));
            modal.show();
        });

        $wire.on('hide-modal', () => {
            var modalEl = document.getElementById('staffModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });

        $wire.on('show-grant-modal', () => {
            var modal = new bootstrap.Modal(document.getElementById('grantAccessModal'));
            modal.show();
        });

        $wire.on('hide-grant-modal', () => {
            var modalEl = document.getElementById('grantAccessModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>
    @endscript
</div>
