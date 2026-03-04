<?php

return [
    'menu' => [
        [
            'title' => 'Main',
            'items' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'mdi mdi-view-dashboard',
                    'roles' => ['chairman', 'director', 'principal', 'ict-team'],
                ],
            ],
        ],
        [
            'title' => 'School Data',
            'items' => [
                [
                    'title' => 'Schools Registry',
                    'route' => 'admin.schools.index',
                    'icon' => 'mdi mdi-school',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
                [
                    'title' => 'My School Profile',
                    'route' => 'principal.school.profile',
                    'icon' => 'mdi mdi-school',
                    'roles' => ['principal'],
                ],
                [
                    'title' => 'Teachers & Staff',
                    'route' => 'admin.teachers.index',
                    'icon' => 'mdi mdi-account-group',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
                [
                    'title' => 'Enrollment & Pupils',
                    'route' => 'admin.enrollment.index',
                    'icon' => 'mdi mdi-account-multiple',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
                [
                    'title' => 'Infrastructure & Facilities',
                    'route' => 'admin.infrastructure.index',
                    'icon' => 'mdi mdi-home-city',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
            ],
        ],
        [
            'title' => 'Needs Assessment',
            'items' => [
                [
                    'title' => 'Open/Close Assessment',
                    'route' => 'admin.assessment.windows',
                    'icon' => 'mdi mdi-calendar-clock',
                    'roles' => ['chairman'],
                ],
                [
                    'title' => 'Submit Needs Assessment',
                    'route' => 'principal.assessment.submit',
                    'icon' => 'mdi mdi-clipboard-text-outline',
                    'roles' => ['principal'],
                ],
                [
                    'title' => 'Review Submissions',
                    'route' => 'admin.assessment.review',
                    'icon' => 'mdi mdi-file-find-outline',
                    'roles' => ['director'],
                ],
                [
                    'title' => 'Approvals',
                    'route' => 'admin.assessment.approvals',
                    'icon' => 'mdi mdi-check-decagram',
                    'roles' => ['chairman'],
                ],
                [
                    'title' => 'Assessment Summary',
                    'route' => 'admin.assessment.summary',
                    'icon' => 'mdi mdi-chart-box-outline',
                    'roles' => ['chairman', 'director'],
                ],
            ],
        ],
        [
            'title' => 'Reports & Exports',
            'items' => [
                [
                    'title' => 'Reports Dashboard',
                    'route' => 'admin.reports.index',
                    'icon' => 'mdi mdi-file-chart',
                    'roles' => ['chairman', 'director'],
                ],
                [
                    'title' => 'Downloads / Publications',
                    'route' => 'admin.downloads.index',
                    'icon' => 'mdi mdi-download',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
            ],
        ],
        [
            'title' => 'Communications',
            'items' => [
                [
                    'title' => 'Announcements',
                    'route' => 'admin.announcements.index',
                    'icon' => 'mdi mdi-bullhorn',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
                [
                    'title' => 'Notifications',
                    'route' => 'admin.notifications.index',
                    'icon' => 'mdi mdi-bell-outline',
                    'roles' => ['chairman', 'director', 'principal', 'ict-team'],
                ],
            ],
        ],
        [
            'title' => 'Administration',
            'items' => [
                [
                    'title' => 'User Management',
                    'route' => 'admin.users.index',
                    'icon' => 'mdi mdi-account-cog',
                    'roles' => ['chairman', 'director'],
                ],
                [
                    'title' => 'Audit Logs',
                    'route' => 'admin.audit.index',
                    'icon' => 'mdi mdi-shield-search',
                    'roles' => ['chairman', 'director'],
                ],
                [
                    'title' => 'System Settings',
                    'route' => 'admin.settings.index',
                    'icon' => 'mdi mdi-cog-outline',
                    'roles' => ['chairman', 'director', 'ict-team'],
                ],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                [
                    'title' => 'My Profile',
                    'route' => 'admin.profile',
                    'icon' => 'mdi mdi-account-circle',
                    'roles' => ['chairman', 'director', 'principal', 'ict-team'],
                ],
                [
                    'title' => 'Logout',
                    'route' => 'logout',
                    'icon' => 'mdi mdi-logout',
                    'roles' => ['chairman', 'director', 'principal', 'ict-team'],
                    'method' => 'POST', // Helper for render logic
                ],
            ],
        ],
    ],
];
