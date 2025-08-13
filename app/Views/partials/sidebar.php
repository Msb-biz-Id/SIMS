<aside class="sidebar">
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="<?= base_url() ?>" class="sidebar-logo">
      <img src="assets/images/logo.png" alt="site logo" class="light-logo">
      <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
      <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon">
    </a>
  </div>
  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">
      <li class="sidebar-menu-group-title">Sistem</li>

      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
          <span>SIMS</span>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="<?= base_url('sims/surat-masuk') ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Surat Masuk</a></li>
          <li><a href="<?= base_url('sims/surat-keluar') ?>"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Surat Keluar</a></li>
          <li><a href="<?= base_url('sims/laporan-agenda') ?>"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Laporan Agenda</a></li>
        </ul>
      </li>

      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
          <span>Keuangan</span>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="<?= base_url('keuangan') ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Manajemen Keuangan</a></li>
          <li><a href="<?= base_url('keuangan/invoice-gaji') ?>"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Invoice Gaji</a></li>
          <li><a href="<?= base_url('keuangan/laporan') ?>"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Laporan Keuangan</a></li>
        </ul>
      </li>

      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="solar:calendar-outline" class="menu-icon"></iconify-icon>
          <span>Program Kerja</span>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="<?= base_url('program-kerja') ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Program Kerja</a></li>
          <li><a href="<?= base_url('program-kerja/anggaran') ?>"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Manajemen Anggaran</a></li>
        </ul>
      </li>

    </ul>
  </div>
</aside>