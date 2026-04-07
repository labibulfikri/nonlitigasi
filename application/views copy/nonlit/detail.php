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
        </div>
        <div class="container ">
            <br />
            <br />
            <nav class="nav nav-pills flex-column flex-sm-row ">
                <a class="btn-rounded btn-primary flex-sm-fill text-sm-center nav-link active" href="<?php echo base_url('nonlit/detail/' . $id) ?>">Detail</a>
                <a class="btn-primary flex-sm-fill text-sm-center nav-link" href="<?php echo base_url('nonlit/tab_kronologi/' . $id) ?>">Berkas Pendukung</a>
            </nav>
        </div>
        <div class="container">
            <div class="mb-20">

                <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#modal_tambah_rapat">
                    <i class="mdi mdi-plus"></i>
                </button>
                <hr />
            </div>
            <div class="row" style="margin-bottom: 100px;">
                <div class="col-md-3">
                    <ul class="list-group" id="menu">
                        <h4 class="card-title"> History Rapat </h4>
                        <?= crsf_ajax() ?>
                        <?php foreach ($det as $key) { ?>
                            <li class="list-group-item" onclick="setActiveMenu(this)" id="<?php echo $key->id ?>">
                                <?php echo $key->judul_rapat ?> - <span> <?php echo $key->tgl_rapat ?> </span>
                            </li>
                        <?php } ?>

                    </ul>

                </div>
                <div class="col-md-9">


                    <h2 class="font-bold"> Detail Non Litigasi</h2>
                    <div id="content">
                        <div class="col-md-12" style="justify-content: center; align-items: center; text-align: center;">

                            <h4 class="text-center"> Klik Salah Satu History Rapat untuk melihat detail </h4>
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
<div class="modal fade" id="modal_tambah_rapat" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel"> TAMBAH DATA RAPAT </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('nonlit/upload_berkas'); ?>" method="post" enctype="multipart/form-data">

                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Tanggal <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required id="id_nonlit" hidden value="<?php echo $id ?>" name="id_nonlit">
                        <input type="date" class="form-control" required id="tgl_rapat" name="tgl_rapat">
                    </div>
                    <div class="form-group">
                        <label>Acara<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul_rapat" name="judul_rapat">
                    </div>

                    <div class="form-group">
                        <label> Kesimpulan <span class="text-danger">*</span> </label>
                        <textarea name="kesimpulan" id="kesimpulan" class="ckeditor"></textarea>
                    </div>
                    <hr />
                    <input type="file" name="file" id="file" class="form-control">

                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCloseTambah">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- MODAL edit data MASTER -->
<div class="modal fade" id="modal_edit_nonlit_det" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> EDIT DATA </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('nonlit/update_nonlit_det') ?>" method="POST" enctype="multipart/form-data">
                <?= crsf() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Tanggal Rapat </label>
                        <input type="text" hidden class="form-control" required name="id_nonlit" id="edit_id_nonlit">
                        <input type="text" hidden class="form-control" required name="id" id="edit_id_det">
                        <input type="date" class="form-control" required name="tgl_rapat" id="edit_tgl_rapat">
                    </div>
                    <div class="form-group">
                        <label>Judul Rapat</label>
                        <input type="text" class="form-control" required name="judul_rapat" id="edit_judul_rapat">
                    </div>

                    <div class="form-group">
                        <label> Kesimpulan </label>
                        <textarea class="form-control ckeditor" name="kesimpulan" id="edit_kesimpulan"> </textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label> Upload </label>
                        <iframe id="edit_berkas" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen=""></iframe>
                        <input type="text" hidden class="form-control" id="edit_berkas_old" name="old_image" placeholder="File">
                        <input type="file" class="form-control" name="new_image" placeholder="File">


                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btnCloseDet">Close</button>
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
        $('#menu li').click(function() {
            var id = $(this).attr('id'); // Dapatkan ID dari menu yang diklik

            // Lakukan permintaan AJAX ke controller untuk mendapatkan konten yang sesuai
            $.ajax({
                url: '<?= base_url('nonlit/get_content'); ?>', // Sesuaikan dengan URL controller Anda
                type: 'POST',
                data: {
                    id: id,
                    token: token
                },
                success: function(response) {
                    // Perbarui konten dengan respons yang diterima dari controller
                    // $('#content').html(response);

                    $('#content').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>


<script>
    $(document).on('click', '#btnEditDet', function() {
        var id_nonlit = $(this).data("idnonlit");
        var id = $(this).data("id");
        var tgl_rapat = $(this).data("tglrapat");
        var judul_rapat = $(this).data("judulrapat");
        var kesimpulan = $(this).data("kesimpulan");
        var berkas = $(this).data("berkas");
        // Menggunakan CKEditor untuk mengatur data kesimpulan
        // Set data ke CKEditor

        if (CKEDITOR.instances['edit_kesimpulan']) {
            CKEDITOR.instances['edit_kesimpulan'].destroy(true);
        }
        CKEDITOR.replace('edit_kesimpulan');
        if (CKEDITOR.instances['edit_kesimpulan']) {
            CKEDITOR.instances['edit_kesimpulan'].setData(kesimpulan);
        } else {
            CKEDITOR.replace('edit_kesimpulan', {
                on: {
                    instanceReady: function() {
                        this.setData(kesimpulan);
                    }
                }
            });
        }

        $('#edit_id_nonlit').val(id_nonlit);
        $('#edit_id_det').val(id);
        $('#edit_tgl_rapat').val(tgl_rapat);
        $('#edit_judul_rapat').val(judul_rapat);
        // $('#edit_kesimpulan').val(kesimpulan);

        $('#edit_berkas_old').val(berkas);

        if (berkas == null || !berkas) {
            document.getElementById('edit_berkas').style.display = "none";
        } else {
            document.getElementById('edit_berkas').style.display = "block";
            $('#edit_berkas').attr('src', "<?= base_url() ?>/assets/berkas_nonlit/" + berkas);
        }



        $('#modal_edit_nonlit_det').appendTo("body").modal({
            backdrop: 'static'
        })

    });
</script>
<script>
    $(document).on('click', '#btnCloseDet', function() {

        $('#edit_id_nonlit').val('');
        $('#edit_id_det').val('');
        $('#edit_tgl_rapat').val('');
        $('#edit_judul_rapat').val('');
        $('#edit_kesimpulan').val('');
        $('#edit_berkas_old').val('');
        document.getElementById('edit_berkas').style.display = "none";

        $('#modal_edit_nonlit_det').modal('hide');
    });
</script>
<script>
    $(document).on('click', '#btnCloseTambah', function() {

        $('#tgl_rapat').val('');
        $('#judul_rapat').val('');
        $('#file').val('');
        if (CKEDITOR.instances['kesimpulan']) {
            CKEDITOR.instances['kesimpulan'].destroy(true);
        }
        CKEDITOR.replace('kesimpulan');
        $('#modal_tambah_rapat').modal('hide');
    });
</script>


<script>
    //fungsi delete
    //fungsi delete
    $(document).on('click', '.hapus_det', function() {
        // var id_aset = $(this).attr("id");

        var id = $(this).attr("id");
        var id_nonlit = $(this).data("idnonlit");
        var token = $('#token').val();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Anda ingin Menghapus Data Rapat ",
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
                    url: "<?php echo base_url(); ?>nonlit/hapus_det",
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


<script>
    // var map = L.map('map').fitWorld();

    // // Tambahkan layer peta dasar
    // let streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    // let satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    //     maxZoom: 30,
    //     subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    // });
    // // let satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
    // let basemapControl = {
    //     "Streets": streets,
    //     "Satellite": satellite,
    //     // "tes": tes,
    // };
    // L.control.layers(basemapControl).addTo(map);

    // map.locate({
    //     setView: true,
    //     maxZoom: 30
    // });

    // // var nama = $("[name=nama_gis]").val();
    // // var alamat = $("[name=alamat]").val();
    // // var kelurahan = $("[name=kelurahan]").val();
    // var latlngs = $("[name=polygon]").val();

    // // Edit polygon
    // if (latlngs != " ") {
    //     var drawnItems = new L.FeatureGroup();
    //     map.addLayer(drawnItems);

    //     var latlngs = JSON.parse(latlngs); // Pastikan data poligon sudah di-parse
    //     var polygon = L.polygon(latlngs, {
    //         color: 'red'
    //     }).addTo(drawnItems);
    //     // .bindPopup("Nama =" + nama + "<br /> Alamat = " + alamat + " <br /> Kelurahan =" + kelurahan).openPopup();
    //     setTimeout(function() {
    //         map.fitBounds(polygon.getBounds());
    //     }, 100);

    //     // Memusatkan peta pada polygon yang ada
    //     // map.fitBounds(polygon.getBounds());

    //     var drawControl = new L.Control.Draw({
    //         draw: {
    //             polyline: false,
    //             rectangle: false,
    //             circle: false,
    //             circlemarker: false,
    //             marker: false
    //         },
    //         edit: {
    //             featureGroup: drawnItems
    //         }
    //     });
    //     map.addControl(drawControl);

    //     map.on('draw:created', function(e) {
    //         console.log('created');
    //         var type = e.layerType,
    //             layer = e.layer;
    //         var latLang = layer.getLatLngs();
    //         $("[name=polygon]").val(JSON.stringify(latLang[0]));
    //         drawnItems.addLayer(layer);
    //     });

    //     map.on('draw:edited', function(e) {
    //         console.log('edited');
    //         var latLang = e.layers.getLayers()[0].getLatLngs()[0];
    //         $("[name=polygon]").val(JSON.stringify(latLang));
    //     });

    //     map.on('draw:deleted', function(e) {
    //         console.log('deleted');
    //         $("[name=polygon]").val("");
    //     });
    // } else {
    //     var drawnItems = new L.FeatureGroup();
    //     map.addLayer(drawnItems);
    //     var drawControl = new L.Control.Draw({
    //         draw: {
    //             polyline: false,
    //             rectangle: false,
    //             circle: false,
    //             circlemarker: false,
    //             marker: false
    //         },
    //         edit: {
    //             featureGroup: drawnItems
    //         }
    //     });

    //     map.addControl(drawControl);

    //     map.on('draw:created', function(e) {
    //         console.log('created');
    //         var type = e.layerType,
    //             layer = e.layer;
    //         var latLang = layer.getLatLngs();
    //         $("[name=polygon]").val(JSON.stringify(latLang[0]));
    //         drawnItems.addLayer(layer);
    //     });

    //     map.on('draw:edited', function(e) {
    //         console.log('edited');
    //         var latLang = e.layers.getLayers()[0].getLatLngs()[0];
    //         $("[name=polygon]").val(JSON.stringify(latLang));
    //     });

    //     map.on('draw:deleted', function(e) {
    //         console.log('deleted');
    //         $("[name=polygon]").val("");
    //     });
    // }
</script>