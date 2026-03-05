<nav class="navbar top-navbar col-lg-12 col-12 p-0">
    <!-- <nav class="bottom-navbar"> -->
    <div class="container-fluid">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
            <ul class="navbar-nav navbar-nav-left">
                <li class="nav-item ms-0 me-5 d-lg-flex d-none">
                    <!-- <a href="#" class="nav-link horizontal-nav-left-menu"><i class="mdi mdi-format-list-bulleted"></i></a> -->
                    <a class="navbar-brand brand-logo" href="<?php echo base_url('home') ?>">
                        <img src="<?php echo base_url() ?>assets/assets2/template/images/logononlit2.png" alt="logo" />
                    </a>
                    <!-- <a class="navbar-brand brand-logo-mini" href="<?php echo base_url() ?>assets/assets2/template/index.html"><img src="<?php echo base_url() ?>assets/assets2/template/images/logo-mini.svg" alt="logo" /></a> -->
                </li>


                <!-- <li class="nav-item dropdown">
                    <a href="#" class="nav-link count-indicator "><i class="mdi mdi-message-reply-text"></i></a>
                </li> -->

            </ul>
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="<?php echo base_url('home') ?>">
                    <!-- <img src="<?php echo base_url() ?>assets/assets2/template/images/logo.svg" alt="logo" />-->
                    <!-- <img src="<?php echo base_url() ?>assets/assets2/template/images/nonlit.png" alt="logo" /> -->
                </a>
                <!-- <a class="navbar-brand brand-logo-mini" href="<?php echo base_url() ?>assets/assets2/template/index.html"> -->
                <!-- <img src="<?php echo base_url() ?>assets/assets2/template/images/logo-mini.svg" alt="logo" /> -->
                <!-- <img src="<?php echo base_url() ?>assets/assets2/template/images/nonlit.png" alt="logo" /> -->
                </a>
            </div>
            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <span class="nav-profile-name"><?= $this->session->userdata('username') ?></span>
                        <span class="online-status"></span>
                        <img src="<?php echo base_url() ?>assets/assets2/template/images/faces/face28.png" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <!-- <a class="dropdown-item">
                            <i class="mdi mdi-settings text-primary"></i>
                            Settings
                        </a> -->
                        <a class="dropdown-item" href="<?php echo base_url('auth/logout') ?>">
                            <i class="mdi mdi-logout text-primary"></i>
                            Logout
                        </a>
                        <a class="dropdown-item" href="<?php echo base_url() ?>auth/edit_pass/<?php echo $this->session->userdata('id') ?>">
                            <i class="mdi mdi-logout text-primary"></i>
                            Ganti Password
                        </a>


                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </div>
</nav>