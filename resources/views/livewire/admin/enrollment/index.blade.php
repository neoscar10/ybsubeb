<div>
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Enrollment & Pupil Analytics</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Enrollment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Pupils</p>
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
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Male vs Female</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                {{ number_format($kpis['male']) }} <span class="text-muted fs-12">M</span> / 
                                {{ number_format($kpis['female']) }} <span class="text-muted fs-12">F</span>
                            </h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-men-line text-info"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Active Schools</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['school_count']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-building-line text-warning"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Avg Pupils/School</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($kpis['avg']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-bar-chart-groupped-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row g-3">
                <div class="col-lg-3 col-sm-6">
                    <div class="search-box">
                        <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search school...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <select class="form-select" wire:model.live="lga">
                        <option value="">All LGAs</option>
                        @foreach($lgas as $l)
                            <option value="{{ $l }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <select class="form-select" wire:model.live="school_id">
                        <option value="">All Schools</option>
                        @foreach($filterSchools as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <select class="form-select" wire:model.live="class_id">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <select class="form-select" wire:model.live="status">
                        <option value="all">Status: All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="transferred">Transferred</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <select class="form-select" wire:model.live="gender">
                        <option value="">Gender: All</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-auto">
                     <button type="button" class="btn btn-primary w-100" wire:click="resetFilters">
                         <i class="ri-refresh-line me-1 align-bottom"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card">
         <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">School Enrollment Summary</h5>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-success" wire:click="exportCsv">
                        <i class="ri-file-download-line align-bottom me-1"></i> Download CSV
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card mb-3">
                <table class="table align-middle table-nowrap mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>School Name</th>
                            <th>LGA</th>
                            <th class="text-end">Total Pupils</th>
                            <th class="text-end">Male</th>
                            <th class="text-end">Female</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td><span class="text-muted">{{ $school->code ?: '-' }}</span></td>
                            <td>
                                <h6 class="fs-14 mb-0">{{ $school->name }}</h6>
                            </td>
                            <td>{{ $school->lga }}</td>
                            <td class="text-end fw-bold">{{ number_format($school->filtered_total) }}</td>
                            <td class="text-end">{{ number_format($school->filtered_male) }}</td>
                            <td class="text-end">{{ number_format($school->filtered_female) }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-soft-info" wire:click="openBreakdownModal({{ $school->id }})">
                                    View Breakdown
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="noresult">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">No Matching Data</h5>
                                    <p class="text-muted mb-0">Try adjusting your filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $schools->links() }}
            </div>
        </div>
    </div>

    <!-- Breakdown Modal -->
    @if($showBreakdownModal && $breakdownSchool)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">{{ $breakdownSchool->name }}</h5>
                        <p class="text-muted fs-12 mb-0">{{ $breakdownSchool->code }} | {{ $breakdownSchool->lga }}</p>
                    </div>
                    <button type="button" class="btn-close" wire:click="$set('showBreakdownModal', false)"></button>
                </div>
                <div class="modal-body">
                    <!-- Filters Check -->
                    @if($class_id || $gender || $status != 'all')
                        <div class="alert alert-info py-2 px-3 mb-3 fs-12">
                             <strong>Filtering applied:</strong> 
                             {{ $class_id ? 'Class Selected •' : '' }} 
                             {{ $gender ? 'Gender: '.ucfirst($gender).' •' : '' }}
                             {{ $status ? 'Status: '.ucfirst($status) : '' }}
                        </div>
                    @endif

                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Class</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Male</th>
                                    <th class="text-end">Female</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($breakdownStats as $row)
                                <tr>
                                    <td>{{ $row['class_name'] }}</td>
                                    <td class="text-end fw-medium">{{ number_format($row['total']) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row['male']) }}</td>
                                    <td class="text-end text-muted">{{ number_format($row['female']) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No students found matching filters.</td>
                                </tr>
                                @endforelse
                                <!-- Quick Total Row -->
                                <tr class="table-active fw-bold border-top">
                                    <td>TOTAL</td>
                                    <td class="text-end">{{ number_format(collect($breakdownStats)->sum('total')) }}</td>
                                    <td class="text-end">{{ number_format(collect($breakdownStats)->sum('male')) }}</td>
                                    <td class="text-end">{{ number_format(collect($breakdownStats)->sum('female')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showBreakdownModal', false)">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="exportBreakdown">Download CSV</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
