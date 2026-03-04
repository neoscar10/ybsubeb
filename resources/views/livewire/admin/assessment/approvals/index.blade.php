<div>
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Needs Assessment Approvals</h5>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row g-3">
                <div class="col-xxl-3 col-sm-6">
                    <select class="form-select" wire:model.live="filterWindow">
                        <option value="">All Windows</option>
                        @foreach($windows as $win)
                            <option value="{{ $win->id }}">{{ $win->title }} ({{ $win->status }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-2 col-sm-6">
                    <select class="form-select" wire:model.live="filterLga">
                        <option value="">All LGAs</option>
                        @foreach($lgas as $lga)
                            <option value="{{ $lga }}">{{ $lga }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-xxl-2 col-sm-6">
                    <select class="form-select" wire:model.live="filterStatus">
                        <option value="">All Types</option>
                        <option value="submitted">Submitted</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-xxl-5 col-sm-12">
                     <div class="search-box">
                        <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search school...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card mb-4">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">School</th>
                            <th scope="col">Window</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Items (App/Rej/Pend)</th>
                            <th scope="col">Principal</th>
                            <th scope="col" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                        <tr>
                            <td>
                                <h5 class="fs-14 mb-1">{{ $assessment->school->name }}</h5>
                                <p class="text-muted mb-0">{{ $assessment->school->lga }} ({{ $assessment->school->code }})</p>
                            </td>
                            <td>{{ $assessment->window->title ?? '-' }}</td>
                            <td>
                                @if($assessment->submitted_at)
                                    <div>{{ $assessment->submitted_at->format('d M Y') }}</div>
                                    <div class="text-muted fs-11">{{ $assessment->submitted_at->format('h:i A') }}</div>
                                @else
                                    -
                                @endif
                            </td>
                             <td>
                                @php
                                    $statusClass = match($assessment->status) {
                                        'approved' => 'bg-success-subtle text-success',
                                        'rejected' => 'bg-danger-subtle text-danger',
                                        'under_review' => 'bg-warning-subtle text-warning',
                                        'submitted' => 'bg-info-subtle text-info',
                                        default => 'bg-light text-muted'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-uppercase">{{ str_replace('_', ' ', $assessment->status) }}</span>
                            </td>
                            <td>
                                <div>Total: {{ $assessment->total_items }}</div>
                                <div class="fs-11">
                                    <span class="text-success">{{ $assessment->approved_items }}</span> / 
                                    <span class="text-danger">{{ $assessment->rejected_items }}</span> / 
                                    <span class="text-warning">{{ $assessment->pending_items }}</span>
                                </div>
                            </td>
                            <td>{{ $assessment->submitter->name ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-soft-primary" wire:click="openReview({{ $assessment->id }})">
                                    <i class="ri-eye-line align-middle me-1"></i> Review
                                </button>
                            </td>
                        </tr>
                        @empty
                         <tr>
                            <td colspan="7" class="text-center">
                                <div class="noresult">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">No Submissions Found</h5>
                                        <p class="text-muted mb-0">Try adjusting your filters.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="d-flex justify-content-end mt-2">
                {{ $assessments->links() }}
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    @if($showReviewModal && $viewAssessment)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Assessment: <span class="text-primary">{{ $viewAssessment->school->name }}</span></h5>
                    <button type="button" class="btn-close" wire:click="$set('showReviewModal', false)"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8" style="border-right: 1px solid #e9ebec;">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fs-14 text-muted mb-0">Needs Items</h6>
                                    <div>
                                        <button class="btn btn-sm btn-soft-success me-1" wire:click="bulkDecide('approved')">Approve Pending</button>
                                        <button class="btn btn-sm btn-soft-danger" wire:click="bulkDecide('rejected')">Reject Pending</button>
                                    </div>
                                </div>

                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty/Cost</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th style="width: 120px;">Decision</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($viewAssessment->items as $item)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-1">{{ $item->title }}</h6>
                                                    <p class="text-muted fs-12 mb-1">{{ $item->category }}</p>
                                                    @if($item->description)
                                                        <p class="text-muted fs-11 fst-italic mb-0">{{ Str::limit($item->description, 50) }}</p>
                                                    @endif
                                                    
                                                    <!-- Attachments Display -->
                                                    @if($item->attachments->count() > 0)
                                                        <div class="mt-2 text-wrap">
                                                            @foreach($item->attachments as $att)
                                                                <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="badge bg-light text-primary border border-primary-subtle me-1 mb-1 text-decoration-none">
                                                                    <i class="ri-attachment-2"></i> {{ Str::limit($att->original_name, 15) }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $item->quantity }} {{ $item->unit }}</div>
                                                    @if($item->estimated_cost)
                                                        <div class="text-muted fs-11">₦{{ number_format($item->estimated_cost) }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     @php
                                                        $prioClass = match($item->priority) {
                                                            'critical' => 'badge bg-danger',
                                                            'high' => 'badge bg-warning',
                                                            'medium' => 'badge bg-info',
                                                            'low' => 'badge bg-success',
                                                            default => 'badge bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="{{ $prioClass }}">{{ ucfirst($item->priority) }}</span>
                                                </td>
                                                <td>
                                                     @if($item->status === 'approved')
                                                        <i class="ri-checkbox-circle-fill text-success fs-18"></i>
                                                    @elseif($item->status === 'rejected')
                                                        <i class="ri-close-circle-fill text-danger fs-18"></i>
                                                    @else
                                                        <i class="ri-time-fill text-warning fs-18"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-success {{ $item->status === 'approved' ? 'active' : '' }}" wire:click="decideItem({{ $item->id }}, 'approved')"><i class="ri-check-line"></i></button>
                                                        <button type="button" class="btn btn-outline-danger {{ $item->status === 'rejected' ? 'active' : '' }}" wire:click="decideItem({{ $item->id }}, 'rejected')"><i class="ri-close-line"></i></button>
                                                    </div>
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
                                <h6 class="fs-14 fw-bold mb-3">Assessment Summary</h6>
                                
                                <div class="card shadow-none border">
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Submitted By:</strong> {{ $viewAssessment->submitter->name ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Date:</strong> {{ $viewAssessment->submitted_at ? $viewAssessment->submitted_at->format('d M, Y h:i A') : '-' }}</p>
                                        
                                        @if($viewAssessment->principal_comment)
                                            <div class="mt-3 p-2 bg-white border rounded">
                                                <p class="fs-11 text-muted mb-1 text-uppercase">Principal's Note:</p>
                                                <p class="fs-13 mb-0">{{ $viewAssessment->principal_comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <h6 class="fs-14 fw-bold mt-4 mb-2">Chairman's Verdict</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Internal Comment</label>
                                    <textarea class="form-control" wire:model="adminComment" rows="4" placeholder="Add notes about this decision..."></textarea>
                                    <div class="text-end mt-2">
                                        <button class="btn btn-sm btn-ghost-primary" wire:click="saveComment">Save Comment</button>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" wire:click="finalizeAssessment('approved')"><i class="ri-check-double-line align-bottom me-1"></i> Approve Assessment</button>
                                    <button class="btn btn-danger" wire:click="finalizeAssessment('rejected')"><i class="ri-close-circle-line align-bottom me-1"></i> Reject Assessment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
