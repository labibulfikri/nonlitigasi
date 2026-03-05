<?php if ($this->session->userdata('role') == "superadmin") { ?>
    <div class="container">
        <br />
        <br />
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_tambah_user">
            <i class="mdi mdi-plus"></i>
        </button>
    </div>
    <hr />
<?php } ?>

<div class="container">

    <h3 class="font-bold"> Daftar Non Litigasi BPKAD Kota Surabaya </h3>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                </p>
                <?= crsf_ajax() ?>
                <div class="table-responsive">
                    <table class="table table-striped users">
                        <thead>
                            <tr>
                                <th width="10%"> No </th>
                                <th width="30%"> Username </th>
                                <th width="10%"> Role</th>
                                <th width="20%"> Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal tambah user -->



<!-- MODAL tambah data MASTER -->
<div class="modal fade" id="modal_tambah_user" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> TAMBAH DATA USER </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form action="<?= base_url('user/tambah_data_user') ?>" method="POST"> -->
            <form id="formTambahUser">
                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Username <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="username" name="username">
                        <small class="text-danger" id="error-username"></small>
                    </div>

                    <div class="form-group">
                        <label> Password <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="password" name="password">
                        <small class="text-danger" id="error-password"></small>>
                    </div>
                    <div class="form-group">
                        <label> password Confirm <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="password_confirm" name="password_confirm">
                        <small class="text-danger" id="error-password-confirm"></small>
                    </div>

                    <div class="form-group">
                        <label> Role <span class="text-danger">*</span> </label>
                        <select class="form-control" name="team_nonlit" id="team_nonlit" required>
                            <option value="" selected disabled> Silahkan Pilih </option>
                            <option value="superadmin"> Superadmin </option>
                            <option value="admin"> Admin </option>
                        </select>
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

<!-- end  -->


<!-- edit role -->

<!-- MODAL edit data MASTER -->
<div class="modal fade" id="modal_edit_role" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel"> EDIT ROLE USER </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form id="editForm" action="<?= base_url('users/edit_data_nonlit') ?>" method="POST"> -->
            <form id="formUpdateRole">
                <?= crsf() ?>
                <div class="modal-body">
                    <input type="text" readonly class="form-control" required id="edit_username_role" name="username">
                    <input type="hidden" readonly class="form-control" required id="edit_id_role" name="id">

                    <div class="form-group">
                        <label> ROLE </label>
                        <select class="form-control" name="role" id="edit_role">
                            <option selected disabled value=""> Silahkan Pilih </option>
                            <option value="superadmin"> SUPER ADMIN </option>
                            <option value="admin"> ADMIN </option>
                        </select>
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
<!-- edit role -->
<script>
    $('#formTambahUser').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= base_url('users/tambah_data_user'); ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status === 'gagal') {
                    // Tampilkan pesan error di masing-masing field
                    $('#error-username').text(response.errors.username);
                    $('#error-password').text(response.errors.password);
                    $('#error-password-confirm').text(response.errors.password_confirm);
                } else {
                    alert(response.message);
                    location.reload(); // Reload setelah berhasil
                }
            }
        });
    });
</script>
<script>
    var token = $('#token').val();

    var dataAset = $('.users').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],

        "ajax": {
            url: "<?php echo base_url() . 'users/fetch_users'; ?>",
            type: "POST",
            data: {
                token: token
            },
        },
        "columns": [{
                "data": "no"
            },
            {
                "data": "username"
            },
            {
                "data": "role"
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

<script>
    $(document).on('click', '#editButtonUser', function() {
        var id = $(this).data("id");
        var role = $(this).data("role");
        var username = $(this).data("username");


        if (role === null || role === "") {
            $('#edit_role option[value="' + role + '"]').prop('selected', false);
        } else {
            $('#edit_role option[value="' + role + '"]').prop('selected', true);
        }
        $('#edit_id_role').val(id);
        $('#edit_username_role').val(username);

        $('#modal_edit_role').appendTo("body").modal({
            backdrop: 'static'
        })

    });
</script>

<script>
    //Event ketika foto dihapus
    $(document).on('click', '.tombol_hapus_user', function() {
        var id_user = $(this).attr("id");

        $.ajax({
            type: "post",
            data: {
                id_user: id_user
            },
            url: "<?php echo base_url('users/remove_user') ?>",
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


<script>
    document.getElementById("formUpdateRole").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah pengiriman formulir

        // Validasi formulir
        var id = document.getElementById("edit_id_role").value;
        var token = document.getElementById("token").value;
        var username = document.getElementById("edit_username_role").value;
        var role = document.getElementById("edit_role").value;
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
                updateData(id, token, role);
            }
        });
    });

    // Fungsi untuk menyimpan data menggunakan AJAX
    function updateData(id, token, role) {
        // Data yang akan dikirim
        var data = {
            id: id,
            role: role,
            token: token,
        };



        // Lakukan permintaan AJAX
        $.ajax({
            url: '<?php echo base_url('users/update_data') ?>',
            type: 'POST',
            // contentType: 'application/json',
            // data: data,
            data: {
                id: data.id,
                role: data.role,
                token: data.token,

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