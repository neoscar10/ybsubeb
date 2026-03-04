<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Students Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Portal</a></li>
                        <li class="breadcrumb-item active">Students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Students Directory</h5>
                        <div class="flex-shrink-0">
                            {{-- Add Student --}}
                            <button wire:click="create" class="btn btn-primary add-btn waves-effect waves-light">
                                <i class="ri-add-line align-bottom me-1"></i> Add Student
                            </button>
                            
                            {{-- Export CSV --}}
                            <button wire:click="exportCsv" wire:loading.attr="disabled" class="btn btn-soft-success ms-1 waves-effect waves-light">
                                <i class="mdi mdi-download align-bottom me-1"></i> <span wire:loading.remove wire:target="exportCsv">Export CSV</span><span wire:loading wire:target="exportCsv">Exporting...</span>
                            </button>

                            {{-- Actions --}}
                            @if(Auth::user()->isPrincipal() || Auth::user()->can_upload_students)
                                <button type="button" class="btn btn-secondary ms-1 waves-effect waves-light">
                                    <i class="ri-upload-cloud-2-line align-bottom me-1"></i> Bulk Upload
                                </button>
                            @endif

                            @if(Auth::user()->isPrincipal())
                                <button type="button" class="btn btn-info ms-1 waves-effect waves-light" onclick="alert('Promotions module is currently under maintenance.')">
                                    <i class="ri-arrow-up-circle-line align-bottom me-1"></i> Promote
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-header border-0 border-bottom border-dashed">
                    <div class="row g-2">
                        <div class="col-xl-4 col-sm-6">
                             <div class="search-box">
                                <input type="text" class="form-control search" wire:model.live.debounce.300ms="search" placeholder="Search by name, admission no...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                         <div class="col-xl-3 col-sm-6">
                            <select class="form-select" wire:model.live="classFilter">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="transferred">Transferred</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-sm-6">
                            <button type="button" class="btn btn-ghost-secondary w-100" wire:click="$set('search', '')">
                                <i class="ri-refresh-line align-bottom me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive table-card mb-3">
                        <table class="table align-middle table-nowrap table-hover mb-0">
                             <thead class="table-light">
                                <tr>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Admission NO</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Guardian</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ substr($student->first_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="fs-14 m-0">
                                                    <a href="javascript:void(0);" wire:click="edit({{ $student->id }})" class="text-reset">{{ $student->full_name }}</a>
                                                </h5>
                                                <small class="text-muted">{{ $student->date_of_birth ? $student->date_of_birth->format('d M, Y') : '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted">{{ $student->admission_no ?? '-' }}</span></td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $student->subebClass->name }}</span></td>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                    <td>
                                        @if($student->guardian_name)
                                            <h6 class="fs-13 mb-1">{{ $student->guardian_name }}</h6>
                                            <span class="text-muted fs-12">{{ $student->guardian_phone }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusType = match($student->status) {
                                                'active' => 'success',
                                                'inactive' => 'danger',
                                                'transferred' => 'warning',
                                                'graduated' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusType }}-subtle text-{{ $statusType }} text-uppercase">{{ $student->status }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0);" wire:click="edit({{ $student->id }})"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="confirm('Mark student as transferred out?') || event.stopImmediatePropagation()"><i class="ri-shut-down-line align-bottom me-2 text-muted"></i> Transfer Out</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                     <td colspan="7">
                                        <div class="noresult">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                <h5 class="mt-2">No Students Found</h5>
                                                <p class="text-muted mb-0">Use the filters or add a new student.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Modal -->
    <div wire:ignore.self class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">{{ $editMode ? 'Edit Student' : 'Add New Student' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                        
                        <!-- Personal -->
                        <div class="row g-3 mb-4">
                            <div class="col-12"><h6 class="fw-semibold text-uppercase fs-12 text-muted">Personal Information</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" wire:model="first_name">
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" wire:model="last_name">
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Name</label>
                                <input type="text" class="form-control" wire:model="other_name">
                            </div>

                             <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" wire:model="gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" wire:model="date_of_birth">
                            </div>
                        </div>

                        <!-- Academic -->
                        <div class="row g-3 mb-4">
                             <div class="col-12 border-top pt-3"><h6 class="fw-semibold text-uppercase fs-12 text-muted">Academic Information</h6></div>
                             
                             <div class="col-md-4">
                                <label class="form-label">Current Class <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" wire:model="class_id">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             
                             <div class="col-md-4">
                                <label class="form-label">Admission Number</label>
                                <input type="text" class="form-control" wire:model="admission_no" placeholder="School Adm No">
                                <span class="form-text text-muted fs-11">Unique identifier per school</span>
                             </div>

                             <div class="col-md-4">
                                 <label class="form-label">Status</label>
                                 <select class="form-select" wire:model="status">
                                     <option value="active">Active</option>
                                     <option value="inactive">Inactive</option>
                                     <option value="transferred">Transferred</option>
                                     <option value="graduated">Graduated</option>
                                 </select>
                             </div>
                        </div>

                        <!-- Guardian -->
                        <div class="row g-3">
                             <div class="col-12 border-top pt-3"><h6 class="fw-semibold text-uppercase fs-12 text-muted">Parent / Guardian Information</h6></div>
                             <div class="col-md-6">
                                 <label class="form-label">Guardian Name</label>
                                 <input type="text" class="form-control" wire:model="guardian_name">
                             </div>
                             <div class="col-md-6">
                                 <label class="form-label">Guardian Phone</label>
                                 <input type="text" class="form-control" wire:model="guardian_phone">
                             </div>
                             <div class="col-12">
                                 <label class="form-label">Address</label>
                                 <textarea class="form-control" wire:model="address" rows="2"></textarea>
                             </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Save Student' : 'Add Student' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-modal', () => {
            var modal = new bootstrap.Modal(document.getElementById('studentModal'));
            modal.show();
        });

        $wire.on('hide-modal', () => {
            var modalEl = document.getElementById('studentModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>
    @endscript
</div>
