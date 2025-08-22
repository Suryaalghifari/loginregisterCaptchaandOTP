<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- pakai sb-admin-2 kalau sudah ada; kalau belum, cukup style simpel -->
  <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
  <style>
    body{background:#f6f7fb}
    .wrap{max-width:720px;margin:6vh auto}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card shadow">
      <div class="card-body">
        <h3 class="mb-4">Dashboard</h3>

        <div class="table-responsive">
          <table class="table table-bordered mb-4">
            <tbody>
              <tr><th style="width:160px">User ID</th><td><?= (int)$id ?></td></tr>
              <tr><th>Nama</th><td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td></tr>
              <tr><th>Email</th><td><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td></tr>
              <tr><th>Role ID</th><td><?= (int)$role_id ?></td></tr>
              <?php if (!empty($role)): ?>
                <tr><th>Role</th><td><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <a href="<?= site_url('keluar'); ?>" class="btn btn-danger">
          Logout
        </a>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
