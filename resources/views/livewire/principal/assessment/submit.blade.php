<div class="page-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Needs Assessment Submission</h4>
                        <p class="text-muted mb-0">
                            @if($window)
                                Window: <span class="fw-semibold">{{ $window->title }}</span> 
                                (Closes: {{ $window->closes_at->format('d M, Y') }})
                            @else
                                Needs Assessment Portal
                            @endif
                        </p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        @if($assessment)
                            <span class="badge fs-12 me-2 p-2 
                                @if($assessment->status == 'draft') bg-warning 
                                @elseif($assessment->status == 'submitted') bg-primary 
                                @elseif($assessment->status == 'approved') bg-success 
                                @elseif($assessment->status == 'rejected') bg-danger 
                                @else bg-secondary @endif">
                                {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                            </span>
                            
                            @if($assessment->status === 'draft')
                                <button type="button" class="btn btn-soft-success waves-effect waves-light me-1" wire:click="openItemModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Need Item
                                </button>
                                <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="confirmSubmit">
                                    Review & Submit
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts / Empty States -->
        @if($noSchoolLinked)
            <div class="alert alert-danger" role="alert">
                <strong>Account Issue:</strong> Your account is not linked to any school. Please contact the ICT support team immediately.
            </div>
        @elseif($noWindowOpen)
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mt-4">
                        <h4 class="mb-2">No Active Assessment Window</h4>
                        <p class="text-muted mb-4">There is currently no open window for submitting needs assessments. Please check back later.</p>
                        <i class="ri-calendar-event-line display-4 text-muted"></i>
                    </div>
                </div>
            </div>
        @elseif($windowClosed)
            <div class="alert alert-danger" role="alert">
                <strong>Submission Closed:</strong> The submission window for "{{ $window->title }}" has closed. You can no longer edit your assessment.
            </div>
        @endif

        @if($assessment)
            <!-- Admin Feedback -->
            @if($assessment->admin_comment)
                <div class="alert alert-info" role="alert">
                    <strong>Admin Feedback:</strong> {{ $assessment->admin_comment }}
                </div>
            @endif

            <!-- Main Content: Items List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Needs Items</h4>
                            <div class="flex-shrink-0">
                                <span class="text-muted">Total Items: {{ $items->count() }}</span>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 40px;">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Priority</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Est. Cost (₦)</th>
                                            <th scope="col">Attachments</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <h5 class="fs-13 mb-1"><a href="#" class="text-link" wire:click.prevent="openItemModal({{ $item->id }})">{{ $item->title }}</a></h5>
                                                    <p class="text-muted mb-0 fs-11">{{ Str::limit($item->description, 30) }}</p>
                                                </td>
                                                <td><span class="badge bg-soft-info text-info">{{ ucfirst($item->category) }}</span></td>
                                                <td>
                                                    @if($item->priority == 'critical') <span class="badge bg-danger">Critical</span>
                                                    @elseif($item->priority == 'high') <span class="badge bg-warning text-dark">High</span>
                                                    @elseif($item->priority == 'medium') <span class="badge bg-secondary">Medium</span>
                                                    @else <span class="badge bg-success">Low</span> @endif
                                                </td>
                                                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                                <td>{{ $item->estimated_cost ? number_format($item->estimated_cost, 2) : '-' }}</td>
                                                <td>
                                                    @if($item->attachments->count() > 0)
                                                        <a href="#" wire:click.prevent="openAttachmentModal({{ $item->id }})">{{ $item->attachments->count() }} Files</a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status == 'approved') <span class="badge bg-success">Approved</span>
                                                    @elseif($item->status == 'rejected') <span class="badge bg-danger">Rejected</span>
                                                    @else <span class="badge bg-soft-warning text-warning">Pending</span> @endif
                                                </td>
                                                <td>
                                                    @if($assessment->status == 'draft')
                                                        <div class="dropdown">
                                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-more-fill align-middle"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="javascript:void(0);" wire:click="openItemModal({{ $item->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);" wire:click="openAttachmentModal({{ $item->id }})"><i class="ri-attachment-2 align-bottom me-2 text-muted"></i> Attachments</a></li>
                                                                <li><a class="dropdown-item remove-item-btn" href="javascript:void(0);" wire:click="deleteItem({{ $item->id }})"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <button class="btn btn-sm btn-light" wire:click="openItemModal({{ $item->id }})">View</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="ri-file-list-3-line display-5 overflow-hidden"></i>
                                                        <p class="mt-2 text-muted">No items added yet.</p>
                                                        @if($assessment->status == 'draft')
                                                        <button class="btn btn-sm btn-primary" wire:click="openItemModal">Add First Item</button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div>
            </div>
        @endif

        <!-- Add/Edit Item Modal -->
        <div wire:ignore.self class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $itemId ? 'Edit Item' : 'Add New Need Item' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="saveItem">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" wire:model="category">
                                        <option value="infrastructure">Infrastructure</option>
                                        <option value="learning_materials">Learning Materials</option>
                                        <option value="staffing">Staffing</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="sanitation">Sanitation (WASH)</option>
                                        <option value="security">Security</option>
                                        <option value="ict">ICT</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('category') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-select" wire:model="priority">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                    @error('priority') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="title" placeholder="e.g. Renovation of Block A">
                                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Description (Optional)</label>
                                    <textarea class="form-control" wire:model="description" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model="quantity" min="1">
                                    @error('quantity') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Unit (e.g. blocks, pcs)</label>
                                    <input type="text" class="form-control" wire:model="unit">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Est. Cost (₦)</label>
                                    <input type="number" class="form-control" wire:model="estimated_cost" step="0.01">
                                    @error('estimated_cost') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Justification / Note</label>
                                    <textarea class="form-control" wire:model="justification" rows="2" placeholder="Why is this needed?"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Attachments (Optional)</label>
                                    <input type="file" class="form-control" wire:model="newAttachments" multiple accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="form-text">Max 10MB per file. Supported: JPG, PNG, PDF.</div>
                                    @error('newAttachments.*') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    
                                    <!-- Loading Animation -->
                                    <div wire:loading wire:target="newAttachments" class="mt-3">
                                        <div class="d-flex align-items-center text-primary fs-13">
                                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                            <span>Preparing preview...</span>
                                        </div>
                                    </div>

                                    <!-- Preview Grid -->
                                    <div wire:loading.remove wire:target="newAttachments">
                                        @if(!empty($newAttachments))
                                            <div class="mt-3 row g-2">
                                                @foreach($newAttachments as $index => $file)
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="border rounded p-2 d-flex align-items-center bg-light">
                                                            <div class="flex-shrink-0 me-2">
                                                                @if(Str::startsWith($file->getMimeType(), 'image/') && in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png']))
                                                                    <img src="{{ $file->temporaryUrl() }}" alt="preview" class="rounded object-fit-cover border" style="width: 40px; height: 40px;">
                                                                @elseif(strtolower($file->getClientOriginalExtension()) === 'pdf')
                                                                    <div class="rounded bg-white d-flex align-items-center justify-content-center text-danger border" style="width: 40px; height: 40px;">
                                                                        <i class="ri-file-pdf-line fs-20"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="rounded bg-white d-flex align-items-center justify-content-center text-primary border" style="width: 40px; height: 40px;">
                                                                        <i class="ri-file-line fs-20"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <h6 class="fs-12 mb-0 text-truncate" title="{{ $file->getClientOriginalName() }}">{{ $file->getClientOriginalName() }}</h6>
                                                                <span class="text-muted fs-11">{{ round($file->getSize() / 1024, 1) }} KB</span>
                                                            </div>
                                                            <div class="flex-shrink-0 ms-2">
                                                                <button type="button" class="btn btn-sm btn-ghost-danger px-2 text-danger" wire:click.prevent="removeNewAttachment({{ $index }})" title="Remove">
                                                                    <i class="ri-close-line fs-16"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            @if($assessment && $assessment->status === 'draft')
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveItem">Save Item</span>
                                <span wire:loading wire:target="saveItem"><i class="ri-loader-4-line ri-spin"></i> Saving...</span>
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Attachment Modal (View Only) -->
        <div wire:ignore.self class="modal fade" id="attachmentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Attachments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         @if($attachmentItem)
                            <h6 class="mb-3 text-muted">Item: {{ $attachmentItem->title }}</h6>
                            
                            <!-- List Existing -->
                            <div class="list-group mb-3">
                                @forelse($attachmentItem->attachments as $att)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    @if(Str::startsWith($att->mime, 'image/'))
                                                        <img src="{{ Storage::url($att->file_path) }}" alt="{{ $att->original_name }}" class="rounded avatar-sm object-fit-cover border">
                                                    @elseif($att->mime === 'application/pdf')
                                                        <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center text-danger">
                                                            <i class="ri-file-pdf-line fs-24"></i>
                                                        </div>
                                                    @else
                                                        <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center text-primary">
                                                            <i class="ri-file-line fs-24"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fs-13 mb-1"><a href="{{ Storage::url($att->file_path) }}" target="_blank" class="text-reset">{{ $att->original_name }}</a></h6>
                                                    <span class="text-muted fs-12">{{ round($att->size / 1024, 1) }} KB</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <!-- View/Download -->
                                                <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="btn btn-sm btn-ghost-primary" title="View">
                                                    <i class="ri-external-link-line fs-16"></i>
                                                </a>
                                                
                                                @if($assessment->status == 'draft')
                                                    <button class="btn btn-sm btn-ghost-danger" wire:click="removeAttachment({{ $att->id }})" title="Remove">
                                                        <i class="ri-delete-bin-line fs-16"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted p-2">No files attached yet.</div>
                                @endforelse
                            </div>
                         @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@script
<script>
    window.addEventListener('open-item-modal', event => {
        var myModal = new bootstrap.Modal(document.getElementById('itemModal'));
        myModal.show();
    });

    window.addEventListener('close-item-modal', event => {
        var el = document.getElementById('itemModal');
        var modal = bootstrap.Modal.getInstance(el);
        if (modal) modal.hide();
    });

     window.addEventListener('open-attachment-modal', event => {
        var myModal = new bootstrap.Modal(document.getElementById('attachmentModal'));
        myModal.show();
    });

    window.addEventListener('close-attachment-modal', event => {
        var el = document.getElementById('attachmentModal');
        var modal = bootstrap.Modal.getInstance(el);
        if (modal) modal.hide();
    });

    // Confirm Submit
    window.addEventListener('confirm-submit-modal', event => {
        Swal.fire({
            title: 'Submit Assessment?',
            text: "You will not be able to make changes after submitting.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.submit();
            }
        })
    });
</script>
@endscript
