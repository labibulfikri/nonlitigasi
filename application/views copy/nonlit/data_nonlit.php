<style>
    .list-group {
        max-height: 200px;
        /* Set a maximum height */
        overflow-y: auto;
        /* Enable vertical scrolling */
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
        padding: 0;
        /* Remove default padding */
    }

    .list-group-item {
        border-bottom: 1px solid #ddd;
        padding: 10px;
        list-style: none;
        /* Remove default list style */
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
<div class="container">
    <br />
    <br />
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_tambah">
        <i class="mdi mdi-plus"></i>
    </button>
</div>
<hr />

<div class="container">

    <h3 class="font-bold"> Daftar Non Litigasi BPKAD Kota Surabaya </h3>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                </p>
                <?= crsf_ajax() ?>
                <div class="table-responsive">
                    <table class="table table-striped nonlit">
                        <thead>
                            <tr>
                                <th width="10%"> No </th>
                                <th width="30%"> Permohonan Nonlit </th>
                                <th width="30%"> PIC </th>
                                <th width="10%"> Tanggal </th>
                                <th width="10%"> Bidang</th>
                                <th width="10%"> Status</th>
                                <th width="10%"> Keterangan</th>
                                <th width="10%"> Aktifitas</th>
                                <th width="20%"> Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- MODAL tambah data MASTER -->
<div class="modal fade" id="modal_tambah" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> TAMBAH DATA NONLIT </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form action="<?= base_url('nonlit/tambah_data_nonlit') ?>" method="POST"> -->
            <form id="formSave">
                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Permohonan Non-Litigasi <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="permohonan_nonlit" name="permohonan_nonlit">
                    </div>

                    <div class="form-group">
                        <label> Nomor Register <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="register_baru" name="register_baru">
                    </div>
                    <div class="form-group">
                        <label> Alamat <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="alamat" name="alamat">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Non-litigasi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_nonlit" name="tgl_nonlit">
                    </div>
                    <div class="form-group">
                        <label> Luas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="luas" name="luas">
                    </div>

                    <div class="form-group">
                        <label> Team Nonlit <span class="text-danger">*</span> </label>
                        <select class="form-control" name="team_nonlit" id="team_nonlit" required>
                            <option value="" selected disabled> Silahkan Pilih </option>
                            <option value="kejati"> Kejaksaan Tinggi Jawa Timur </option>
                            <option value="kejari_sby"> Kejaksaan Negeri Surabaya </option>
                            <option value="kejari_perak"> Kejaksaan Negeri Tanjung Perak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label> Bidang <span class="text-danger">*</span></label>
                        <select class="form-control" name="bidang" id="bidang" required>
                            <option value="" selected disabled> Silahkan Pilih </option>
                            <option value="p3bmd"> P3BMD </option>
                            <option value="ppsbmd"> PPSBMD </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label> PIC <span class="text-danger">*</span></label>
                        <select class="form-control" name="pic" id="pic" required>
                            <option selected disabled> Silahkan Pilih </option>
                            <option value="cavita"> CAVITA </option>
                            <option value="rendy"> RENDY BAMBANG DWI PUTRA </option>
                            <option value="widi"> PATRIA WIDIANTO</option>
                            <option value="andi"> ANDI MARDIANTO </option>
                            <option value="elia"> ELIA </option>
                            <option value="qowi"> QOWI ZULFARHAD </option>
                            <option value="iqbal"> IQBAL </option>
                            <option value="denis"> DENNIS </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label> Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="status">
                            <option selected disabled> Silahkan Pilih </option>
                            <option value="proses"> Proses </option>
                            <option value="thl"> Tindakan Hukum Lain </option>
                            <option value="selesai"> Selesai </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Penyimpanan Rak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="penyimpanan_rak" name="penyimpanan_rak">
                    </div>


                    <div class="form-group">
                        <label> Keterangan </label>
                        <!-- <textarea class="form-control" name="keterangan" id="keterangan"></textarea> -->
                        <textarea name="keterangan" id="keterangan" class="ckeditor"></textarea>

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
<div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel"> EDIT DATA NONLIT </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form id="editForm" action="<?= base_url('nonlit/edit_data_nonlit') ?>" method="POST"> -->
            <form id="formUpdate">
                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Permohonan Non-Litigasi </label>
                        <input type="text" class="form-control" required name="id" hidden id="edit_id">
                        <input type="text" class="form-control" required name="permohonan_nonlit" id="edit_permohonan_nonlit">
                    </div>
                    <div class="form-group">
                        <label> ALAMAT </label>
                        <input type="text" class="form-control" required name="alamat" id="edit_alamat">
                    </div>
                    <div class="form-group">
                        <label> Luas </label>
                        <input type="text" class="form-control" required name="luas" id="edit_luas">
                    </div>
                    <div class="form-group">
                        <label> Nomor Register </label>
                        <input type="text" class="form-control" required name="register_baru" id="edit_register_baru">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Non-litigasi</label>
                        <input type="date" class="form-control" required name="tgl_nonlit" id="edit_tgl_nonlit">
                    </div>

                    <div class="form-group">
                        <label> Team Nonlit </label>
                        <select class="form-control" name="team_nonlit" id="edit_team_nonlit">
                            <option selected disabled value=""> Silahkan Pilih </option>
                            <option value="kejati"> Kejaksaan Tinggi Jawa Timur </option>
                            <option value="kejari_sby"> Kejaksaan Negeri Surabaya </option>
                            <option value="kejari_perak"> Kejaksaan Negeri Tanjung Perak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label> Bidang <span class="text-danger">*</span></label>
                        <select class="form-control" name="bidang" id="edit_bidang" required>
                            <option selected disabled value=""> Silahkan Pilih </option>
                            <option value="p3bmd"> P3BMD </option>
                            <option value="ppsbmd"> PPSBMD </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label> PIC <span class="text-danger">*</span></label>
                        <select class="form-control" name="pic" id="edit_pic" required>
                            <option selected disabled> Silahkan Pilih </option>
                            <option value="cavita"> CAVITA </option>
                            <option value="rendy"> RENDY BAMBANG DWI PUTRA </option>
                            <option value="widi"> PATRIA WIDIANTO</option>
                            <option value="andi"> ANDI MARDIANTO </option>
                            <option value="elia"> ELIA </option>
                            <option value="qowi"> QOWI ZULFARHAD </option>
                            <option value="iqbal"> IQBAL </option>
                            <option value="denis"> DENNIS </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label> Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="edit_status">
                            <option selected disabled> Silahkan Pilih </option>
                            <option value="proses"> Proses </option>
                            <option value="thl"> Tindakan Hukum Lain </option>
                            <option value="selesai"> Selesai </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Penyimpanan Rak</label>
                        <input type="text" class="form-control" required name="penyimpanan_rak" id="edit_penyimpanan_rak">
                    </div>

                    <div class="form-group">
                        <label> Keterangan </label>
                        <textarea class="ckeditor" name="keterangan" id="edit_keterangan"></textarea>

                    </div>
                    <br>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var token = $('#token').val();

    var dataAset = $('.nonlit').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],

        "ajax": {
            url: "<?php echo base_url() . 'nonlit/fetch_nonlit'; ?>",
            type: "POST",
            data: {
                token: token
            },
        },
        "columns": [{
                "data": "no"
            },
            {
                "data": "permohonan_nonlit"
            },
            {
                "data": "pic"
            },

            {
                "data": "tgl_nonlit"
            },

            {
                "data": "bidang"
            },
            {
                "data": "status"
            },
            {
                "data": "keterangan"
            },
            {
                "data": "updated_by"
            },
            {
                "data": "id"
            }
        ],





        // "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        // console.log(nRow);
        // console.log(aData['masalah']);
        // console.log(iDisplayIndex);
        // console.log(iDisplayIndexFull);
        //  if (aData['masalah'] == 0) {
        //      $('td', nRow).css('background-color', 'Red');
        //  } else if (aData[2] == "4") {
        //      $('td', nRow).css('background-color', 'Orange');
        //  }
        // },

    });
    // new $.fn.dataTable.FixedHeader(dataAset);
</script>
<!-- simpan data -->
<script>
    document.getElementById("formSave").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah pengiriman formulir

        // Validasi formulir
        var token = document.getElementById("token").value;
        var permohonan_nonlit = document.getElementById("permohonan_nonlit").value;
        var register_baru = document.getElementById("register_baru").value;
        var tgl_nonlit = document.getElementById("tgl_nonlit").value;
        var status = document.getElementById("status").value;
        var team_nonlit = document.getElementById("team_nonlit").value;
        var keterangan = document.getElementById("keterangan").value;
        var bidang = document.getElementById("bidang").value;
        var luas = document.getElementById("luas").value;
        var alamat = document.getElementById("alamat").value;
        var pic = document.getElementById("pic").value;
        var penyimpanan_rak = document.getElementById("penyimpanan_rak").value;


        // if (!permohonan_nonlit || !bidang || !tgl_nonlit || !team_nonlit || status) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'Harus mengisi semua field!'
        //     });
        //     return;
        // }

        // Tampilkan pesan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simpan data menggunakan AJAX
                simpanData(permohonan_nonlit, penyimpanan_rak, bidang, tgl_nonlit, status, team_nonlit, keterangan, token, register_baru, luas, alamat, pic);
            }
        });
    });

    // Fungsi untuk menyimpan data menggunakan AJAX
    function simpanData(permohonan_nonlit, penyimpanan_rak, bidang, tgl_nonlit, status, team_nonlit, keterangan, token, register_baru, luas, alamat, pic) {
        // Data yang akan dikirim
        var data = {
            permohonan_nonlit: permohonan_nonlit,
            bidang: bidang,
            register_baru: register_baru,
            tgl_nonlit: tgl_nonlit,
            status: status,
            team_nonlit: team_nonlit,
            keterangan: keterangan,
            luas: luas,
            alamat: alamat,
            pic: pic,
            penyimpanan_rak: penyimpanan_rak,
            token: token,
        };



        // Lakukan permintaan AJAX
        $.ajax({
            url: '<?php echo base_url('nonlit/tambah_data_nonlit') ?>',
            type: 'POST',
            // contentType: 'application/json',
            // data: data,
            data: {
                permohonan_nonlit: data.permohonan_nonlit,
                bidang: data.bidang,
                token: data.token,
                keterangan: data.keterangan,
                tgl_nonlit: data.tgl_nonlit,
                status: data.status,
                team_nonlit: data.team_nonlit,
                luas: data.luas,
                alamat: data.alamat,
                penyimpanan_rak: data.penyimpanan_rak,
                pic: data.pic,
                register_baru: data.register_baru
            },
            success: function(response) {
                // Tanggapi hasil dari server
                var result = JSON.parse(response);
                if (result.status === 'success') {

                    Swal.fire(
                        'Berhasil!',
                        result.message,
                        'success'
                    );
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // 2000 milidetik = 2 detik
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data!'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Tangani kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menyimpan data!'
                });
            }
        });
    }
</script>

<script>
    document.getElementById("formUpdate").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah pengiriman formulir

        // Validasi formulir
        var id = document.getElementById("edit_id").value;
        var token = document.getElementById("token").value;
        var alamat = document.getElementById("edit_alamat").value;
        var pic = document.getElementById("edit_pic").value;
        var luas = document.getElementById("edit_luas").value;
        var permohonan_nonlit = document.getElementById("edit_permohonan_nonlit").value;
        var bidang = document.getElementById("edit_bidang").value;
        var team_nonlit = document.getElementById("edit_team_nonlit").value;
        var tgl_nonlit = document.getElementById("edit_tgl_nonlit").value;
        var status = document.getElementById("edit_status").value;
        var register_baru = document.getElementById("edit_register_baru").value;
        var penyimpanan_rak = document.getElementById("edit_penyimpanan_rak").value;
        CKEDITOR.replace('edit_keterangan');
        var keterangan = CKEDITOR.instances.edit_keterangan.getData();

        // var keterangan = document.getElementById("edit_keterangan").value;

        // console.log(keterangan);
        // if (!permohonan_nonlit || !bidang || !tgl_nonlit || !team_nonlit || status) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'Harus mengisi semua field!'
        //     });
        //     return;
        // }

        // Tampilkan pesan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simpan data menggunakan AJAX
                updateData(id, penyimpanan_rak, permohonan_nonlit, bidang, tgl_nonlit, status, team_nonlit, keterangan, token, register_baru, luas, pic, alamat);
            }
        });
    });

    // Fungsi untuk menyimpan data menggunakan AJAX
    function updateData(id, penyimpanan_rak, permohonan_nonlit, bidang, tgl_nonlit, status, team_nonlit, keterangan, token, register_baru, luas, pic, alamat) {
        // Data yang akan dikirim
        var data = {
            id: id,
            permohonan_nonlit: permohonan_nonlit,
            bidang: bidang,
            tgl_nonlit: tgl_nonlit,
            status: status,
            team_nonlit: team_nonlit,
            penyimpanan_rak: penyimpanan_rak,
            register_baru: register_baru,
            keterangan: keterangan,
            luas: luas,
            pic: pic,
            alamat: alamat,
            token: token,
        };



        // Lakukan permintaan AJAX
        $.ajax({
            url: '<?php echo base_url('nonlit/update_data') ?>',
            type: 'POST',
            // contentType: 'application/json',
            // data: data,
            data: {
                id: data.id,
                permohonan_nonlit: data.permohonan_nonlit,
                bidang: data.bidang,
                token: data.token,
                keterangan: data.keterangan,
                tgl_nonlit: data.tgl_nonlit,
                status: data.status,
                register_baru: data.register_baru,
                luas: data.luas,
                pic: data.pic,
                alamat: data.alamat,
                penyimpanan_rak: data.penyimpanan_rak,
                team_nonlit: data.team_nonlit
            },
            success: function(response) {
                // Tanggapi hasil dari server
                var result = JSON.parse(response);
                if (result.status === 'success') {

                    Swal.fire(
                        'Berhasil!',
                        result.message,
                        'success'
                    );
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // 2000 milidetik = 2 detik
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data!'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Tangani kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menyimpan data!'
                });
            }
        });
    }
</script>
<script>
    $(document).on('click', '#editButton', function() {
        var id = $(this).data("id");
        var team = $(this).data("team");
        var status = $(this).data("status");
        var permohonan = $(this).data("permohonan");
        var alamat = $(this).data("alamat");
        var luas = $(this).data("luas");
        var penyimpanan_rak = $(this).data("penyimpananrak");
        var bidang = $(this).data("bidang");
        var tglnonlit = $(this).data("tglnonlit");
        var keterangan = $(this).data("keterangan");
        var register_baru = $(this).data("registerbaru");
        var pic = $(this).data("pic");

        if (team === null || team === "") {
            $('#edit_team_nonlit option[value="' + team + '"]').prop('selected', false);
        } else {

            $('#edit_team_nonlit option[value="' + team + '"]').prop('selected', true);
        }
        if (bidang === null || bidang === "") {
            $('#edit_bidang option[value="' + bidang + '"]').prop('selected', false);
        } else {

            $('#edit_bidang option[value="' + bidang + '"]').prop('selected', true);
        }
        if (pic === null || pic === "") {
            $('#edit_pic option[value="' + pic + '"]').prop('selected', false);
        } else {

            $('#edit_pic option[value="' + pic + '"]').prop('selected', true);
        }
        if (status === null || status === "") {
            $('#edit_status option[value="' + status + '"]').prop('selected', false);
        } else {

            $('#edit_status option[value="' + status + '"]').prop('selected', true);
        }

        $('#edit_id').val(id);
        $('#edit_register_baru').val(register_baru);
        $('#edit_pic').val(pic);
        $('#edit_tgl_nonlit').val(tglnonlit);
        $('#edit_alamat').val(alamat);
        $('#edit_luas').val(luas);
        $('#edit_penyimpanan_rak').val(penyimpanan_rak);
        $('#edit_permohonan_nonlit').val(permohonan);

        if (CKEDITOR.instances['edit_keterangan']) {
            CKEDITOR.instances['edit_keterangan'].destroy(true);
        }
        CKEDITOR.replace('edit_keterangan');
        if (CKEDITOR.instances['edit_keterangan']) {
            CKEDITOR.instances['edit_keterangan'].setData(keterangan);
        } else {
            CKEDITOR.replace('edit_keterangan', {
                on: {
                    instanceReady: function() {
                        this.setData(keterangan);
                    }
                }
            });
        }
        // $('#edit_keterangan').val(keterangan);

        $('#modal_edit').appendTo("body").modal({
            backdrop: 'static'
        })

    });
</script>


<script>
    //Event ketika foto dihapus
    $(document).on('click', '.tombol_hapus', function() {
        var id_nonlit = $(this).attr("id_nonlit");
        console.log(id_nonlit);
        // var token=a.token;
        $.ajax({
            type: "post",
            data: {
                id_nonlit: id_nonlit
            },
            url: "<?php echo base_url('nonlit/remove_nonlit') ?>",
            cache: false,
            dataType: 'json',
            success: function() {
                // data_berkas();
                console.log("data terhapus");
                alert('data berhasil dihapus');
                location.reload();

                // pilih_data();
                // $('#EditModal').modal('hide');
            },
            error: function() {
                console.log("Error");

            }
        });
    });
</script>