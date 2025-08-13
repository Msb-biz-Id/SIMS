<div class="page-header d-print-none">
	<div class="row g-2 align-items-center">
		<div class="col"><h2 class="page-title">Tambah Lembaga</h2></div>
		<div class="col-auto ms-auto"><a href="<?= base_url('lembaga') ?>" class="btn">Kembali</a></div>
	</div>
</div>
<div class="card"><div class="card-body">
	<form action="<?= base_url('lembaga/store') ?>" method="post" enctype="multipart/form-data">
		<?= csrf_field() ?>
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Nama</label>
				<input type="text" name="name" class="form-control" required>
			</div>
			<div class="col-md-6">
				<label class="form-label">Induk (opsional)</label>
				<input type="number" name="parent_id" class="form-control" placeholder="ID lembaga induk">
			</div>
			<div class="col-md-12">
				<label class="form-label">Deskripsi</label>
				<textarea name="description" class="form-control" rows="3"></textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Logo</label>
				<input type="file" name="logo" class="form-control" accept="image/*">
			</div>
			<div class="col-md-6 d-flex align-items-end">
				<label class="form-check">
					<input class="form-check-input" type="checkbox" name="is_keuangan" id="is_keuangan">
					<span class="form-check-label"> Tandai sebagai lembaga keuangan</span>
				</label>
			</div>
		</div>
		<div class="mt-3"><button type="submit" class="btn btn-primary">Simpan</button></div>
	</form>
</div></div>