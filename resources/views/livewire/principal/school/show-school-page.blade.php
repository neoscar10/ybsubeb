<div>
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">My School</h4>
                            <div class="text-muted mb-0">
                                Principal Portal / My School
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($noSchoolAssigned)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bg-soft-warning border-warning border shadow-none">
                            <div class="card-body text-center p-5">
                                <div class="mt-4">
                                    <h4 class="mb-2 text-warning">Setup Incomplete</h4>
                                    <p class="text-muted mb-4">Your administrative account is not currently assigned to any specific school record. Please contact the Board or your ICT team to link your profile.</p>
                                    <i class="ri-building-line display-4 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Main Header / Profile Identity Section -->
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="mx-auto avatar-md mb-3">
                                    <div class="avatar-title bg-light text-primary rounded-circle fs-36 border border-primary border-opacity-25">
                                        <i class="ri-government-line"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="card-title mb-0">{{ $school->name ?? '—' }}</h5>
                                    <button type="button" class="btn btn-sm btn-soft-primary" wire:click="openBasicModal">
                                        <i class="ri-edit-line me-1"></i> Edit
                                    </button>
                                </div>
                                <p class="text-muted mb-2 fs-13">School Code: <span class="fw-semibold text-dark">{{ $school->code ?? 'N/A' }}</span></p>
                                
                                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                                    <span class="badge bg-soft-primary text-primary">{{ ucfirst(str_replace('_', ' ', $school->school_type ?? 'Unknown')) }}</span>
                                    <span class="badge bg-soft-info text-info">{{ ucfirst($school->ownership ?? 'Public') }}</span>
                                    @if($school->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>

                                <div class="border-top border-top-dashed pt-4 text-start">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted fs-13"><i class="ri-phone-line me-2 text-primary"></i>Principal Phone</span>
                                        <span class="fs-13 fw-medium">{{ $user->phone ?? '—' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted fs-13"><i class="ri-mail-line me-2 text-primary"></i>Principal Email</span>
                                        <span class="fs-13 fw-medium">{{ $user->email ?? '—' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted fs-13"><i class="ri-map-pin-line me-2 text-primary"></i>Location</span>
                                        <span class="fs-13 fw-medium text-end">{{ $school->lga ?? '—' }}<br><small class="text-muted">{{ $school->town ?? $school->community ?? '' }}</small></span>
                                    </div>
                                </div>

                                <div class="mt-4 text-muted fs-12 text-center">
                                    Last Updated: {{ $school->updated_at ? $school->updated_at->format('d M, Y h:i A') : 'Never' }}
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <!-- KPI Row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-animate text-center">
                                    <div class="card-body">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div class="avatar-title bg-soft-success text-success rounded-circle fs-20">
                                                <i class="ri-group-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted text-uppercase fs-12">Total Students</h5>
                                        <h3 class="mb-2"><span class="counter-value">{{ number_format($school->total_students) }}</span></h3>
                                        <p class="text-muted mb-0 fs-13">
                                            <span class="text-info me-2"><i class="ri-men-line align-middle"></i> {{ number_format($school->male_students) }}</span>
                                            <span class="text-danger"><i class="ri-women-line align-middle"></i> {{ number_format($school->female_students) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-animate text-center">
                                    <div class="card-body">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-20">
                                                <i class="ri-presentation-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted text-uppercase fs-12">Total Staff</h5>
                                        <h3 class="mb-2"><span class="counter-value">{{ number_format($school->total_staff) }}</span></h3>
                                        <p class="text-muted mb-0 fs-13">
                                            <span class="text-info me-2"><i class="ri-men-line align-middle"></i> {{ number_format($maleStaff) }}</span>
                                            <span class="text-danger"><i class="ri-women-line align-middle"></i> {{ number_format($femaleStaff) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-animate text-center">
                                    <div class="card-body">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-20">
                                                <i class="ri-book-read-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted text-uppercase fs-12">Total Classes</h5>
                                        <h3 class="mb-2"><span class="counter-value">{{ number_format($school->total_classes ?? 0) }}</span></h3>
                                        <p class="text-muted mb-0 fs-13">&nbsp;</p> <!-- Spacing alignment -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Complex Details Row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex border-bottom-dashed">
                                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-booklet-line text-muted me-2 align-middle"></i>Overview & Facilities</h4>
                                        <div class="flex-shrink-0">
                                            <button type="button" class="btn btn-sm btn-soft-primary" wire:click="openInfraModal">
                                                <i class="ri-edit-line me-1"></i> Edit
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <p class="text-muted mb-1 fs-12 text-uppercase fw-semibold">Physical Location</p>
                                                <h6 class="fs-14">
                                                    {{ $school->address ?? 'Address not recorded.' }}
                                                </h6>
                                                <div class="mt-2 text-muted fs-13">
                                                    <strong>Ward:</strong> {{ $school->ward ?? '—' }}<br>
                                                    <strong>LGA:</strong> {{ $school->lga ?? '—' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-muted mb-1 fs-12 text-uppercase fw-semibold">Facilities Snapshot</p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge {{ $school->has_water ? 'bg-success' : 'bg-soft-danger text-danger' }} fs-12 px-3 py-2">
                                                        <i class="ri-drop-line align-middle me-1"></i> Water Source
                                                    </span>
                                                    <span class="badge {{ $school->has_toilets ? 'bg-success' : 'bg-soft-danger text-danger' }} fs-12 px-3 py-2">
                                                        <i class="ri-building-3-line align-middle me-1"></i> Toilets
                                                    </span>
                                                    <span class="badge {{ $school->has_electricity ? 'bg-success' : 'bg-soft-danger text-danger' }} fs-12 px-3 py-2">
                                                        <i class="ri-flashlight-line align-middle me-1"></i> Electricity
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex border-bottom-dashed">
                                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-inbox-archive-line text-muted me-2 align-middle"></i>Needs Assessment Summary</h4>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('principal.assessment.history') }}" class="btn btn-sm btn-soft-primary">View History</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-sm-4">
                                                <div class="p-3 border border-dashed rounded text-center">
                                                    <p class="text-uppercase fw-semibold text-muted fs-11 mb-2">Pending Review</p>
                                                    <h4 class="text-warning mb-0">{{ $pendingNeeds }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="p-3 border border-dashed rounded text-center">
                                                    <p class="text-uppercase fw-semibold text-muted fs-11 mb-2">Approved Needs</p>
                                                    <h4 class="text-success mb-0">{{ $approvedNeeds }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="p-3 border border-dashed rounded text-center">
                                                    <p class="text-uppercase fw-semibold text-muted fs-11 mb-2">Rejected Needs</p>
                                                    <h4 class="text-danger mb-0">{{ $rejectedNeeds }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fs-13 text-muted text-uppercase fw-semibold mb-0">Recent Requests</h6>
                                            @if($lastSubmissionDate)
                                                <span class="fs-12 text-muted">Last Submitted: {{ $lastSubmissionDate->format('d M, Y') }}</span>
                                            @endif
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-sm align-middle mb-0">
                                                <tbody>
                                                    @forelse($recentNeeds as $item)
                                                        <tr>
                                                            <td>
                                                                <h6 class="fs-13 mb-1 text-truncate" style="max-width: 250px;">{{ $item->title }}</h6>
                                                                <span class="text-muted fs-12">{{ ucfirst($item->category) }}</span>
                                                            </td>
                                                            <td class="text-end">
                                                                <span class="text-muted fs-12 me-3">{{ $item->created_at->format('d M, Y') }}</span>
                                                                @if($item->status == 'approved') <span class="badge bg-soft-success text-success">Approved</span>
                                                                @elseif($item->status == 'rejected') <span class="badge bg-soft-danger text-danger">Rejected</span>
                                                                @elseif($item->status == 'pending') <span class="badge bg-soft-warning text-warning">Pending</span>
                                                                @else <span class="badge bg-soft-secondary text-secondary">{{ ucfirst($item->status) }}</span> @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center text-muted p-4">
                                                                <i class="ri-file-search-line fs-24 mb-2 d-block"></i>
                                                                No needs history found for your school.
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

                    </div>
                </div>
            @endif

            <!-- Edit Basic Info Modal -->
            <div class="modal fade" id="basicInfoModal" tabindex="-1" aria-labelledby="basicInfoModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header p-3 bg-soft-primary text-primary">
                            <h5 class="modal-title" id="basicInfoModalLabel">Update School Basic Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetModals"></button>
                        </div>
                        <form wire:submit.prevent="saveBasic">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="basic.name" required>
                                    @error('basic.name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Type <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model="basic.school_type" required>
                                            <option value="">Select Type</option>
                                            <option value="primary">Primary</option>
                                            <option value="junior_secondary">Junior Secondary</option>
                                            <option value="basic">Basic (Primary + JSS)</option>
                                            <option value="special">Special Needs</option>
                                        </select>
                                        @error('basic.school_type') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ownership <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model="basic.ownership" required>
                                            <option value="">Select Ownership</option>
                                            <option value="public">Public</option>
                                            <option value="private">Private</option>
                                            <option value="community">Community</option>
                                        </select>
                                        @error('basic.ownership') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address / Physical Location</label>
                                    <textarea class="form-control" rows="2" wire:model="basic.address"></textarea>
                                    @error('basic.address') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">LGA <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" wire:model="basic.lga" required>
                                        @error('basic.lga') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ward</label>
                                        <input type="text" class="form-control" wire:model="basic.ward">
                                        @error('basic.ward') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Town / Community</label>
                                        <input type="text" class="form-control" wire:model="basic.community">
                                        @error('basic.community') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetModals">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="saveBasic">Save Changes</span>
                                    <span wire:loading wire:target="saveBasic"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Infrastructure Modal -->
            <div class="modal fade" id="infraModal" tabindex="-1" aria-labelledby="infraModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header p-3 bg-soft-secondary text-secondary">
                            <h5 class="modal-title" id="infraModalLabel">Update Infrastructure & Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetModals"></button>
                        </div>
                        <form wire:submit.prevent="saveInfra">
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Total Classes <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" wire:model="infra.total_classes" min="0" required>
                                        <small class="text-muted">Currently active classes</small>
                                        @error('infra.total_classes') <span class="text-danger d-block fs-12 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">School Status <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model="infra.status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('infra.status') <span class="text-danger d-block fs-12 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <h6 class="fs-13 text-muted text-uppercase fw-semibold mb-3">Facilities Snapshot</h6>
                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                    <input type="checkbox" class="form-check-input" id="has_water" wire:model="infra.has_water">
                                    <label class="form-check-label" for="has_water">Has Clean Water Source</label>
                                </div>
                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                    <input type="checkbox" class="form-check-input" id="has_toilets" wire:model="infra.has_toilets">
                                    <label class="form-check-label" for="has_toilets">Has Functional Toilets</label>
                                </div>
                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                    <input type="checkbox" class="form-check-input" id="has_electricity" wire:model="infra.has_electricity">
                                    <label class="form-check-label" for="has_electricity">Has Electricity</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetModals">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="saveInfra">Save Changes</span>
                                    <span wire:loading wire:target="saveInfra"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('livewire:initialized', () => {
                    let basicModal = null;
                    let infraModal = null;

                    @this.on('swal:success', (data) => {
                        if (basicModal) basicModal.hide();
                        if (infraModal) infraModal.hide();
                        
                        Swal.fire({
                            title: data[0].title,
                            text: data[0].text,
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                            buttonsStyling: false,
                        });
                    });

                    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                        succeed(({ snapshot, effect }) => {
                            if (component.name === 'principal.school.show-school-page') {
                                setTimeout(() => {
                                    if (@this.get('showBasicModal')) {
                                        if(!basicModal) {
                                            basicModal = new bootstrap.Modal(document.getElementById('basicInfoModal'));
                                        }
                                        basicModal.show();
                                    } else {
                                        if(basicModal) basicModal.hide();
                                    }

                                    if (@this.get('showInfraModal')) {
                                        if(!infraModal) {
                                            infraModal = new bootstrap.Modal(document.getElementById('infraModal'));
                                        }
                                        infraModal.show();
                                    } else {
                                        if(infraModal) infraModal.hide();
                                    }
                                }, 50);
                            }
                        })
                    })
                });
            </script>

        </div>
    </div>
</div>
