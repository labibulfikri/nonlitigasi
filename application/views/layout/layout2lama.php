<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-NONLIT</title>
    <!-- base:css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets2/template/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets2/template/vendors/base/vendor.bundle.base.css">

    <link rel="stylesheet" href="<?= base_url() ?>assets/assets2/datatable/css/datatables.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/assets2/datatable/css/datatables.min.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets2/template/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets2/template/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <script src="<?php echo base_url() ?>assets/jquery.min.js"></script>
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.css" />
    <!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->
    <!-- <script src="<?php echo base_url() ?>assets/ckeditor/ckeditor.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>assets/ckeditor/config.js"></script> -->
    <!-- <script src="https://esurat.surabaya.go.id/assets/vendors/ckeditor/ckeditor.js"></script> -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->
    <!-- <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script> -->
    <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>

    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Control Search CSS -->
    <!-- Leaflet Control Geocoder CSS -->


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.css" />
    <!-- JS Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Control Search JS -->
    <script src="https://unpkg.com/leaflet-control-search/dist/leaflet-search.min.js"></script>

    <!-- Leaflet Control Search CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.js"></script>
    <!-- CSS Leaflet -->
    <!-- Tambahkan JS Leaflet Control Search -->

    <!-- JS Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- plugins:js -->
    <script src="<?= base_url() ?>assets/assets2/datatable/js/datatable/datatables.js"></script>
    <script src="<?= base_url() ?>assets/assets2/datatable/js/datatable/datatables.min.js"></script>
    <!-- <script src="<?= base_url() ?>assets/assets2/js/datatable/bootstrap.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script> -->


    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets2/template/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/assets2/template/images/logononlit.png" />

</head>

<body>
    <div class="container-scroller">
        <div class="horizontal-menu">
            <?php $this->load->view($navbar2); ?>
            <?php $this->load->view($navbar_bawah); ?>

        </div>
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php $this->load->view($content) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view($footer) ?>
</body>



<script>
    function setActive(element) {
        // Menghapus kelas 'active' dari semua link
        var links = document.getElementsByClassName('nav-link');
        for (var i = 0; i < links.length; i++) {
            links[i].classList.remove('active');
        }
        // Menambahkan kelas 'active' ke link yang diklik
        element.classList.add('active');
    }
</script>

<script>
    function setActiveMenu(element) {
        // Menghapus kelas 'active' dari semua link
        var links = document.getElementsByClassName('list-group-item');
        for (var i = 0; i < links.length; i++) {
            links[i].classList.remove('active');
        }
        // Menambahkan kelas 'active' ke link yang diklik
        element.classList.add('active');
    }
</script>
<script>
    function setActiveMenuKronologi(element) {
        // Menghapus kelas 'active' dari semua link
        var links = document.getElementsByClassName('item-kronology');
        for (var i = 0; i < links.length; i++) {
            links[i].classList.remove('active');
        }
        // Menambahkan kelas 'active' ke link yang diklik
        element.classList.add('active');
    }
</script>

<!-- base:js -->
<script src="<?php echo base_url() ?>assets/assets2/template/vendors/base/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo base_url() ?>assets/assets2/template/js/template.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="<?php echo base_url() ?>assets/assets2/template/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/assets2/template/vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="<?php echo base_url() ?>assets/assets2/template/js/file-upload.js"></script>
<script src="<?php echo base_url() ?>assets/assets2/template/js/typeahead.js"></script>
<script src="<?php echo base_url() ?>assets/assets2/template/js/select2.js"></script>
<!-- End custom js for this page-->

</html>