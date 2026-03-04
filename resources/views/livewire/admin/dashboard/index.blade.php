<div class="page-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Admin Dashboard</h4>
                        <p class="text-muted mb-0">Overview of schools, enrollment, and needs assessment.</p>
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
                                <label class="form-label">Assessment Window</label>
                                <select class="form-select" wire:model.live="filterWindow">
                                    <option value="">All Windows</option>
                                    @foreach($windows as $win)
                                        <option value="{{ $win->id }}">{{ $win->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">LGA</label>
                                <select class="form-select" wire:model.live="filterLga">
                                    <option value="">All LGAs</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga }}">{{ $lga }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-soft-primary w-100" wire:click="exportNeedsCsv">
                                    <i class="ri-download-cloud-line me-1"></i> Export Needs Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Schools</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalSchools) }}</h4>
                                <a href="{{ route('admin.schools.index') }}" class="text-decoration-underline text-muted">View all schools</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="ri-building-line text-info"></i>
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
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Students</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalStudents) }}</h4>
                                <a href="#" class="text-decoration-underline text-muted">View enrollment</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                    <i class="ri-group-line text-warning"></i>
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
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Needs Submitted</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($submittedCount) }}</h4>
                                <a href="{{ route('admin.assessment.summary') }}" class="text-decoration-underline text-muted">View summary</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-secondary rounded fs-3">
                                    <i class="ri-file-list-3-line text-secondary"></i>
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
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Needs Cost</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">₦{{ number_format($totalCost) }}</h4>
                                <span class="text-muted">Estimated</span>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="ri-money-naira-circle-line text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Needs by Category</h4>
                    </div>
                    <div class="card-body">
                        <div id="needs_category_chart" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Enrollment by LGA</h4>
                    </div>
                    <div class="card-body">
                        <div id="enrollment_lga_chart" data-colors='["--vz-info"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top 5 Schools by Needs Cost</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">School</th>
                                        <th scope="col">Items</th>
                                        <th scope="col" class="text-end">Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hotList as $hot)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">{{ $hot->name }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $hot->item_count }}</td>
                                        <td class="text-end">₦{{ number_format($hot->total_cost) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
<script src="{{ asset('themes/velzon/libs/apexcharts/apexcharts.min.js') }}"></script>
@endassets

@script
<script>
    // Helper to get colors from data attribute
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

    // Init Logic
    let needsChart, enrollmentChart;

    function initCharts() {
        // Needs Category Chart
        var needsColors = getChartColorsArray("needs_category_chart");
        var needsOptions = {
            series: [{ data: [] }], // Initial empty
            chart: { height: 350, type: 'bar', toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true, distributed: true } },
            dataLabels: { enabled: false },
            colors: needsColors,
            xaxis: { categories: [] },
            grid: { borderColor: '#f1f1f1' }
        };
        needsChart = new ApexCharts(document.querySelector("#needs_category_chart"), needsOptions);
        needsChart.render();

        // Enrollment Chart
        var enrollColors = getChartColorsArray("enrollment_lga_chart");
        var enrollOptions = {
            series: [{ name: 'Students', data: [] }],
            chart: { height: 350, type: 'bar', toolbar: { show: false } },
            colors: enrollColors,
            xaxis: { categories: [] },
            grid: { borderColor: '#f1f1f1' }
        };
        enrollmentChart = new ApexCharts(document.querySelector("#enrollment_lga_chart"), enrollOptions);
        enrollmentChart.render();
    }

    initCharts();
    $wire.dispatchCharts(); // Trigger initial data load

    // Listen for updates
    $wire.on('update-dashboard-charts', (data) => {
        // Data format: { needsCats: { categories: [], data: [] }, enrollmentLga: ... }
        var payload = data[0]; 

        if (payload.needsCats) {
            needsChart.updateOptions({
                xaxis: { categories: payload.needsCats.categories },
                series: [{ data: payload.needsCats.data }]
            });
        }

        if (payload.enrollmentLga) {
            enrollmentChart.updateOptions({
                xaxis: { categories: payload.enrollmentLga.categories },
                series: [{ data: payload.enrollmentLga.data }]
            });
        }
    });
</script>
@endscript
