<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Program Kerja</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('program-kerja/proker/create') ?>" class="btn btn-primary">Tambah</a></div>
	</div>
</div>
<div class="card mb-3"><div class="card-body">
	<form method="get" class="row g-2 align-items-end">
		<div class="col-md-4">
			<label class="form-label">Lembaga</label>
			<select name="lembaga_id" class="form-select">
				<option value="">- Semua -</option>
				<?php foreach ($lembagaOptions as $opt): ?>
					<option value="<?= (int)$opt['id'] ?>" <?= ((int)($filters['lembaga_id'] ?? 0)===(int)$opt['id']) ? 'selected' : '' ?>><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label class="form-label">Periode</label>
			<input type="number" name="periode_year" class="form-control" value="<?= e((string)($filters['periode_year'] ?? '')) ?>" placeholder="Tahun">
		</div>
		<div class="col-md-2">
			<button class="btn btn-outline-primary w-100">Terapkan</button>
		</div>
	</form>
</div></div>
<div class="card"><div class="card-body p-0">
	<div class="table-responsive">
		<table class="table mb-0 datatable">
			<thead><tr><th>Lembaga</th><th>Nama</th><th>Periode</th><th>Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($rows as $r): ?>
				<tr>
					<td><?= e($r['lembaga_name']) ?> (#<?= (int)$r['lembaga_id'] ?>)</td>
					<td><?= e($r['nama']) ?></td>
					<td><?= e((string)$r['periode_year']) ?></td>
					<td class="d-flex gap-2">
						<a class="btn btn-sm" href="<?= base_url('program-kerja/proker/edit?id=' . (int)$r['id']) ?>">Edit</a>
						<form action="<?= base_url('program-kerja/proker/delete') ?>" method="post" onsubmit="return confirm('Hapus program kerja?')">
							<?= csrf_field() ?>
							<input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
							<button type="submit" class="btn btn-sm btn-danger">Hapus</button>
						</form>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div></div>