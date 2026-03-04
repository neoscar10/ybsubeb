<div class="page-content">
    <div class="container-fluid">

        @if($noSchool ?? false)
            <div class="alert alert-danger">Your account is not linked to a valid school profile.</div>
        @else

        <!-- Header -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">School Dashboard</h4>
                        <p class="text-muted mb-0">Overview of your school’s students, staff, and needs submissions.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Class</label>
                                <select class="form-select" wire:model.live="filterClass">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" wire:model.live="filterGender">
                                    <option value="">All Genders</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-light w-100" wire:click="resetFilters">
                                    <i class="ri-restart-line me-1"></i> Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if(Auth::user()->hasRole('principal'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('principal.assessment.submit') }}" class="btn btn-soft-success">
                                <i class="ri-add-circle-line align-middle me-1 fs-16"></i> Submit Needs Assessment
                            </a>
                            <button wire:click="exportStudentsCsv" class="btn btn-soft-primary">
                                <i class="ri-download-2-line align-middle me-1 fs-16"></i> Download Students List
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recently Added Needs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Recently Added Needs</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentItems as $item)
                                    <tr>
                                        <td>
                                            <h6 class="fs-13 mb-0">{{ Str::limit($item->title, 20) }}</h6>
                                            <span class="text-muted fs-12">{{ $item->category }}</span>
                                        </td>
                                        <td>
                                            @if($item->priority == 'critical') <span class="badge bg-danger">Critical</span>
                                            @elseif($item->priority == 'high') <span class="badge bg-warning">High</span>
                                            @else <span class="badge bg-success">Normal</span> @endif
                                        </td>
                                        <td>
                                            @if($item->status == 'approved') <span class="badge bg-soft-success text-success">Approved</span>
                                            @elseif($item->status == 'rejected') <span class="badge bg-soft-danger text-danger">Rejected</span>
                                            @else <span class="badge bg-soft-warning text-warning">Pending</span> @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center py-4">
                                                <div class="avatar-md mx-auto mb-4">
                                                    <div class="avatar-title bg-light text-primary rounded-circle fs-4x">
                                                        <i class="ri-survey-line fs-24"></i>
                                                    </div>
                                                </div>
                                                <h5 class="mt-2">No Needs Found</h5>
                                                <p class="text-muted mb-3">No recent needs assessment items have been added.</p>
                                                <a href="{{ route('principal.assessment.submit') }}" class="btn btn-soft-success btn-sm">Submit Needs Assessment</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row gy-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Students</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div class="flex-grow-1 overflow-hidden me-3">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-truncate">{{ number_format($studentCount) }}</h4>
                                <a href="{{ route('portal.students.index') }}" class="text-decoration-underline text-muted text-truncate d-block">View details</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="ri-user-smile-line text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Staff</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div class="flex-grow-1 overflow-hidden me-3">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-truncate">{{ number_format($teacherCount) }}</h4>
                                @if(Auth::user()->hasRole('principal'))
                                <a href="{{ route('portal.staff.index') }}" class="text-decoration-underline text-muted text-truncate d-block">Manage staff</a>
                                @endif
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                    <i class="ri-briefcase-line text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fixed Assessment Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Assessment Status</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div class="flex-grow-1 overflow-hidden me-3">
                                <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-truncate" title="{{ ucfirst($needsStats['status']) }}">{{ ucfirst($needsStats['status']) }}</h4>
                                <a href="javascript:void(0);" class="text-decoration-underline text-muted text-truncate d-block">{{ $windowTitle }}</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                    <i class="ri-survey-line text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate h-100 mb-0">
                    <div class="card-body">
                         <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Items</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div class="flex-grow-1 overflow-hidden me-3">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-truncate">{{ $needsStats['pending_count'] }}</h4>
                                <a href="{{ route('principal.assessment.submit') }}" class="text-decoration-underline text-muted text-truncate d-block">Go to submission</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger rounded fs-3">
                                    <i class="ri-error-warning-line text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row gy-4 mb-4">
            <div class="col-xl-6">
                <div class="card h-100 mb-0">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Students by Class</h4>
                    </div>
                    <div class="card-body" style="min-height: 380px;" wire:ignore>
                        <div id="students_class_chart" data-colors='["--vz-primary"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card h-100 mb-0">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Gender Distribution</h4>
                    </div>
                    <div class="card-body" style="min-height: 380px;" wire:ignore>
                        <div id="gender_dist_chart" data-colors='["--vz-primary", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
             <div class="col-xl-3">
                <div class="card h-100 mb-0">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Staff Breakdown</h4>
                    </div>
                    <div class="card-body" style="min-height: 380px;" wire:ignore>
                        <div id="staff_breakdown_chart" data-colors='["--vz-success", "--vz-warning", "--vz-secondary"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>

@assets
<script src="{{ asset('themes/velzon/libs/apexcharts/apexcharts.min.js') }}"></script>
@endassets

@script
<script>
    function getChartColorsArray(chartId) {
        if (document.getElementById(chartId) !== null) {
            var colors = document.getElementById(chartId).getAttribute("data-colors");
            if (colors) {
                colors = JSON.parse(colors);
                return colors.map(function (value) {
                    var newValue = value.replace(" ", "");
                    if (newValue.indexOf(",") === -1) {
                        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                        if (color) return color;
                        else return newValue;
                    } else {
                        var val = value.split(",");
                        if (val.length == 2) {
                            var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                            rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                            return rgbaColor;
                        } else {
                            return newValue;
                        }
                    }
                });
            }
        }
    }

    let classChart, genderChart, staffChart;

    function initPortalCharts() {
        // 1. Class Chart
        var classColors = getChartColorsArray("students_class_chart");
        var classOptions = {
            series: [{ name: 'Students', data: [] }],
            chart: { height: 350, type: 'bar', toolbar: { show: false } },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%' } },
            dataLabels: { enabled: false },
            colors: classColors,
            xaxis: { categories: [] },
            grid: { borderColor: '#f1f1f1' }
        };
        classChart = new ApexCharts(document.querySelector("#students_class_chart"), classOptions);
        classChart.render();

        // 2. Gender Chart
        var genderColors = getChartColorsArray("gender_dist_chart");
        var genderOptions = {
            series: [],
            labels: [],
            chart: { height: 350, type: 'donut' },
            colors: genderColors,
            legend: { position: 'bottom' },
            dataLabels: { 
                enabled: true,
                dropShadow: { enabled: true, top: 1, left: 1, blur: 1, opacity: 0.45 }
            }
        };
        genderChart = new ApexCharts(document.querySelector("#gender_dist_chart"), genderOptions);
        genderChart.render();

        // 3. Staff Chart
        var staffColors = getChartColorsArray("staff_breakdown_chart");
        var staffOptions = {
             series: [],
            labels: [],
            chart: { height: 350, type: 'donut' },
            colors: staffColors,
            legend: { position: 'bottom' },
             dataLabels: { 
                enabled: true,
                dropShadow: { enabled: true, top: 1, left: 1, blur: 1, opacity: 0.45 }
             }
        };
        staffChart = new ApexCharts(document.querySelector("#staff_breakdown_chart"), staffOptions);
        staffChart.render();
    }

    initPortalCharts();
    $wire.dispatchCharts();

    $wire.on('portal-dashboard-charts-updated', (data) => {
        var payload = data[0];

        // Class
        if(payload.studentsByClass) {
            classChart.updateOptions({
                xaxis: { categories: payload.studentsByClass.categories },
                series: [{ data: payload.studentsByClass.data }]
            });
        }

        // Gender
        if(payload.genderDist) {
            genderChart.updateOptions({
                labels: payload.genderDist.labels,
                series: payload.genderDist.data
            });
        }

        // Staff
        if(payload.staffBreakdown) {
            staffChart.updateOptions({
                labels: payload.staffBreakdown.labels,
                series: payload.staffBreakdown.data
            });
        }
    });

</script>
@endscript
