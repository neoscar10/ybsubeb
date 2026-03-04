<div>
    <!-- KPI Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Windows</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $totalWindows }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-calendar-event-line text-primary"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Current Status</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                @if($openWindow)
                                    <span class="text-success">Active</span>
                                @else
                                    <span class="text-muted">None Open</span>
                                @endif
                            </h4>
                            <p class="mb-0 text-muted text-truncate">{{ $openWindow->title ?? '-' }}</p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-pulse-line text-info"></i>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">Submissions (Current)</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $receivedSubmissions }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-file-list-3-line text-warning"></i>
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
                <h5 class="card-title mb-0 flex-grow-1">Assessment Windows</h5>
                <div class="flex-shrink-0">
                    <button wire:click="create" class="btn btn-success add-btn">
                        <i class="ri-add-line align-bottom me-1"></i> Create Window
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="search-box">
                <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search windows...">
                <i class="ri-search-line search-icon"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card mb-4">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Timeline</th>
                            <th scope="col">Scope</th>
                            <th scope="col">Status</th>
                            <th scope="col">Submissions</th>
                            <th scope="col">Created By</th>
                            <th scope="col" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($windows as $window)
                        <tr>
                            <td>
                                <h5 class="fs-14 mb-1">{{ $window->title }}</h5>
                                <p class="text-muted mb-0">{{ Str::limit($window->note, 30) }}</p>
                            </td>
                            <td>
                                <div class="fs-12">
                                    <span class="text-muted">Opens:</span> {{ $window->opens_at->format('d M Y') }}<br>
                                    <span class="text-muted">Closes:</span> {{ $window->closes_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>{{ ucfirst($window->scope ?? 'Statewide') }}</td>
                            <td>
                                @if($window->status === 'open')
                                    <span class="badge bg-success-subtle text-success text-uppercase">Open</span>
                                @elseif($window->status === 'draft')
                                    <span class="badge bg-secondary-subtle text-secondary text-uppercase">Draft</span>
                                @elseif($window->status === 'closed')
                                    <span class="badge bg-danger-subtle text-danger text-uppercase">Closed</span>
                                @else
                                    <span class="badge bg-light text-muted text-uppercase">{{ $window->status }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">{{ $window->assessments_count }}</span>
                            </td>
                             <td>
                                {{ $window->creator->name ?? 'Unknown' }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($window->status === 'draft')
                                            <li><a class="dropdown-item" href="javascript:void(0);" wire:click="edit({{ $window->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" wire:click="toggleStatus({{ $window->id }}, 'open')"><i class="ri-play-circle-line align-bottom me-2 text-success"></i> Open Window</a></li>
                                             <li><a class="dropdown-item" href="javascript:void(0);" wire:click="delete({{ $window->id }})"><i class="ri-delete-bin-line align-bottom me-2 text-danger"></i> Delete</a></li>
                                        @elseif($window->status === 'open')
                                            <li><a class="dropdown-item" href="javascript:void(0);" wire:click="edit({{ $window->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit Props</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" wire:click="toggleStatus({{ $window->id }}, 'closed')"><i class="ri-stop-circle-line align-bottom me-2 text-danger"></i> Close Window</a></li>
                                        @elseif($window->status === 'closed')
                                            <li><a class="dropdown-item" href="javascript:void(0);" wire:click="toggleStatus({{ $window->id }}, 'archived')"><i class="ri-archive-line align-bottom me-2 text-muted"></i> Archive</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="noresult">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">No Assessment Windows Found</h5>
                                        <p class="text-muted mb-0">Create a new window to start collecting needs.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-2">
                {{ $windows->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editMode ? 'Edit Window' : 'Create Assessment Window' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="title" placeholder="e.g. 2026 Q1 Infrastructure Assessment">
                            @error('title') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Opens At <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" wire:model="opens_at">
                                @error('opens_at') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Closes At <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" wire:model="closes_at">
                                @error('closes_at') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Scope (Optional)</label>
                            <input type="text" class="form-control" wire:model="scope" placeholder="e.g. Statewide, or Specific LGAs">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" wire:model="note" rows="3" placeholder="Internal notes..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" wire:click="$set('showModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Create' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
