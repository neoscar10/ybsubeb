<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <!-- Dark Logo-->
        <a href="{{ route('home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/Yobe-SUBEB-logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/Yobe-SUBEB-logo.jpg') }}" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/Yobe-SUBEB-logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/Yobe-SUBEB-logo.jpg') }}" alt="" height="40">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ asset('themes/velzon') }}/images/users/avatar-1.jpg" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ Auth::user()->name }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
            <a class="dropdown-item" href="{{ route('profile.show') }}"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></button>
            </form>
        </div>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('ict-team'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="ri-user-settings-line"></i> <span data-key="t-users">User Management</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('ict-team') || Auth::user()->hasRole('principal'))
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">School Data</span></li>
                @endif

                @if(Auth::user()->hasRole('principal'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('principal.school.*') ? 'active' : '' }}" href="{{ route('principal.school.profile') }}">
                        <i class="ri-building-4-line"></i> <span data-key="t-my-school">My School</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasRole('principal') || Auth::user()->hasRole('school-staff'))
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-school-portal">School Portal</span></li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('portal.dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                        <i class="ri-dashboard-3-line"></i> <span data-key="t-portal-dash">Portal Dashboard</span>
                    </a>
                </li>

                @if(Auth::user()->hasRole('principal'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('portal.staff.*') ? 'active' : '' }}" href="{{ route('portal.staff.index') }}">
                        <i class="ri-team-line"></i> <span data-key="t-school-staff">Staff Management</span>
                    </a>
                </li>
                @endif

                
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('portal.students.*') ? 'active' : '' }}" href="{{ route('portal.students.index') }}">
                        <i class="ri-group-line"></i> <span data-key="t-students">Students</span>
                    </a>
                </li>
                
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('ict-team'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.schools.*') ? 'active' : '' }}" href="{{ route('admin.schools.index') }}">
                        <i class="ri-building-line"></i> <span data-key="t-schools">Schools Registry</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                        <i class="ri-team-line"></i> <span data-key="t-teachers">Teachers & Staff</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.enrollment.*') ? 'active' : '' }}" href="{{ route('admin.enrollment.index') }}">
                        <i class="ri-parent-line"></i> <span data-key="t-enrollment">Enrollment & Pupils</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.infrastructure.*') ? 'active' : '' }}" href="{{ route('admin.infrastructure.index') }}">
                        <i class="ri-community-line"></i> <span data-key="t-infrastructure">Infrastructure</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('principal'))
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Needs Assessment</span></li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAssessment" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.assessment.*') || Route::is('principal.assessment.*') ? 'true' : 'false' }}" aria-controls="sidebarAssessment">
                        <i class="ri-survey-line"></i> <span data-key="t-assessment">Needs Assessment</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.assessment.*') || Route::is('principal.assessment.*') ? 'show' : '' }}" id="sidebarAssessment">
                        <ul class="nav nav-sm flex-column">
                            @if(Auth::user()->hasRole('principal'))
                            <li class="nav-item">
                                <a href="{{ route('principal.assessment.submit') }}" class="nav-link {{ Route::is('principal.assessment.submit') ? 'active' : '' }}" data-key="t-submit-needs">Submit Needs</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('principal.assessment.history') }}" class="nav-link {{ Route::is('principal.assessment.history') ? 'active' : '' }}" data-key="t-assessment-history">Assessment History</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('chairman'))
                            <li class="nav-item">
                                <a href="{{ route('admin.assessment.windows') }}" class="nav-link {{ Route::is('admin.assessment.windows') ? 'active' : '' }}" data-key="t-assessment-windows">Assessment Windows</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('director'))
                            <li class="nav-item">
                                <a href="{{ route('admin.assessment.review') }}" class="nav-link {{ Route::is('admin.assessment.review') ? 'active' : '' }}" data-key="t-review-submissions">Review Submissions</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('chairman'))
                            <li class="nav-item">
                                <a href="{{ route('admin.assessment.approvals') }}" class="nav-link {{ Route::is('admin.assessment.approvals') ? 'active' : '' }}" data-key="t-approvals">Approvals</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director'))
                            <li class="nav-item">
                                <a href="{{ route('admin.assessment.summary') }}" class="nav-link {{ Route::is('admin.assessment.summary') ? 'active' : '' }}" data-key="t-assessment-summary">Summary Dashboard</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director'))
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">System</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="ri-file-chart-line"></i> <span data-key="t-reports">Reports</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('ict-team'))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarContent" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.downloads.*') || Route::is('admin.announcements.*') ? 'true' : 'false' }}" aria-controls="sidebarContent">
                        <i class="ri-folder-open-line"></i> <span data-key="t-content">Content Manager</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.downloads.*') || Route::is('admin.announcements.*') ? 'show' : '' }}" id="sidebarContent">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.downloads.index') }}" class="nav-link {{ Route::is('admin.downloads.index') ? 'active' : '' }}" data-key="t-downloads">Downloads</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ Route::is('admin.announcements.index') ? 'active' : '' }}" data-key="t-announcements">Announcements</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                        <i class="ri-notification-3-line"></i> <span data-key="t-notifications">Notifications</span>
                    </a>
                </li>

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
                        <i class="ri-history-line"></i> <span data-key="t-audit-logs">Audit Logs</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasRole('chairman') || Auth::user()->hasRole('director') || Auth::user()->hasRole('ict-team'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="ri-settings-4-line"></i> <span data-key="t-settings">Settings</span>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="nav-link" data-key="t-logout"> 
                            <i class="ri-logout-box-line"></i> <span data-key="t-logout">Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
