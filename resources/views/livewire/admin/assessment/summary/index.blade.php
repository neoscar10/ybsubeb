<div>
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Needs Assessment Summary</h4>
                    <p class="text-muted mb-0">Aggregate view of submitted needs and estimated costs.</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select" wire:model.live="filterWindow" style="width: 250px;">
                            <option value="">Select Window...</option>
                            @foreach($windows as $win)
                                <option value="{{ $win->id }}">{{ $win->title }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-soft-primary" wire:click="exportCsv">
                            <i class="ri-download-cloud-2-line align-bottom me-1"></i> Export CSV
                        </button>
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
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Schools Submitted</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($stats['schools_submitted']) }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Items</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($stats['total_items']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-shopping-cart-2-line text-info"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Budget (Approved)</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">₦{{ number_format($stats['total_cost'] / 1000000, 2) }}M</h4>
                            <a href="#" class="text-decoration-underline text-muted">View details</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-money-dollar-circle-line text-success"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">High Priority</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($stats['high_priority']) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                <i class="ri-alarm-warning-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Category Table -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Needs by Category</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Approved</th>
                                    <th scope="col">Est. Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryStats as $cat)
                                <tr>
                                    <td>{{ ucfirst($cat->category) }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $cat->count }}</span>
                                    </td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cat->count > 0 ? ($cat->approved_count / $cat->count) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="fs-11 text-muted">{{ $cat->approved_count }} / {{ $cat->count }}</span>
                                    </td>
                                    <td>₦{{ number_format($cat->cost) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">No data available</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- LGA Table -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Breakdown by LGA</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light sticky-top">
                                <tr>
                                    <th scope="col">LGA</th>
                                    <th scope="col">Schools</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Total Ask</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lgaStats as $lga)
                                <tr>
                                    <td>{{ $lga->lga }}</td>
                                    <td>{{ $lga->school_count }}</td>
                                    <td>{{ $lga->item_count }}</td>
                                    <td class="text-end">₦{{ number_format($lga->total_cost) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">No data available</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
