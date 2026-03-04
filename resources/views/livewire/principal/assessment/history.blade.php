<div class="page-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Needs Assessment History</h4>
                        <p class="text-muted mb-0">Past submissions for your school</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                         @if(!$noSchool)
                        <div class="d-flex align-items-center gap-2">
                             <span class="badge bg-soft-info text-info fs-12">Total Submissions: {{ $assessments->total() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($noSchool)
            <div class="alert alert-danger" role="alert">
                <strong>Account Issue:</strong> Your account is not linked to any school.
            </div>
        @else
            <!-- Filters -->
            <div class="card">
                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <div class="row g-3">
                        <div class="col-xxl-3 col-sm-6">
                            <select class="form-select" wire:model.live="filterWindow">
                                <option value="">All Windows</option>
                                @foreach($windows as $win)
                                    <option value="{{ $win->id }}">{{ $win->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="">All Statuses</option>
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <button type="button" class="btn btn-light w-100" wire:click="resetFilters">
                                <i class="ri-refresh-line align-bottom me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Window</th>
                                    <th scope="col">Submitted Date</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Status Breakdown (App/Rej/Pend)</th>
                                    <th scope="col">Final Status</th>
                                    <th scope="col" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assessments as $assessment)
                                <tr>
                                    <td>{{ $assessment->window->title ?? '-' }}</td>
                                    <td>
                                        @if($assessment->submitted_at)
                                            <div>{{ $assessment->submitted_at->format('d M, Y') }}</div>
                                            <span class="text-muted fs-11">{{ $assessment->submitted_at->format('h:i A') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $assessment->items_count }}</td>
                                    <td>
                                        <span class="text-success fw-medium">{{ $assessment->approved_count }}</span> / 
                                        <span class="text-danger fw-medium">{{ $assessment->rejected_count }}</span> / 
                                        <span class="text-warning fw-medium">{{ $assessment->pending_count }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($assessment->status) {
                                                'approved' => 'bg-success-subtle text-success',
                                                'rejected' => 'bg-danger-subtle text-danger',
                                                'under_review' => 'bg-warning-subtle text-warning',
                                                'submitted' => 'bg-info-subtle text-info',
                                                'draft' => 'bg-secondary-subtle text-secondary',
                                                default => 'bg-light text-muted'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} text-uppercase">{{ str_replace('_', ' ', $assessment->status) }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary" wire:click="viewDetails({{ $assessment->id }})">
                                            <i class="ri-eye-line align-bottom me-1"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-file-search-line display-5 overflow-hidden"></i>
                                            <h5 class="mt-2">No Submissions Found</h5>
                                            <p class="text-muted mb-0">You have no past assessment submissions matching the filters.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $assessments->links() }}
                    </div>
                </div>
            </div>

            <!-- Details Modal -->
            <div wire:ignore.self class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assessment Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                             @if($viewAssessment)
                             <div class="row g-0">
                                <div class="col-lg-8 border-end">
                                    <div class="p-3">
                                        <h6 class="fs-14 text-muted mb-3">Submitted Items</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>Item Details</th>
                                                        <th>Qty/Cost</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        <th>Attachments</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($viewAssessment->items as $item)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-1">{{ $item->title }}</h6>
                                                            <span class="badge bg-light text-body border">{{ ucfirst($item->category) }}</span>
                                                            @if($item->description)
                                                                <p class="text-muted fs-11 fst-italic mt-1 mb-0">{{ Str::limit($item->description, 60) }}</p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ $item->quantity }} {{ $item->unit }}</div>
                                                            @if($item->estimated_cost)
                                                                <div class="text-muted fs-11">₦{{ number_format($item->estimated_cost) }}</div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->priority == 'critical') <span class="badge bg-danger">Critical</span>
                                                            @elseif($item->priority == 'high') <span class="badge bg-warning">High</span>
                                                            @elseif($item->priority == 'medium') <span class="badge bg-info">Medium</span>
                                                            @else <span class="badge bg-success">Low</span> @endif
                                                        </td>
                                                        <td>
                                                             @if($item->status == 'approved') <span class="badge bg-success">Approved</span>
                                                            @elseif($item->status == 'rejected') <span class="badge bg-danger">Rejected</span>
                                                            @else <span class="badge bg-soft-warning text-warning">Pending</span> @endif
                                                            
                                                            @if($item->decision_note)
                                                                <i class="ri-information-line text-muted ms-1" title="{{ $item->decision_note }}"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->attachments->count() > 0)
                                                                @foreach($item->attachments as $att)
                                                                    <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="badge bg-light text-primary border border-primary-subtle me-1 mb-1 text-decoration-none">
                                                                        <i class="ri-attachment-2"></i> {{ Str::limit($att->original_name, 10) }}
                                                                    </a>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted fs-11">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 bg-light-subtle">
                                    <div class="p-3">
                                        <h6 class="fs-14 fw-bold mb-3">Submission Summary</h6>
                                        <div class="card shadow-none border">
                                            <div class="card-body">
                                                <p class="mb-2"><span class="text-muted">Window:</span> <br><span class="fw-medium">{{ $viewAssessment->window->title ?? 'N/A' }}</span></p>
                                                <p class="mb-2"><span class="text-muted">Submitted:</span> <br><span class="fw-medium">{{ $viewAssessment->submitted_at ? $viewAssessment->submitted_at->format('d M, Y h:i A') : 'Not Submitted' }}</span></p>
                                                <p class="mb-0"><span class="text-muted">Status:</span> <br>
                                                    <span class="badge {{ $statusClass }} fs-12 mt-1">{{ ucfirst(str_replace('_', ' ', $viewAssessment->status)) }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        @if($viewAssessment->admin_comment)
                                            <div class="alert alert-info mb-0">
                                                <h6 class="alert-heading fs-13 fw-bold mb-1">Admin Feedback:</h6>
                                                <p class="mb-0 fs-12">{{ $viewAssessment->admin_comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                             </div>
                             @endif
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>
</div>

@script
<script>
    window.addEventListener('open-details-modal', event => {
        var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
        myModal.show();
    });
</script>
@endscript
