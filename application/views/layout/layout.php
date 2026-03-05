<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Non Litigasi</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/font-awesome/css/font-awesome.min.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/jquery-bar-rating/css-stars.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/vendors/font-awesome/css/font-awesome.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->

  <script src="<?php echo base_url() ?>assets/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.css" />

  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
  <!-- Layout styles -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/template2/dist/assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="<?php echo base_url() ?>assets/template2/dist/assets/images/favicon.png" />


  <!-- jQuery (WAJIB sebelum DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">
  <div class="container-scroller">

    <?php $this->load->view($sidebar) ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view($navbar) ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Content Wrapper -->
          <?php $this->load->view($content) ?>

        </div>

        <?php $this->load->view($footer) ?>


      </div>
    </div>
  </div>



  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- <script src="<?= base_url() ?>assets/template2/dist/assets/vendors/chart.js/Chart.min.js"></script> -->
  <!-- plugins:js -->
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/js/vendor.bundle.base.js"></script>

  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/chart.js/chart.umd.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/flot/jquery.flot.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/flot/jquery.flot.resize.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/flot/jquery.flot.categories.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/flot/jquery.flot.fillbetween.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/vendors/flot/jquery.flot.stack.js"></script>
  <!-- <script src="assets/js/jquery.cookie.js" type="text/javascript"></script> -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/off-canvas.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/misc.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/settings.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/todolist.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/hoverable-collapse.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/proBanner.js"></script>
  <script src="<?php echo base_url() ?>assets/template2/dist/assets/js/dashboard.js"></script>
  <!-- End custom js for this page -->
</body>

</html>