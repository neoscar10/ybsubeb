<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'home'])->name('home');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard.index');
    })->middleware(['verified'])->name('admin.dashboard');
    
    // User Management (Existing)
    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index')->middleware('role:chairman,director');

    // Schools Registry
    Route::get('/admin/schools', function () {
        return view('admin.placeholder', ['title' => 'Schools Registry', 'description' => 'Manage all schools, their locations, and basic details here.']);
    })->name('admin.schools.index')->middleware('role:chairman,director,ict-team');

    // Teachers & Staff
    Route::get('/admin/teachers', function () {
        return view('admin.teachers.index');
    })->name('admin.teachers.index')->middleware('role:chairman,director,ict-team');

    // Enrollment
    Route::get('/admin/enrollment', function () {
        return view('admin.enrollment.index');
    })->name('admin.enrollment.index')->middleware('role:chairman,director,ict-team');

    // Infrastructure
    Route::get('/admin/infrastructure', function () {
        return view('admin.placeholder', ['title' => 'Infrastructure & Facilities', 'description' => 'Monitor school buildings, facilities, and maintenance needs.']);
    })->name('admin.infrastructure.index')->middleware('role:chairman,director,ict-team');

    // Needs Assessment - Windows (Chairman)
    Route::get('/admin/assessment/windows', function () {
        return view('admin.assessment.windows.index');
    })->name('admin.assessment.windows')->middleware('role:chairman');

    // Needs Assessment - Review (Director) [Still Placeholder for now if not requested, but Approval page covers similar ground]
    Route::get('/admin/assessment/review', function () {
        return view('admin.placeholder', ['title' => 'Review Submissions', 'description' => 'Review incoming needs assessment data from schools.']);
    })->name('admin.assessment.review')->middleware('role:director');

    // Needs Assessment - Approvals (Chairman)
    Route::get('/admin/assessment/approvals', function () {
        return view('admin.assessment.approvals.index');
    })->name('admin.assessment.approvals')->middleware('role:chairman');

    // Needs Assessment - Summary (Chairman/Director)
    Route::get('/admin/assessment/summary', function () {
        return view('admin.assessment.summary.index');
    })->name('admin.assessment.summary')->middleware('role:chairman,director');

    // Principal Routes
    Route::middleware(['auth', 'role:principal'])->prefix('principal')->name('principal.')->group(function () {
        Route::get('/assessment/submit', function () {
            return view('principal.assessment.submit');
        })->name('assessment.submit');

        Route::get('/assessment/history', function () {
            return view('principal.assessment.history');
        })->name('assessment.history');
    });

    // Reports
    Route::get('/admin/reports', function () {
        return view('admin.placeholder', ['title' => 'Reports Dashboard', 'description' => 'Comprehensive reports and analytics across all modules.']);
    })->name('admin.reports.index')->middleware('role:chairman,director');

    // Downloads
    Route::get('/admin/downloads', function () {
        return view('admin.placeholder', ['title' => 'Downloads & Publications', 'description' => 'Manage downloadable resources and public documents.']);
    })->name('admin.downloads.index')->middleware('role:chairman,director,ict-team');

    // Announcements
    Route::get('/admin/announcements', function () {
        return view('admin.placeholder', ['title' => 'Announcements', 'description' => 'Manage internal circulars and public announcements.']);
    })->name('admin.announcements.index')->middleware('role:chairman,director,ict-team');

    // Notifications (All Roles)
    Route::get('/admin/notifications', function () {
        return view('admin.placeholder', ['title' => 'Notifications', 'description' => 'View your system notifications and alerts.']);
    })->name('admin.notifications.index');

    // Audit Logs
    Route::get('/admin/audit', function () {
        return view('admin.placeholder', ['title' => 'Audit Logs', 'description' => 'View detailed logs of system activities and user actions.']);
    })->name('admin.audit.index')->middleware('role:chairman,director');

    // Settings
    Route::get('/admin/settings', function () {
        return view('admin.placeholder', ['title' => 'System Settings', 'description' => 'Configure global system settings and preferences.']);
    })->name('admin.settings.index')->middleware('role:chairman,director,ict-team');

    // My Profile (All Roles)
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    // School Profile (Principal)
    Route::get('/principal/school', function () {
        return view('admin.placeholder', ['title' => 'My School Profile', 'description' => 'Manage your school details and staff overview.']);
    })->name('principal.school.profile')->middleware('role:principal');

    // Submit Assessment (Principal)


    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // School Registry
    Route::get('/schools', [App\Http\Controllers\Admin\SchoolsController::class, 'index'])->name('admin.schools.index');

    // School Portal Routes
    Route::middleware(['auth', 'active', 'school-portal'])->name('portal.')->prefix('portal')->group(function () {
        Route::get('/dashboard', function () {
            return view('portal.dashboard.index');
        })->name('dashboard');

        // Principal Only: Staff Management
        Route::get('/staff', function() {
            return view('portal.staff.index');
        })->middleware('role:principal')->name('staff.index');
            
        // Students Management (Principal + Authorized Staff)
        Route::get('/students', function() {
            return view('portal.students.index');
        })->name('students.index');
    });

});

require __DIR__.'/auth.php';
