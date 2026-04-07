<br />
<br />
<div class="container" style="margin-bottom: 100px;">
    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-8">
                    <?php $this->load->view($peta) ?>
                </div>
                <div class="col-md-4">
                    <h5 style="font-weight: bold;"> DETAIL</h5>
                    <?php $this->load->view($tab) ?>
                </div>
            </div>
            <div class="container">
                <nav class="nav nav-pills flex-column flex-sm-row ">
                    <a class="btn-rounded btn-primary flex-sm-fill text-sm-center nav-link " href="<?php echo base_url('nonlit/detail/' . $id) ?>">Detail</a>
                    <a class="btn-primary flex-sm-fill text-sm-center nav-link active" href="<?php echo base_url('nonlit/tab_kronologi/' . $id) ?>">Berkas Pendukung</a>
                </nav>
            </div>

            <div class="container">
                <div class="mb-20">

                    <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#modal_tambah_lampiran">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <hr />
                </div>
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col-md-3">
                        <ul class="list-group" id="menu_berkas">
                            <h4 class="card-title"> Daftar Berkas Lampiran </h4>
                            <?= crsf_ajax() ?>
                            <?php foreach ($lampiran as $key) { ?>
                                <li class="list-group-item item-kronology" onclick="setActiveMenuKronologi(this)" id="<?php echo $key->id ?>">
                                    <?php echo $key->judul_berkas ?> </span>
                                </li>
                            <?php } ?>

                        </ul>

                    </div>
                    <div class="col-md-9">


                        <h2> Lampiran Dokumen </h2>
                        <div id="content_lampiran">
                            <div class="col-md-12" style="justify-content: center; align-items: center; text-align: center;">

                                <h4 class="text-center"> Klik salah satu berkas lampiran untuk melihat berkas !</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<br />
<br />


<!-- MODAL tambah data MASTER -->
<div class="modal fade" id="modal_tambah_lampiran" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> TAMBAH LAMPIRAN </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('nonlit/upload_berkas_lampiran'); ?>" method="post" enctype="multipart/form-data">

                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Judul Berkas <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="id_nonlit_lampiran" hidden value="<?php echo $id ?>" name="id_nonlit">
                        <input type="text" class="form-control" required id="judul_berkas" name="judul_berkas">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file" name="file" id="nama_berkas">
                    </div>
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- MODAL edit data MASTER -->
<div class="modal fade" id="modal_edit_lampiran" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> EDIT DATA </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('nonlit/update_berkas_lampiran') ?>" method="POST" enctype="multipart/form-data">
                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Judul </label>
                        <input type="text" class="form-control" required name="id_nonlit" id="edit_id_nonlit_berkas">
                        <input type="text" class="form-control" required name="id" id="edit_id_det_berkas">
                        <input type="text" class="form-control" required name="judul_berkas" id="edit_judul_berkas">
                    </div>

                    <br>
                    <div class="form-group">
                        <label> Upload </label>
                        <iframe id="edit_lampiran" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen=""></iframe>
                        <input type="text" class="form-control" id="edit_lampiran_old" name="old_image" placeholder="File">
                        <input type="file" class="form-control" name="new_image" placeholder="File">


                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btnCloseDetLampiran">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Ketika salah satu menu di klik
        var token = $('#token').val();
        $('#menu_berkas li').click(function() {
            var id = $(this).attr('id'); // Dapatkan ID dari menu yang diklik

            // Lakukan permintaan AJAX ke controller untuk mendapatkan konten yang sesuai
            $.ajax({
                url: '<?= base_url('nonlit/get_content_berkas'); ?>', // Sesuaikan dengan URL controller Anda
                type: 'POST',
                data: {
                    id: id,
                    token: token
                },
                success: function(response) {
                    // Perbarui konten dengan respons yang diterima dari controller
                    // $('#content').html(response);
                    $('#content_lampiran').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>


<script>
    $(document).on('click', '#btnEditLampiran', function(element) {
        var id_nonlit = $(this).data("idnonlit");
        var id = $(this).data("id");
        var nama_berkas = $(this).data("nama_berkas");
        var judul_berkas = $(this).data("judul_berkas");

        $('#edit_id_nonlit_berkas').val(id_nonlit);
        $('#edit_id_det_berkas').val(id);
        $('#edit_judul_berkas').val(judul_berkas);
        $('#edit_lampiran_old').val(nama_berkas);

        if (nama_berkas == null || !nama_berkas) {
            document.getElementById('edit_lampiran').style.display = "none";
        } else {
            document.getElementById('edit_lampiran').style.display = "block";
            $('#edit_lampiran').attr('src', "<?= base_url() ?>/assets/berkas_lampiran/" + nama_berkas);
        }



        $('#modal_edit_lampiran').appendTo("body").modal({
            backdrop: 'static'
        })

    });
</script>
<script>
    $(document).on('click', '#btnCloseDetLampiran', function() {

        $('#edit_id_nonlit_berkas').val('');
        $('#edit_id_det_berkas').val('');
        $('#edit_judul_berkas').val('');
        $('#edit_lampiran_old').val('');
        document.getElementById('edit_lampiran').style.display = "none";

        $('#modal_edit_lampiran').modal('hide');
    });
</script>


<script>
    //fungsi delete
    $(document).on('click', '.hapus_lampiran', function() {
        // var id_aset = $(this).attr("id");

        var id = $(this).attr("id");
        var id_nonlit = $(this).data("idnonlit");
        var token = $('#token').val();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Anda ingin Menghapus Data Lampiran ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ya',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo base_url(); ?>nonlit/hapus_lampiran",
                    method: "POST",
                    onBeforeOpen: function() {
                        Swal.fire({
                            title: 'Menunggu',
                            html: 'Memproses data',
                            onOpen: () => {
                                swal.showLoading()
                            }
                        })
                    },
                    data: {
                        id: id,
                        id_nonlit: id_nonlit,
                        token: token,
                    },
                    success: function(data) {
                        Swal.fire(
                            'Berhasil',
                            'Berhasil Menghapus Data',
                            'success'
                        )
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'Batal',
                    'Anda membatalkan penghapusan',
                    'error'
                )
            }
        })
    });
</script>