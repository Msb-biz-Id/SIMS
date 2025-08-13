<?php
return [
    '/' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],

    '/auth/login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],
    '/auth/do-login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doLogin'],
    '/auth/forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'forgotPassword'],
    '/auth/do-forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doForgotPassword'],
    '/auth/reset-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'resetPassword'],
    '/auth/do-reset-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doResetPassword'],
    '/auth/logout' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'logout'],

    '/dashboard' => ['module' => 'SistemDataMaster', 'controller' => 'Home', 'action' => 'index'],

    // Settings Admin
    '/settings' => ['module' => 'SistemDataMaster', 'controller' => 'Settings', 'action' => 'index'],
    '/settings/save' => ['module' => 'SistemDataMaster', 'controller' => 'Settings', 'action' => 'save'],

    // Profile
    '/profile' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'view'],
    '/profile/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'edit'],
    '/profile/update' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'update'],

    // Users CRUD + Import/Export
    '/users' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'index'],
    '/users/create' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'create'],
    '/users/store' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'store'],
    '/users/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'edit'],
    '/users/update' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'update'],
    '/users/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'delete'],
    '/users/import' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'import'],
    '/users/do-import' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'doImport'],
    '/users/export' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'export'],

    // Roles & Assignments
    '/roles' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'index'],
    '/roles/assign' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'assign'],
    '/roles/save-assignment' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'saveAssignment'],

    // Lembaga CRUD + Import/Export
    '/lembaga' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'index'],
    '/lembaga/create' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'create'],
    '/lembaga/store' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'store'],
    '/lembaga/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'edit'],
    '/lembaga/update' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'update'],
    '/lembaga/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'delete'],
    '/lembaga/import' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'import'],
    '/lembaga/do-import' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'doImport'],
    '/lembaga/export' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'export'],

    // Setup Installer (DEV only)
    '/setup/install' => ['module' => 'Setup', 'controller' => 'Install', 'action' => 'run'],

    // SIMS
    '/sims/surat-masuk' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'masuk'],
    '/sims/surat-keluar' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'keluar'],
    '/sims/laporan-agenda' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'agenda'],
    '/sims/export-csv' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'exportCsv'],
    '/sims/export-pdf' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'exportPdf'],
    '/sims/surat-create' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'create'],
    '/sims/surat-store' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'store'],
    '/sims/surat-edit' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'edit'],
    '/sims/surat-update' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'update'],
    '/sims/surat-delete' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'delete'],
    '/sims/lampiran-upload' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'uploadLampiran'],
    '/sims/lampiran-delete' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'deleteLampiran'],
    '/sims/import-klasifikasi' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'importKlasifikasi'],
    '/sims/do-import-klasifikasi' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'doImportKlasifikasi'],

    // Keuangan
    '/keuangan' => ['module' => 'Keuangan', 'controller' => 'Dashboard', 'action' => 'index'],
    '/keuangan/transaksi' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'index'],
    '/keuangan/transaksi/create' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'create'],
    '/keuangan/transaksi/store' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'store'],
    '/keuangan/transaksi/edit' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'edit'],
    '/keuangan/transaksi/update' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'update'],
    '/keuangan/transaksi/delete' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'delete'],
    '/keuangan/laporan' => ['module' => 'Keuangan', 'controller' => 'Laporan', 'action' => 'index'],

    // Program Kerja
    '/program-kerja' => ['module' => 'ProgramKerja', 'controller' => 'Dashboard', 'action' => 'index'],
    '/program-kerja/proker' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'index'],
    '/program-kerja/proker/create' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'create'],
    '/program-kerja/proker/store' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'store'],
    '/program-kerja/proker/edit' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'edit'],
    '/program-kerja/proker/update' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'update'],
    '/program-kerja/proker/delete' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'delete'],
];