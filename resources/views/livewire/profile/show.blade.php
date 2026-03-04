<div class="row">
    <div class="col-xxl-3">
        <div class="card mt-n5">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <div class="avatar-xl rounded-circle bg-light d-flex align-items-center justify-content-center">
                            <span class="text-primary fs-3 fw-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <h5 class="fs-16 mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-0">{{ Auth::user()->roleLabel() }}</p>
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-5">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">Complete Your Profile</h5>
                    </div>
                </div>
                <div class="progress animated-progress custom-progress progress-label">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ Auth::user()->phone ? '100%' : '80%' }}" aria-valuenow="{{ Auth::user()->phone ? '100' : '80' }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="label">{{ Auth::user()->phone ? '100%' : '80%' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Info</h5>
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Full Name :</th>
                                <td class="text-muted">{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Mobile :</th>
                                <td class="text-muted">{{ Auth::user()->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">E-mail :</th>
                                <td class="text-muted">{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Location :</th>
                                <td class="text-muted">{{ Auth::user()->school ? Auth::user()->school->lga : 'Headquarters' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Joining Date</th>
                                <td class="text-muted">{{ Auth::user()->created_at->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-xxl-9">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                            <i class="fas fa-home"></i> Personal Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                            <i class="far fa-user"></i> Change Password
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        
                        @if (session('profile-success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('profile-success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit="updateProfile">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="firstnameInput" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="firstnameInput" placeholder="Enter your name" wire:model="name">
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phonenumberInput" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phonenumberInput" placeholder="Enter your phone number" wire:model="phone">
                                        @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="emailInput" placeholder="Enter your email" wire:model="email" @if(!Auth::user()->isAdmin() && !Auth::user()->hasRole('ict-team')) readonly disabled @endif>
                                        @if(!Auth::user()->isAdmin() && !Auth::user()->hasRole('ict-team'))
                                            <div class="form-text text-muted">Contact SUBEB ICT to update your email address.</div>
                                        @endif
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                
                                @if(Auth::user()->school)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">School</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->school->name }}" readonly disabled>
                                    </div>
                                </div>
                                @endif

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Updates</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="changePassword" role="tabpanel">
                        
                         @if (session('password-success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('password-success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit="updatePassword">
                            <div class="row g-2">
                                <div class="col-lg-4">
                                    <div>
                                        <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="oldpasswordInput" placeholder="Enter current password" wire:model="current_password">
                                        @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="newpasswordInput" class="form-label">New Password*</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="newpasswordInput" placeholder="Enter new password" wire:model="password">
                                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                        <input type="password" class="form-control" id="confirmpasswordInput" placeholder="Confirm password" wire:model="password_confirmation">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <a href="javascript:void(0);" class="link-primary text-decoration-underline">Forgot Password ?</a>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">Change Password</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->
