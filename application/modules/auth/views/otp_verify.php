<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Verifikasi OTP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?= base_url('assets/vendor/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-6 mx-auto">
      <div class="card-body p-0">
        <div class="row"><div class="col-lg"><div class="p-5">
          <div class="text-center"><h1 class="h4 text-gray-900 mb-4">Masukkan Kode OTP</h1></div>

          <?= $this->session->flashdata('message'); ?>

          <form class="user" method="post" action="<?= site_url('auth/otp'); ?>">
            <div class="form-group">
              <input type="text" class="form-control form-control-user" name="code" maxlength="6" placeholder="6-digit OTP" required>
              <?= form_error('code','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <button class="btn btn-primary btn-user btn-block" type="submit">Verifikasi</button>
          </form>

          <div class="text-center mt-3">
            <a class="small" href="<?= site_url('auth/otp/resend'); ?>">Kirim ulang OTP</a>
          </div>
        </div></div></div>
      </div>
    </div>
  </div>
  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
