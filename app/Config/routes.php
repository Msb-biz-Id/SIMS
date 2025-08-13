<?php
return [
    '/' => ['module' => 'SistemDataMaster', 'controller' => 'Home', 'action' => 'index'],

    '/sims/surat-masuk' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'masuk'],
    '/sims/surat-keluar' => ['module' => 'SistemSurat', 'controller' => 'Surat', 'action' => 'keluar'],
    '/sims/laporan-agenda' => ['module' => 'SistemSurat', 'controller' => 'Laporan', 'action' => 'agenda'],

    '/keuangan' => ['module' => 'Keuangan', 'controller' => 'Dashboard', 'action' => 'index'],
    '/keuangan/invoice-gaji' => ['module' => 'Keuangan', 'controller' => 'Invoice', 'action' => 'gaji'],
    '/keuangan/laporan' => ['module' => 'Keuangan', 'controller' => 'Laporan', 'action' => 'index'],

    '/program-kerja' => ['module' => 'ProgramKerja', 'controller' => 'Dashboard', 'action' => 'index'],
    '/program-kerja/anggaran' => ['module' => 'ProgramKerja', 'controller' => 'Anggaran', 'action' => 'index'],
];