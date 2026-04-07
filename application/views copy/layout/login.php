<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; BPKAD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="https://www.google.com/recaptcha/api.js"></script>

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/components.css">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <img src="<?php echo base_url() ?>assets2/template/images/login-logo.png" alt="logo" width="100%" class="shadow-light mb-5 mt-2">
                        <h4 class="text-dark font-weight-normal">Selamat Datang di <span class="font-weight-bold">Aplikasi Sertifikasi </span></h4>

                        <div class="alert-danger" role="alert">
                            <?php echo $this->session->userdata('pesan') ?>
                        </div>

                        <?php if (validation_errors()) {
                            echo validation_errors();
                        } ?>
                        <!-- <form method="POST" action="<?php echo base_url('auth/cek_login') ?>" class="needs-validation" novalidate=""> -->
                        <form method="POST" action="<?php echo base_url('auth/check_captcha') ?>" class="needs-validation" novalidate="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" type="username" placeholder="Masukkan Username" class="form-control" name="username" tabindex="1" required autofocus>
                                <div class="invalid-feedback">
                                    Please fill in your username
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <input id="password" type="password" class="form-control" placeholder="Masukkan Password " name="password" tabindex="2" required>
                                <div class="invalid-feedback">
                                    please fill in your password
                                </div>
                            </div>


                            <span id="captcha-img" class="captcha-img">
                                <?php echo $image; ?>
                            </span>
                            <button id="btn_cap" type="button" class="btn btn-primary btn-lg btn-icon icon-right"> Coba Kode Lain</button>
                            <br>
                            <br>
                            <input type="text" class="form-control" name="captcha" placeholder="Masukkan Captcha">
                            <br>
                            <br>

                            <div class="form-group text-right">
                                <!-- <a href="<?php echo base_url('auth/lupa_password') ?>" class="float-left mt-3">
                                    Forgot Password?
                                </a> -->
                                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="<?php echo base_url() ?>assets/assets/img/unsplash/login-bg.jpg">
                    <h2 style="margin-top: 50px;"> Sertifikasi BPKAD 2022</h2>
                    <p> Pemerintah Kota Surabaya</h2>
                        <img src="<?php echo base_url() ?>assets2/template/images/login.png" width="100%" height="600px"> </img>
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <!-- <h1 class="mb-2 display-4 font-weight-bold">Good Morning</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Bali, Indonesia</h5> -->
                            </div>
                            Photo by <a class="text-light bb" target="_blank" href="https://unsplash.com/photos/a8lTjWJJgLA">Justin Kauffman</a> on <a class="text-light bb" target="_blank" href="https://unsplash.com">Unsplash</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->

    <script>
        $(document).ready(function() {
            buat()
        });

        function buat() {
            $("#btn_cap").click(function() {
                $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "<?php echo base_url('auth/reload_captcha') ?>",
                    success: function(data) {
                        $('#captcha-img').html(data);
                    }
                });
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <!-- <script src="<?php echo base_url() ?>assets/js/stisla.js"></script>

    <script src="<?php echo base_url() ?>assets/js/scripts.js"></script>
    <script src="<?php echo base_url() ?>assets/js/custom.js"></script> -->

    <!-- Page Specific JS File -->

</body>

</html>