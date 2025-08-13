<div class="navbar-nav">
	<ul class="navbar-nav">
		<li class="nav-item"><a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">SIMS</a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?= base_url('sims/surat-masuk') ?>">Surat Masuk</a>
				<a class="dropdown-item" href="<?= base_url('sims/surat-keluar') ?>">Surat Keluar</a>
				<a class="dropdown-item" href="<?= base_url('sims/laporan-agenda') ?>">Laporan Agenda</a>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Keuangan</a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?= base_url('keuangan') ?>">Manajemen Keuangan</a>
				<a class="dropdown-item" href="<?= base_url('keuangan/invoice-gaji') ?>">Invoice Gaji</a>
				<a class="dropdown-item" href="<?= base_url('keuangan/laporan') ?>">Laporan Keuangan</a>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Program Kerja</a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?= base_url('program-kerja') ?>">Program Kerja</a>
				<a class="dropdown-item" href="<?= base_url('program-kerja/proker') ?>">Daftar Proker</a>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Data Master</a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?= base_url('users') ?>">Users</a>
				<a class="dropdown-item" href="<?= base_url('roles') ?>">Roles & Assignment</a>
				<a class="dropdown-item" href="<?= base_url('lembaga') ?>">Lembaga</a>
				<a class="dropdown-item" href="<?= base_url('settings') ?>">Pengaturan</a>
			</div>
		</li>
		<li class="nav-item"><a class="nav-link" href="<?= base_url('profile') ?>">Profil Saya</a></li>
	</ul>
</div>