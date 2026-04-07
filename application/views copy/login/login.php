<!doctype html>
<html lang="en">

<head>
    <title>Login E-Nonlit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/login/css/style.css">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <!-- <h2 class="heading-section"> Sistem Informasi Non- Litigasi Kota Surabaya </h2> -->
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="margin-top: 150px;">

                            <img src="<?php echo base_url() ?>assets/logononlit.png" style="display: block;margin-left: auto;  margin-right: auto;width: 50%; height: 250px; width: 400px;" />

                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4"> Login</h3>
                                </div>
                                <div class="w-100">
                                    <p class="social-media d-flex justify-content-end">
                                        <!-- <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                                        <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a> -->
                                    </p>
                                </div>
                            </div>
                            <form method="post" action="<?php echo base_url('auth/check_captcha') ?>" class="signin-form">

                                <div class="alert-danger" role="alert">
                                    <?php echo $this->session->userdata('pesan') ?>
                                </div>

                                <?= crsf() ?>
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                                <!-- <div class="col-sm-12">
                                    <div class="row">

                                        <div class="g-recaptcha" data-sitekey="6LdglnMpAAAAANb3RfyGMN4mySsvwrMcvUowccEl" data-action="LOGIN"></div>
                                        <br />

                                    </div>
                                </div> -->
                                <span id="captcha-img" class="captcha-img">
                                    <?php echo $image; ?>
                                </span>
                                <button id="btn_cap" type="button" class="btn btn-primary btn-lg btn-icon icon-right"> Coba Kode Lain</button>
                                <br>
                                <br>
                                <input type="text" class="form-control" name="captcha" placeholder="Masukkan Captcha">
                                <br>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login </button>
                                </div>

                            </form>
                            <!-- <p class="text-center">Not a member? <a data-toggle="tab" href="#signup">Sign Up</a></p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?php echo base_url() ?>assets/login/js/jquery.min.js"></script>
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
    <!-- <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script> -->
    <script src="<?php echo base_url() ?>assets/login/js/popper.js"></script>
    <script src="<?php echo base_url() ?>assets/login/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/login/js/main.js"></script>

    <!-- <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script> -->

</body>

</html>