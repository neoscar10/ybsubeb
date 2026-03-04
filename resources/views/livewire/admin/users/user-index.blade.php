<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-0">User Management</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-success add-btn" wire:click="create">
                                <i class="ri-add-line align-bottom me-1"></i> Add User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="search-box">
                                <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search by name or email...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-select" wire:model.live="roleFilter">
                                <option value="">All Roles</option>
                                <option value="chairman">Chairman</option>
                                <option value="director">Director</option>
                                <option value="principal">Principal</option>
                                <option value="ict-team">ICT Team</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-nowrap table-striped-columns mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                        <td>
                                            @if($user->role === 'chairman')
                                                <span class="badge bg-danger">Chairman</span>
                                            @elseif($user->role === 'director')
                                                <span class="badge bg-warning">Director</span>
                                            @elseif($user->role === 'principal')
                                                <span class="badge bg-info">Principal</span>
                                            @else
                                                <span class="badge bg-primary">ICT Team</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->school)
                                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $user->school->name }}">
                                                    {{ $user->school->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$user->isAdmin())
                                                <div class="hstack gap-3 fs-15">
                                                    <a href="javascript:void(0);" wire:click="edit({{ $user->id }})" class="link-primary"><i class="ri-settings-4-line"></i></a>
                                                    
                                                    @if($user->id !== auth()->id())
                                                        <a href="javascript:void(0);" wire:click="confirmToggleStatus({{ $user->id }})" class="link-danger">
                                                            @if($user->is_active)
                                                                <i class="ri-toggle-fill"></i>
                                                            @else
                                                                <i class="ri-toggle-line"></i>
                                                            @endif
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="ri-lock-2-fill"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{ $editMode ? 'Edit User' : 'Add User' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" placeholder="Enter name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="Enter email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" wire:model="phone" placeholder="Enter phone">
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" wire:model.live="role">
                                <option value="">Select Role</option>
                                <option value="principal">Principal</option>
                                <option value="ict-team">ICT Team</option>
                            </select>
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Principal School Assignment -->
                        @if($role === 'principal')
                            <div class="mb-3">
                                <label class="form-label">Assigned School <span class="text-danger">*</span></label>
                                
                                <div class="card border border-dashed shadow-none mb-0">
                                    <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                        <div>
                                            @if($selectedSchoolName)
                                                <h6 class="fs-13 mb-1">{{ $selectedSchoolName }}</h6>
                                                <span class="badge bg-success-subtle text-success">Selected</span>
                                            @else
                                                <span class="text-muted fst-italic">No school selected</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if($selectedSchoolName)
                                                <button type="button" class="btn btn-sm btn-ghost-danger" wire:click="clearSchool">Remove</button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-soft-primary" wire:click="openSchoolModal">
                                                {{ $selectedSchoolName ? 'Change' : 'Select School' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" wire:model="school_id">
                                @error('school_id') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="mb-3 form-check form-switch form-switch-lg">
                            <input class="form-check-input" type="checkbox" role="switch" id="isActive" wire:model="isActive">
                            <label class="form-check-label" for="isActive">Active Account</label>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <h6 class="mb-3 fw-semibold">Password <small class="text-muted fw-normal">{{ $editMode ? '(Leave blank to keep)' : '(Leave blank to auto-generate)' }}</small></h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" wire:model="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $editMode ? 'update' : 'store' }}" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store, update">
                            {{ $editMode ? 'Update User' : 'Create User' }}
                        </span>
                        <div wire:loading wire:target="store, update">
                            <span class="d-flex align-items-center">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                <span>Processing...</span>
                            </span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- School Selection Modal (Nested or separate, treating as separate but called from user logic) -->
    <div class="modal fade" id="schoolSelectModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select School</h5>
                    <button type="button" class="btn-close" wire:click="$set('showSchoolModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Search school by name, code or LGA..." wire:model.live.debounce.300ms="schoolSearch">
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>LGA</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schoolsList as $school)
                                    <tr>
                                        <td>{{ $school->code ?: '-' }}</td>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->lga }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                wire:click="selectSchool({{ $school->id }}, '{{ addslashes($school->name) }}', '{{ $school->lga }}')">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No schools found matching your search.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        @if(!is_array($schoolsList) && method_exists($schoolsList, 'links'))
                            {{ $schoolsList->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Are you sure you want to <strong>{{ $userToToggleAction }}</strong> this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="toggleStatus">{{ $userToToggleAction }}</button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-modal', () => {
            var myModalEl = document.getElementById('userModal');
            var myModal = bootstrap.Modal.getOrCreateInstance(myModalEl);
            myModal.show();
        });

        $wire.on('hide-modal', () => {
            var myModalEl = document.getElementById('userModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if(modal) {
                modal.hide();
            }
        });

        $wire.on('show-confirm-modal', () => {
            var myModalEl = document.getElementById('confirmModal');
            var myModal = bootstrap.Modal.getOrCreateInstance(myModalEl);
            myModal.show();
        });

        $wire.on('hide-confirm-modal', () => {
            var myModalEl = document.getElementById('confirmModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if(modal) {
                modal.hide();
            }
        });
        
        // Watch for 'showSchoolModal' property changes handled by Livewire, 
        // but if we need manual triggers:
        $wire.watch('showSchoolModal', (value) => {
            var el = document.getElementById('schoolSelectModal');
            var modal = bootstrap.Modal.getOrCreateInstance(el);
            if(value) {
                // Ensure user modal stays open (bootstrap nested modals sometimes tricky)
                // Actually, best practice on the web is to swap them or stack them.
                // Bootstrap 5 supports stacked modals if you initialize them right.
               modal.show();
            } else {
               modal.hide();
            }
        });
    </script>
    @endscript
</div>
