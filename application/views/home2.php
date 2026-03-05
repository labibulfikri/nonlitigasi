<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome, <?= $this->session->userdata('username') ?></h3>
                <h6 class="font-weight-normal mb-0">Sistem Informasi Sertifikasi BPKAD Pemerintah Kota Surabaya</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">

                        <i class="mdi mdi-calendar"></i> <?= $h ?>, <?= date('d-M-Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>