<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0"><?= e($title) ?></h6>
</div>
<div class="card shadow-none border"><div class="card-body">
  <form action="<?= base_url('keuangan/invoice-gaji/send') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">Subjek</label>
        <input type="text" name="subject" class="form-control" value="Invoice Gaji <?= e(date('F Y')) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Email Penerima</label>
        <textarea name="emails" class="form-control" rows="2" placeholder="pisahkan dengan koma, titik koma, atau baris baru"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Isi Email (HTML diperbolehkan)</label>
        <textarea name="body" class="form-control" rows="10" placeholder="<p>Yth. Bapak/Ibu,</p><p>Berikut kami kirimkan invoice gaji periode ini.</p><p>Terima kasih.</p>"></textarea>
        <small class="text-secondary">Anda dapat menyisipkan variabel manual seperti nama, gaji pokok, tunjangan, potongan sesuai kebutuhan saat ini. Template dinamis dapat ditambahkan kemudian.</small>
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