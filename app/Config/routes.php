<?php
return [
    '/' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],

    '/auth/login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],
    '/auth/do-login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doLogin'],
    '/auth/forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'forgotPassword'],
    '/auth/do-forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doForgotPassword'],
    '/auth/logout' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'logout'],

    '/dashboard' => ['module' => 'SistemDataMaster', 'controller' => 'Home', 'action' => 'index'],

    // Profile
    '/profile' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'view'],
    '/profile/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'edit'],
    '/profile/update' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'update'],

    // Users CRUD
    '/users' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'index'],
    '/users/create' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'create'],
    '/users/store' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'store'],
    '/users/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'edit'],
    '/users/update' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'update'],
    '/users/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'delete'],

    // Roles & Assignments
    '/roles' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'index'],
    '/roles/assign' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'assign'],
    '/roles/save-assignment' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'saveAssignment'],

    // Lembaga CRUD (contoh Data Master)
    '/lembaga' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'index'],
    '/lembaga/create' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'create'],
    '/lembaga/store' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'store'],
    '/lembaga/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'edit'],
    '/lembaga/update' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'update'],
    '/lembaga/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'delete'],

    // Setup Installer (DEV only)
    '/setup/install' => ['module' => 'Setup', 'controller' => 'Install', 'action' => 'run'],

    // Modul lain sesuai BRD
    '/sims/surat-masuk' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'masuk'],
    '/sims/surat-keluar' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'keluar'],
    '/sims/laporan-agenda' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'agenda'],

    '/keuangan' => ['module' => 'Keuangan', 'controller' => 'Dashboard', 'action' => 'index'],
    '/keuangan/invoice-gaji' => ['module' => 'Keuangan', 'controller' => 'Invoice', 'action' => 'gaji'],
    '/keuangan/laporan' => ['module' => 'Keuangan', 'controller' => 'Laporan', 'action' => 'index'],

    '/program-kerja' => ['module' => 'ProgramKerja', 'controller' => 'Dashboard', 'action' => 'index'],
    '/program-kerja/anggaran' => ['module' => 'ProgramKerja', 'controller' => 'Anggaran', 'action' => 'index'],
];