<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0"><?= e($title) ?></h6>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('keuangan/invoice-gaji/send') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Filter Lembaga</label>
        <select name="lembaga_id" class="form-select">
          <option value="">- Semua -</option>
          <?php foreach (($lembagaOptions ?? []) as $opt): ?>
            <option value="<?= (int)$opt['id'] ?>"><?= e($opt['name']) ?> (#<?= (int)$opt['id'] ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-8">
        <label class="form-label">Pilih Penerima (berdasarkan lembaga)</label>
        <select name="user_ids[]" class="form-select" multiple>
          <?php foreach (($users ?? []) as $u): ?>
            <option data-lembaga="<?= (int)$u['lembaga_id'] ?>" value="<?= (int)$u['id'] ?>"><?= e($u['name']) ?> (<?= e($u['email']) ?>)</option>
          <?php endforeach; ?>
        </select>
        <small class="text-secondary">Gunakan CTRL/Cmd untuk memilih banyak. Daftar otomatis dapat difilter berdasarkan Lembaga.</small>
      </div>
      <div class="col-12">
        <label class="form-label">Atau masukkan email manual</label>
        <textarea name="emails" class="form-control" rows="2" placeholder="pisahkan dengan koma, titik koma, atau baris baru"></textarea>
      </div>
      <div class="col-12">
        <div class="alert alert-info p-2">
          Placeholder yang tersedia: <code>{{nama}}</code>, <code>{{bulan}}</code>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">Subjek</label>
        <input type="text" name="subject" class="form-control" value="Invoice Gaji {{bulan}}">
      </div>
      <div class="col-12">
        <label class="form-label">Isi Email (HTML diperbolehkan)</label>
        <textarea name="body" class="form-control" rows="10" placeholder="<p>Yth. {{nama}},</p><p>Berikut kami kirimkan invoice gaji periode {{bulan}}.</p><p>Terima kasih.</p>"></textarea>
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="attach_pdf" id="attach_pdf">
          <label class="form-check-label" for="attach_pdf"> Lampirkan versi PDF dari isi email</label>
        </div>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Kirim Invoice</button>
      <a href="<?= base_url('keuangan') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div></div>
<script>
  (function(){
    const lembagaSelect = document.querySelector('select[name="lembaga_id"]');
    const usersSelect = document.querySelector('select[name="user_ids[]"]');
    function filterUsers(){
      const lid = lembagaSelect.value;
      for (const opt of usersSelect.options) {
        if (!lid) { opt.hidden = false; continue; }
        opt.hidden = (String(opt.getAttribute('data-lembaga')) !== String(lid));
      }
    }
    lembagaSelect && lembagaSelect.addEventListener('change', filterUsers);
    filterUsers();
  })();
</script>