<div>
    <!-- KPI Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Schools</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['total']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-building-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Active / Inactive</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                {{ number_format($kpis['active']) }} <span class="text-muted fs-12">Act</span> / 
                                {{ number_format($kpis['inactive']) }} <span class="text-muted fs-12">In</span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-checkbox-circle-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Primary / JSS</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                {{ number_format($kpis['primary']) }} <span class="text-muted fs-12">Pri</span> / 
                                {{ number_format($kpis['jss']) }} <span class="text-muted fs-12">JSS</span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-git-branch-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Principals</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['has_principal']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-user-settings-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Schools List</h5>
                <div class="flex-shrink-0">
                    <button wire:click="create" class="btn btn-success add-btn">
                        <i class="ri-add-line align-bottom me-1"></i> Add School
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search for school, code or LGA...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="filterLga">
                        <option value="">All LGAs</option>
                        @foreach($lgas as $lgaItem)
                            <option value="{{ $lgaItem }}">{{ $lgaItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="filterType">
                        <option value="">All Types</option>
                        @foreach($types as $typeItem)
                            <option value="{{ $typeItem }}">{{ ucfirst(str_replace('_', ' ', $typeItem)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="filterStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
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

            <div class="table-responsive table-card mb-4">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">School Name</th>
                            <th scope="col">LGA</th>
                            <th scope="col">Type</th>
                            <th scope="col">Students (M/F)</th>
                            <th scope="col">Teachers (M/F)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td><a href="#" class="fw-medium link-primary">{{ $school->code ?: 'N/A' }}</a></td>
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->lga }}</td>
                            <td>
                                <span class="badge bg-info-subtle text-info text-uppercase">{{ str_replace('_', ' ', $school->school_type) }}</span>
                            </td>
                            <td>
                                <div>{{ $school->total_students }}</div>
                                <div class="text-muted fs-11">({{ $school->students_male }} / {{ $school->students_female }})</div>
                            </td>
                            <td>
                                <div>{{ $school->total_teachers }}</div>
                                <div class="text-muted fs-11">({{ $school->teachers_male }} / {{ $school->teachers_female }})</div>
                            </td>
                            <td>
                                @if($school->status === 'active')
                                    <span class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger text-uppercase">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($school->last_updated_at)
                                    <div>{{ $school->last_updated_at->format('d M, Y') }}</div>
                                    <div class="text-muted fs-11">{{ $school->last_updated_at->format('h:i A') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);" wire:click="edit({{ $school->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" wire:click="openViewModal({{ $school->id }})"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Details</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="noresult">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ schools We did not find any school for you search.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-2">
                {{ $schools->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editMode ? 'Edit School' : 'Add New School' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                        
                        <!-- Tabs or Sections could go here if very complex, but for now simple grid -->
                        <h6 class="fs-14 text-muted mb-3">Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">School Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" placeholder="Enter school name">
                                @error('name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">School Code</label>
                                <input type="text" class="form-control" wire:model="code" placeholder="Unique ID">
                                @error('code') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="school_type">
                                    <option value="">Select</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                    @endforeach
                                </select>
                                @error('school_type') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">LGA <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="lga">
                                    <option value="">Select LGA</option>
                                    @foreach($lgas as $lgaItem)
                                        <option value="{{ $lgaItem }}">{{ $lgaItem }}</option>
                                    @endforeach
                                </select>
                                @error('lga') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ward</label>
                                <input type="text" class="form-control" wire:model="ward" placeholder="Ward">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Community</label>
                                <input type="text" class="form-control" wire:model="community" placeholder="Community">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" wire:model="address" rows="2"></textarea>
                            </div>
                        </div>

                        <h6 class="fs-14 text-muted mb-3 mt-4">Statistics (Baseline)</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Male Students</label>
                                <input type="number" class="form-control" wire:model="students_male">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Female Students</label>
                                <input type="number" class="form-control" wire:model="students_female">
                            </div>
                             <div class="col-md-3">
                                <label class="form-label">Male Teachers</label>
                                <input type="number" class="form-control" wire:model="teachers_male">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Female Teachers</label>
                                <input type="number" class="form-control" wire:model="teachers_female">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Classrooms</label>
                                <input type="number" class="form-control" wire:model="total_classrooms">
                            </div>
                        </div>

                        <h6 class="fs-14 text-muted mb-3 mt-4">Infrastructure & Status</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" role="switch" id="hasWater" wire:model="has_water">
                                    <label class="form-check-label" for="hasWater">Has Water Source</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" role="switch" id="hasToilets" wire:model="has_toilets">
                                    <label class="form-check-label" for="hasToilets">Has Toilets</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" role="switch" id="hasElectricity" wire:model="has_electricity">
                                    <label class="form-check-label" for="hasElectricity">Has Electricity</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" wire:model="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" wire:model="notes" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" wire:click="$set('showModal', false)">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update School' : 'Add School' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- View Modal -->
    @if($showViewModal && $viewSchool)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        School Details: <span class="text-primary">{{ $viewSchool->name }}</span>
                        <small class="text-muted ms-2">({{ $viewSchool->code }})</small>
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showViewModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- School Info -->
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title mb-0">General Information</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>LGA:</strong> {{ $viewSchool->lga }}</p>
                                    <p class="mb-1"><strong>Ward:</strong> {{ $viewSchool->ward ?? '-' }}</p>
                                    <p class="mb-1"><strong>Community:</strong> {{ $viewSchool->community ?? '-' }}</p>
                                    <p class="mb-1"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $viewSchool->school_type)) }}</p>
                                    <p class="mb-1"><strong>Status:</strong> 
                                        @if($viewSchool->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>Address:</strong> {{ $viewSchool->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Principal Info -->
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title mb-0">Principal Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($viewSchool->principal)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ substr($viewSchool->principal->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">{{ $viewSchool->principal->name }}</h6>
                                                <small class="text-muted">Appointed Principal</small>
                                            </div>
                                        </div>
                                        <p class="mb-1"><i class="ri-mail-line me-2 text-muted"></i> {{ $viewSchool->principal->email }}</p>
                                        <p class="mb-1"><i class="ri-phone-line me-2 text-muted"></i> {{ $viewSchool->principal->phone ?? 'No phone' }}</p>
                                        <p class="mb-0">
                                            <span class="badge {{ $viewSchool->principal->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ $viewSchool->principal->is_active ? 'Active Account' : 'Inactive Account' }}
                                            </span>
                                        </p>
                                    @else
                                        <div class="text-center py-3">
                                            <div class="avatar-sm mx-auto mb-3">
                                                <div class="avatar-title bg-light text-warning rounded-circle fs-24">
                                                    <i class="ri-user-unfollow-line"></i>
                                                </div>
                                            </div>
                                            <h6 class="mb-1">No Principal Assigned</h6>
                                            <p class="text-muted mb-0 fs-12">Assign a principal via User Management.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="col-md-12">
                            <div class="card shadow-none border mb-0">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title mb-0">Statistics & Infrastructure</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 col-md-3 border-end">
                                            <h5 class="mb-1">{{ $viewSchool->total_students }}</h5>
                                            <p class="text-muted mb-0">Students</p>
                                            <small class="text-muted fs-11">(M:{{ $viewSchool->students_male }} / F:{{ $viewSchool->students_female }})</small>
                                        </div>
                                        <div class="col-6 col-md-3 border-end">
                                            <h5 class="mb-1">{{ $viewSchool->total_teachers }}</h5>
                                            <p class="text-muted mb-0">Teachers</p>
                                            <small class="text-muted fs-11">(M:{{ $viewSchool->teachers_male }} / F:{{ $viewSchool->teachers_female }})</small>
                                        </div>
                                        <div class="col-6 col-md-3 border-end">
                                            <h5 class="mb-1">{{ $viewSchool->total_classrooms }}</h5>
                                            <p class="text-muted mb-0">Classrooms</p>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="d-flex flex-column gap-1 align-items-center mt-1">
                                                <span class="badge {{ $viewSchool->has_water ? 'bg-success' : 'bg-secondary' }}">Water Source</span>
                                                <span class="badge {{ $viewSchool->has_toilets ? 'bg-success' : 'bg-secondary' }}">Toilets</span>
                                                <span class="badge {{ $viewSchool->has_electricity ? 'bg-success' : 'bg-secondary' }}">Electricity</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($viewSchool->notes)
                                        <div class="mt-3 pt-3 border-top">
                                            <p class="fw-medium mb-1">Notes:</p>
                                            <p class="text-muted mb-0">{{ $viewSchool->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showViewModal', false)">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="edit({{ $viewSchool->id }})">Edit School</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
