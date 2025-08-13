<?php
return [
    '/' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],

    '/auth/login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'login'],
    '/auth/do-login' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doLogin'],
    '/auth/forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'forgotPassword'],
    '/auth/do-forgot-password' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'doForgotPassword'],
    '/auth/logout' => ['module' => 'Auth', 'controller' => 'Auth', 'action' => 'logout'],

    '/sims/surat-masuk' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'masuk'],
    '/sims/surat-keluar' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'keluar'],
    '/sims/laporan-agenda' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'agenda'],

    '/keuangan' => ['module' => 'Keuangan', 'controller' => 'Dashboard', 'action' => 'index'],
    '/keuangan/invoice-gaji' => ['module' => 'Keuangan', 'controller' => 'Invoice', 'action' => 'gaji'],
    '/keuangan/laporan' => ['module' => 'Keuangan', 'controller' => 'Laporan', 'action' => 'index'],

    '/program-kerja' => ['module' => 'ProgramKerja', 'controller' => 'Dashboard', 'action' => 'index'],
    '/program-kerja/anggaran' => ['module' => 'ProgramKerja', 'controller' => 'Anggaran', 'action' => 'index'],
];