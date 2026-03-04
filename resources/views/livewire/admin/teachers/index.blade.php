<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Teachers & Staff</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Teachers & Staff</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-xl-2 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Staff</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['total']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-group-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Teachers</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['teachers']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-user-star-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Non-Teaching</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['non_teaching']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-briefcase-line text-info"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Portal Accounts</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['portal_users']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-shield-user-line text-success"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Upload Perms</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['upload_access']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                <i class="ri-upload-cloud-line text-secondary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row g-3">
                <div class="col-xxl-3 col-sm-6">
                    <div class="search-box">
                        <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search name, email, phone...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!-- Filters -->
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="lga">
                        <option value="">All LGAs</option>
                        @foreach($lgas as $lgaItem)
                            <option value="{{ $lgaItem }}">{{ $lgaItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="school_id">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="staff_type">
                        <option value="">All Types</option>
                        <option value="teaching_staff">Teaching Staff</option>
                        <option value="non_teaching_staff">Non-Teaching Staff</option>
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <select class="form-select" wire:model.live="status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-xxl-1 col-sm-2">
                    <button type="button" class="btn btn-primary w-100" wire:click="resetFilters">
                         <i class="ri-equalizer-fill me-1 align-bottom"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Staff List</h5>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-success" wire:click="exportCsv">
                        <i class="ri-file-download-line align-bottom me-1"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card mb-3">
                <table class="table align-middle table-nowrap mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Staff Name</th>
                            <th>School & LGA</th>
                            <th>Contact</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Upload Perm</th>
                            <th>Portal</th>
                            <th>Created</th>
                            <th>Action</th>
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
                                                {{ substr($staff->first_name, 0, 1) }}{{ substr($staff->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fs-14 m-0">
                                            <a href="#" class="text-reset">{{ $staff->full_name }}</a>
                                        </h5>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ ucwords(str_replace('_', ' ', $staff->staff_type)) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h6 class="fs-13 mb-1">{{ $staff->school->name ?? 'N/A' }}</h6>
                                <span class="text-muted fs-12">{{ $staff->school->lga ?? '' }}</span>
                            </td>
                            <td>
                                <div><i class="ri-phone-line me-1 text-muted"></i> {{ $staff->phone }}</div>
                                <div><i class="ri-mail-line me-1 text-muted"></i> {{ $staff->email }}</div>
                            </td>
                            <td>{{ $staff->designation ?? '-' }}</td>
                            <td>
                                @if($staff->is_active)
                                    <span class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger text-uppercase">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($staff->can_upload_students)
                                    <span class="badge bg-success-subtle text-success">Yes</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">No</span>
                                @endif
                            </td>
                            <td>
                                @if($staff->user)
                                    <span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> Linked</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">None</span>
                                @endif
                            </td>
                            <td>{{ $staff->created_at->format('d M, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" wire:click="openViewModal({{ $staff->id }})">
                                                <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Details
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="noresult">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We did not find any staff matching your search.</p>
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

    <!-- View Modal -->
    @if($showViewModal && $viewStaff)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Staff Details: {{ $viewStaff->full_name }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showViewModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Employment Info</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr><th class="ps-0" scope="row">School:</th><td class="text-muted">{{ $viewStaff->school->name ?? 'N/A' }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Type:</th><td class="text-muted">{{ ucwords(str_replace('_', ' ', $viewStaff->staff_type)) }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Designation:</th><td class="text-muted">{{ $viewStaff->designation ?? '-' }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Qualification:</th><td class="text-muted">{{ $viewStaff->qualification ?? '-' }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Status:</th>
                                            <td>
                                                @if($viewStaff->is_active)
                                                    <span class="badge bg-success-subtle text-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Personal & Contact</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr><th class="ps-0" scope="row">Email:</th><td class="text-muted">{{ $viewStaff->email }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Phone:</th><td class="text-muted">{{ $viewStaff->phone }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Gender:</th><td class="text-muted">{{ ucfirst($viewStaff->gender) }}</td></tr>
                                        <tr><th class="ps-0" scope="row">DOB:</th><td class="text-muted">{{ $viewStaff->date_of_birth ? $viewStaff->date_of_birth->format('d M, Y') : '-' }}</td></tr>
                                        <tr><th class="ps-0" scope="row">Address:</th><td class="text-muted">{{ $viewStaff->address ?? '-' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">System Access</h6>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><strong>Can Upload Students:</strong> 
                                                <span class="badge {{ $viewStaff->can_upload_students ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $viewStaff->can_upload_students ? 'Yes' : 'No' }}
                                                </span>
                                            </p>
                                            <p class="mb-0"><strong>Portal Account:</strong>
                                                @if($viewStaff->user)
                                                    <span class="text-success"><i class="ri-check-double-line"></i> Linked ({{ $viewStaff->user->email }})</span>
                                                @else
                                                    <span class="text-muted">Not linked</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showViewModal', false)">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
