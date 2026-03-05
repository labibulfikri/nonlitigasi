<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets2/logo.gif" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets2/login/css/main.css">
    <!-- <script src="https://www.google.com/recaptcha/enterprise.js?render=6LcpLlMpAAAAAH97ZFW-Yu3usPI_Pky-4GCKGhse"></script> -->

    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <h1 class="login100-form-title" style="font-size: 35px; padding: 10px;"> ASSIST</h1>
                <span class="login100-form-title">
                    Selamat Datang di Aplikasi Sertifikasi 2024
                </span>
                <div class="login100-pic js-tilt" data-tilt>
                    <!-- <img src="<?php echo base_url() ?>assets2/template/images/login-logo.png" /> -->
                    <img src="<?php echo base_url() ?>assets2/login/images/img-01.png" />
                </div>

                <form method="POST" action="<?php echo base_url('auth/cek_login') ?>" class="login100-form validate-form">

                    <div class="alert-danger" role="alert">
                        <?php echo $this->session->userdata('pesan') ?>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">

                        <?= crsf() ?>
                        <input class="input100" type="text" name="username" placeholder="User Name ">


                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="row">

                            <div class="g-recaptcha" data-sitekey="6LdglnMpAAAAANb3RfyGMN4mySsvwrMcvUowccEl" data-action="LOGIN"></div>
                            <br />

                        </div>
                    </div>



                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>



                    <!-- <div class="text-center p-t-12">
                        <span class="txt1">
                            Forgot
                        </span>
                        <a class="txt2" href="#">
                            Username / Password?
                        </a>
                    </div> -->

                    <div class="text-center p-t-136">
                        <!-- <a class="txt2" href="#">
                            Create your Account
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="<?php echo base_url() ?>assets2/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() ?>assets2/login/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo base_url() ?>assets2/login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() ?>assets2/login/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() ?>assets2/login/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() ?>assets2/login/js/main.js"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>


    <!-- <script>
        function onClick(e) {
            e.preventDefault();
            grecaptcha.enterprise.ready(async () => {
                const token = await grecaptcha.enterprise.execute('6LcpLlMpAAAAAH97ZFW-Yu3usPI_Pky-4GCKGhse', {
                    action: 'LOGIN'
                });
            });
        }
    </script>

     Replace the variables below. 
    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script> -->

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



</body>

</html>