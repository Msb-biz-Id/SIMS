<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Edit Lembaga</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('lembaga') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
	<form action="<?= base_url('lembaga/update') ?>" method="post" enctype="multipart/form-data">
		<?= csrf_field() ?>
		<input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Nama</label>
				<input type="text" name="name" class="form-control" required value="<?= e($item['name']) ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Induk (opsional)</label>
				<input type="number" name="parent_id" class="form-control" placeholder="ID lembaga induk" value="<?= e((string)$item['parent_id']) ?>">
			</div>
			<div class="col-md-12">
				<label class="form-label">Deskripsi</label>
				<textarea name="description" class="form-control" rows="3"><?= e((string)$item['description']) ?></textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Logo</label>
				<input type="file" name="logo" class="form-control" accept="image/*">
				<?php if (!empty($item['logo_path'])): ?>
					<div class="mt-2"><img src="<?= base_url($item['logo_path']) ?>" alt="logo" height="60"></div>
				<?php endif; ?>
			</div>
			<div class="col-md-6 d-flex align-items-end">
				<label class="form-check">
					<input class="form-check-input" type="checkbox" name="is_keuangan" id="is_keuangan" <?= $item['is_keuangan'] ? 'checked' : '' ?>>
					<span class="form-check-label"> Tandai sebagai lembaga keuangan</span>
				</label>
			</div>
		</div>

		<div class="mt-4 p-3 border rounded-3 bg-body-secondary">
			<h3 class="card-title mb-2">Pengaturan Penomoran Surat</h3>
			<div class="row g-3">
				<div class="col-md-4">
					<label class="form-label">Mode Penomoran</label>
					<select name="surat_nomor_mode" class="form-select">
						<option value="statis">Statis (manual)</option>
						<option value="dinamis" <?= (isset($item['surat_nomor_mode']) && $item['surat_nomor_mode']==='dinamis') ? 'selected' : '' ?>>Dinamis (auto increment per tahun)</option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Prefix Nomor (opsional)</label>
					<input type="text" name="surat_nomor_prefix" class="form-control" value="<?= e((string)($item['surat_nomor_prefix'] ?? '')) ?>" placeholder="mis. UNIV/FAK/JUR">
				</div>
				<div class="col-md-4">
					<label class="form-label">Counter Saat Ini</label>
					<input type="number" name="surat_nomor_counter" class="form-control" value="<?= e((string)($item['surat_nomor_counter'] ?? '0')) ?>">
				</div>
			</div>
		</div>

		<div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
	</form>
</div></div>