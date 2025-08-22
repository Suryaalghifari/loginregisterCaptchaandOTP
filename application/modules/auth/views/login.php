<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Warkop Abah</title>

  <!-- Fonts & Icons -->
  <link href="<?= base_url('assets/vendor/fonts/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- SB Admin 2 CSS -->
  <link href="<?= base_url('assets/vendor/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
</head>
<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Warkop Abah</h1>
                  </div>

                  <?php if ($this->session->flashdata('message')): ?>
                    <?= $this->session->flashdata('message'); ?>
                  <?php endif; ?>

                  <form class="user" method="post" action="<?= site_url('auth/login'); ?>">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user"
                             id="email" name="email" placeholder="Enter Email Address..."
                             value="<?= set_value('email'); ?>">
                      <?= form_error('email','<small class="text-danger pl-3">', '</small>'); ?>
                    </div>

                    <div class="form-group">
                      <input type="password" class="form-control form-control-user"
                             id="password" name="password" placeholder="Password">
                      <?= form_error('password','<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                    <?php if (!empty($captcha_img)): ?>
                      <div class="form-group text-center">
                        <div class="mb-2">
                          <?= $captcha_img ?>  <!-- <img src=".../captcha/xxx.jpg"> -->
                        </div>
                        <input type="text" class="form-control form-control-user"
                              name="captcha" placeholder="Masukkan angka pada gambar" required>
                      </div>
                    <?php endif; ?>


                    <button class="btn btn-primary btn-user btn-block" type="submit">
                      Login
                    </button>
                  </form>

                  <div class="text-center mt-3">
                    <a class="small" href="<?= site_url('auth/register'); ?>">Create an Account!</a>
                  </div>
                </div><!-- /.p-5 -->
              </div>
            </div><!-- /.row -->
          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
  <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
</body>
</html>
