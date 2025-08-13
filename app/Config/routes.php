<?php
return [
    '/' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],

    '/auth/login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],
    '/auth/do-login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doLogin'],
    '/auth/forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'forgotPassword'],
    '/auth/do-forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doForgotPassword'],
    '/auth/reset-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'resetPassword'],
    '/auth/do-reset-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doResetPassword'],
    '/auth/logout' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'logout', 'middleware' => ['auth']],

    '/dashboard' => ['module' => 'SistemDataMaster', 'controller' => 'Home', 'action' => 'index', 'middleware' => ['auth']],

    // Settings Admin
    '/settings' => ['module' => 'SistemDataMaster', 'controller' => 'Settings', 'action' => 'index', 'middleware' => ['auth','role:admin']],
    '/settings/save' => ['module' => 'SistemDataMaster', 'controller' => 'Settings', 'action' => 'save', 'middleware' => ['auth','role:admin']],

    // Profile
    '/profile' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'view', 'middleware' => ['auth']],
    '/profile/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'edit', 'middleware' => ['auth']],
    '/profile/update' => ['module' => 'SistemDataMaster', 'controller' => 'Profile', 'action' => 'update', 'middleware' => ['auth']],

    // Users CRUD + Import/Export
    '/users' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'index', 'middleware' => ['auth','role:admin']],
    '/users/create' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'create', 'middleware' => ['auth','role:admin']],
    '/users/store' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'store', 'middleware' => ['auth','role:admin']],
    '/users/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'edit', 'middleware' => ['auth','role:admin']],
    '/users/update' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'update', 'middleware' => ['auth','role:admin']],
    '/users/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'delete', 'middleware' => ['auth','role:admin']],
    '/users/import' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'import', 'middleware' => ['auth','role:admin']],
    '/users/do-import' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'doImport', 'middleware' => ['auth','role:admin']],
    '/users/export' => ['module' => 'SistemDataMaster', 'controller' => 'Users', 'action' => 'export', 'middleware' => ['auth','role:admin']],

    // Roles & Assignments
    '/roles' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'index', 'middleware' => ['auth','role:admin']],
    '/roles/assign' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'assign', 'middleware' => ['auth','role:admin']],
    '/roles/save-assignment' => ['module' => 'SistemDataMaster', 'controller' => 'Roles', 'action' => 'saveAssignment', 'middleware' => ['auth','role:admin']],

    // Lembaga CRUD + Import/Export
    '/lembaga' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'index', 'middleware' => ['auth','role:admin']],
    '/lembaga/create' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'create', 'middleware' => ['auth','role:admin']],
    '/lembaga/store' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'store', 'middleware' => ['auth','role:admin']],
    '/lembaga/edit' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'edit', 'middleware' => ['auth','role:admin']],
    '/lembaga/update' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'update', 'middleware' => ['auth','role:admin']],
    '/lembaga/delete' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'delete', 'middleware' => ['auth','role:admin']],
    '/lembaga/import' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'import', 'middleware' => ['auth','role:admin']],
    '/lembaga/do-import' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'doImport', 'middleware' => ['auth','role:admin']],
    '/lembaga/export' => ['module' => 'SistemDataMaster', 'controller' => 'Lembaga', 'action' => 'export', 'middleware' => ['auth','role:admin']],

    // Setup Installer (DEV only)
    '/setup/install' => ['module' => 'Setup', 'controller' => 'Install', 'action' => 'run'],

    // SIMS
    '/sims/surat-masuk' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'masuk', 'middleware' => ['auth']],
    '/sims/surat-keluar' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'keluar', 'middleware' => ['auth']],
    '/sims/laporan-agenda' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'agenda', 'middleware' => ['auth']],
    '/sims/export-csv' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'exportCsv', 'middleware' => ['auth']],
    '/sims/export-pdf' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'exportPdf', 'middleware' => ['auth']],
    '/sims/surat-create' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'create', 'middleware' => ['auth']],
    '/sims/surat-store' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'store', 'middleware' => ['auth']],
    '/sims/surat-edit' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'edit', 'middleware' => ['auth']],
    '/sims/surat-update' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'update', 'middleware' => ['auth']],
    '/sims/surat-delete' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'delete', 'middleware' => ['auth']],
    '/sims/lampiran-upload' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'uploadLampiran', 'middleware' => ['auth']],
    '/sims/lampiran-delete' => ['module' => 'SistemSurat', 'controller' => 'Crud', 'action' => 'deleteLampiran', 'middleware' => ['auth']],
    '/sims/import-klasifikasi' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'importKlasifikasi', 'middleware' => ['auth']],
    '/sims/do-import-klasifikasi' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'doImportKlasifikasi', 'middleware' => ['auth']],

    // Keuangan
    '/keuangan' => ['module' => 'Keuangan', 'controller' => 'Dashboard', 'action' => 'index', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'index', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi/create' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'create', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi/store' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'store', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi/edit' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'edit', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi/update' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'update', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/transaksi/delete' => ['module' => 'Keuangan', 'controller' => 'Transaksi', 'action' => 'delete', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/laporan' => ['module' => 'Keuangan', 'controller' => 'Laporan', 'action' => 'index', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/invoice-gaji' => ['module' => 'Keuangan', 'controller' => 'Invoice', 'action' => 'gaji', 'middleware' => ['auth','scope:keuangan']],
    '/keuangan/invoice-gaji/send' => ['module' => 'Keuangan', 'controller' => 'Invoice', 'action' => 'send', 'middleware' => ['auth','scope:keuangan']],

    // Program Kerja
    '/program-kerja' => ['module' => 'ProgramKerja', 'controller' => 'Dashboard', 'action' => 'index', 'middleware' => ['auth']],
    '/program-kerja/proker' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'index', 'middleware' => ['auth']],
    '/program-kerja/proker/create' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'create', 'middleware' => ['auth']],
    '/program-kerja/proker/store' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'store', 'middleware' => ['auth']],
    '/program-kerja/proker/edit' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'edit', 'middleware' => ['auth']],
    '/program-kerja/proker/update' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'update', 'middleware' => ['auth']],
    '/program-kerja/proker/delete' => ['module' => 'ProgramKerja', 'controller' => 'Proker', 'action' => 'delete', 'middleware' => ['auth']],
];