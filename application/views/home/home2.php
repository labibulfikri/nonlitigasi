<style>
    .hero {
        /* background-color: #f0f4f8; */
        /* Warna background lembut */
        padding: 80px 0;
        border-radius: 20px;
        /* Border radius pada container */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        /* Bayangan untuk efek card */
    }

    .hero-title {
        font-size: 48px;
        font-weight: bold;
        color: white;
        /* Warna teks */
    }

    .hero-description {
        font-size: 24px;
        color: white;
        /* Warna teks deskripsi */
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .hero-img {
        max-width: 70%;
        height: auto;
        align-items: center;
        justify-items: center;
        border-radius: 15px;
        /* Border radius pada gambar */
    }

    .btn-primary {
        background-color: #1e88e5;
        /* Warna biru yang menarik */
        border-color: #1e88e5;
    }

    .container-custom {
        background-color: #21D4FD;
        background-image: linear-gradient(19deg, #21D4FD 0%, #B721FF 100%);


        /* background-color: white; */
        /* background: linear-gradient(135deg, #1e88e5, #42a5f5); */
        /* Warna gradien */
        /* Background putih untuk container dalam */
        padding: 50px;
        border-radius: 20px;
        /* Border radius pada container dalam */
    }
</style>
</head>

<body>
    <div class="container">
        <br />
        <br />
        <h3 class="text-black" style="font-size: xx-large; font-weight: bold;">Welcome, <?= $this->session->userdata('username') ?></h3>
        <h3 class="font-weight-normal mb-0">Sistem Informasi Non-Litigasi BPKAD Pemerintah Kota Surabaya</h3>
        <br />
        <br />
    </div>

    <!-- <section class="hero text-center"> -->
    <div class="container container-custom">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="hero-title"> E-NONLIT</h1>
                <p class="hero-description">Sistem Informasi Non-Litigasi yang terdapat di Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya</p>
                <a href="<?php echo base_url('nonlit') ?>" class="btn btn-primary btn-lg">Klik Disini</a>
            </div>
            <div class="col-md-6">
                <center>

                    <img src="<?php echo base_url('assets/bpkad_ikon.png') ?>" alt="Hero Image" class="hero-img">
                </center>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 100 100" class="hero-svg">
                    <circle cx="50" cy="50" r="45" fill="#42a5f5" />
                    <rect x="30" y="30" width="40" height="40" fill="#1e88e5" />
                    <path d="M 10 80 Q 50 10, 90 80" stroke="#fff" stroke-width="5" fill="none" />
                </svg> -->
            </div>
        </div>
    </div>
    <!-- </section> -->
    <br />

    <div class="container">
        <div class="row">
            <div class="col-sm-12 flex-column d-flex stretch-card">
                <h3 class="text-black ">Jumlah Permohonan Nonlitigasi </h4>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-dark"> FILTER PENCARIAN By Tahun </h5>
                            <?= crsf_ajax(); ?>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <select class="form-control" id="nonlit_filter_bytahun">
                                        <?php
                                        $tahun = date("Y");
                                        $mulai = date('Y') - 10;
                                        for ($i = $mulai; $i < $mulai + 12; $i++) {
                                            $sel = $i == date('Y') ? ' selected="selected"' : '';
                                            echo '<option value="' . $i . '"' . $sel . '>' . $i . '</option>';
                                        }
                                        ?>
                                        <option value="all"> All </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select class="form-control" id="nonlit_status">
                                        <option value="" disabled selected> SILAHKAN PILIH </option>
                                        <option value="proses"> PROSES </option>
                                        <option value="selesai"> SELESAI </option>
                                        <option value="all"> ALL </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input hidden type="text" id="tahun" value="<?php echo $tahun ?>" />
                                    <input hidden type="text" id="status_nonlit" />
                                    <button type="button" name="filter" id="filter" class="form-control btn btn-primary btn-sm">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <br />
    <br />
    <div class="container ">
        <div class="row">

            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card  bg-facebook">

                    <div class="card-body" style="border-radius: 20px;">
                        <h2 class="font-weight-bold text-center text-white"> Total Permohonan </h2>
                        <h2 class="font-weight-bold text-center text-white" style="font-size: 100px;" id="totalpermohonan"> 0 </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />
    <br />

    <!-- MODAL edit data MASTER -->
    <div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel"> Detail </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <?= crsf_ajax() ?>
                    <table class="table " id="tabelDataModal">
                        <thead>
                            <tr>
                                <th width="10%"> No </th>
                                <th width="40%"> Permohonan Nonlit </th>
                                <th width="10%"> Tanggal </th>
                                <th width="10%"> Team</th>
                                <th width="10%"> Bidang</th>
                                <th width="10%"> Status</th>
                                <th width="10%"> Keterangan</th>
                                <th width="10%"> Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {


            fetchDataAndRenderChart();

            function fill_datatable(token = '', tahun = '', status = '') {

                var token = $('#token').val();
                let table = new DataTable('#tabelData', {
                    "responsive": true,
                    "serverSide": true,
                    "bProcessing": true,
                    "destroy": true,
                    "searching": true,
                    "paging": false,
                    "ajax": {
                        url: "<?php echo base_url() . 'home/fetch_nonlit_tahun'; ?>",
                        type: "POST",
                        data: {
                            tahun: tahun,
                            status: status,
                            token: token,
                        },
                    },
                    // ,
                    // dom: 'Bfrtip',
                    // buttons: [{
                    //     extend: 'pdfHtml5',
                    //     text: 'Print',
                    //     title: 'Laporan ',
                    //     // exportOptions: {
                    //     //     // This will ensure that all data is exported, not just the current page
                    //     //     modifier: {
                    //     //         page: 'all' // This tells the button to export all pages
                    //     //     }
                    //     // }
                    // }],

                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "tgl_nonlit"
                        },
                        {
                            "data": "team_nonlit"
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
                            "data": "btn"
                        }

                    ],
                    oLanguage: {
                        sProcessing: "<div id='loader'></div>"
                    }


                });
            }


            function modal_bar(token = '', tahun = '', status = '', jaksa = '') {


                var token = $('#token').val();
                let table = new DataTable('#tabelDataModal', {
                    "responsive": true,
                    "serverSide": true,
                    "bProcessing": true,
                    "destroy": true,
                    "searching": true,
                    "paging": true,
                    "ajax": {
                        url: "<?php echo base_url() . 'home/fetch_nonlit_bar'; ?>",
                        type: "POST",
                        data: {
                            tahun: tahun,
                            status: status,
                            jaksa: jaksa,
                            token: token,
                        },
                    },
                    // dom: 'Bfrtip',
                    // buttons: [{
                    //     extend: 'pdfHtml5',
                    //     text: 'Print',
                    //     title: 'Laporan ',
                    //     // exportOptions: {
                    //     //     // This will ensure that all data is exported, not just the current page
                    //     //     modifier: {
                    //     //         page: 'all' // This tells the button to export all pages
                    //     //     }
                    //     // }
                    // }],

                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "tgl_nonlit"
                        },
                        {
                            "data": "team_nonlit"
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
                            "data": "btn"
                        }

                    ],
                    oLanguage: {
                        sProcessing: "<div id='loader'></div>"
                    }


                });
            }

            function statusnya(token = '', tahun = '', status = '') {
                var token = $('#token').val();
                var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var status = $("#nonlit_status option:selected").val();

                $.ajax({
                    url: "<?php echo base_url(); ?>home/filter_bytahun",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        tahun: tahun,
                        status: status,
                        token: token
                    },

                    // dataType: 'json',
                    beforeSend: function() {
                        $("#loading").show().html("<img src='<?php echo base_url('assets2/loading.gif') ?>' height='80'>");
                    },
                    success: function(peta_all) {

                        // console.log(peta_all['kejariperak_selesai'].status);
                        $('#kejati_proses').text(peta_all['kejati_proses'].status);
                        $('#kejati_selesai').text(peta_all['kejati_selesai'].status);
                        $('#kejarisby_proses').text(peta_all['kejarisby_proses'].status);
                        $('#kejarisby_selesai').text(peta_all['kejarisby_selesai'].status);
                        $('#kejariperak_proses').text(peta_all['kejariperak_proses'].status);
                        $('#kejariperak_selesai').text(peta_all['kejariperak_selesai'].status);
                        $("#loading").hide();

                    },
                    error: function(request, error) {
                        alert("Request: " + JSON.stringify(request));
                    }
                });

            }
            var chartInstance = null;

            function fetchDataAndRenderChart(token = '', tahun = '', status = '') {
                var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var status = $("#nonlit_status option:selected").val();
                var token = $('#token').val();
                $.ajax({
                    url: '<?php echo base_url('home/filter_bytahun2') ?>', // URL API server-side
                    method: 'POST',
                    data: {
                        tahun: tahun,
                        token: token,
                        status: status,
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Parsing data yang diterima dari server
                        const data = response;
                        var total = 0;
                        total = parseInt(data['kejati_proses'].status) + parseInt(data['kejati_selesai'].status) + parseInt(data['kejarisby_proses'].status) + parseInt(data['kejarisby_selesai'].status) + parseInt(data['kejariperak_proses'].status) + parseInt(data['kejariperak_selesai'].status);

                        $("#totalpermohonan").text(total);
                        // Jika sudah ada chart sebelumnya, hancurkan chart
                        if (chartInstance) {
                            chartInstance.destroy();
                        }

                        // Mengisi data ke Chart.js
                        const ctx = document.getElementById('statusChart').getContext('2d');
                        const chartData = {
                            labels: ['KEJAKSAAN NEGERI PERAK', 'KEJAKSAAN NEGERI SURABAYA', 'KEJAKSAAN TINGGI JAWA TIMUR'],
                            datasets: [{
                                    label: 'Proses',
                                    data: [
                                        data['kejariperak_proses'].status,
                                        data['kejarisby_proses'].status,
                                        data['kejati_proses'].status,
                                    ],
                                    backgroundColor: 'rgba(4, 0, 255, 0.6)', // Warna biru untuk proses
                                    borderColor: 'rgba(4, 0, 255, 1)',
                                    borderWidth: 2
                                },
                                {
                                    label: 'Selesai',
                                    data: [
                                        data['kejariperak_selesai'].status,
                                        data['kejarisby_selesai'].status,
                                        data['kejati_selesai'].status,
                                    ],
                                    backgroundColor: 'rgba(165, 12, 248, 0.9)', // Warna hijau untuk selesai
                                    borderColor: 'rgba(165, 12, 248, 1)',
                                    borderWidth: 2
                                }
                            ]
                        };

                        const config = {
                            type: 'bar',
                            data: chartData,
                            options: {
                                indexAxis: 'y', // Horizontal bar chart
                                scales: {
                                    x: {
                                        beginAtZero: true
                                    }
                                },
                                onClick: function(event) {
                                    const activePoints = this.getElementsAtEventForMode(event, 'nearest', {
                                        intersect: true
                                    }, true);
                                    if (activePoints.length > 0) {
                                        const firstPoint = activePoints[0];
                                        const jaksa = this.data.labels[firstPoint.index];
                                        const status = this.data.datasets[firstPoint.datasetIndex].label;

                                        var jaksanya = jaksa;
                                        var statusnya = status;
                                        modal_bar(token, tahun, statusnya, jaksanya);
                                        // console.log(value);
                                        // const value = this.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];

                                        // Tampilkan data dalam modal
                                        // $('#modalContent').text('Data: ' + label + ', Status: ' + value);

                                        $('#chartModal').modal('show'); // Memunculkan modal Bootstrap
                                    }
                                }
                            }
                        };
                        // Buat chart baru
                        chartInstance = new Chart(ctx, config);
                    },
                    error: function(error) {
                        console.log("Error fetching data:", error);
                    }
                });
            }

            $('#filter').click(function() {
                var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var status = $("#nonlit_status option:selected").val();
                var token = $('#token').val();
                $('#tahun').val(tahun);
                $('#status_nonlit').val(status);

                if (token != '' || tahun != '' || status != '') {
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable(token, tahun, status);
                    statusnya(token, tahun, status);
                    fetchDataAndRenderChart(token, tahun, status);

                } else {
                    alert('Select Both filter option');
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable(token, tahun, status);
                    statusnya(token, tahun, status);
                    fetchDataAndRenderChart(token, tahun, status);
                }
                // new $.fn.dataTable.FixedHeader(dataAset);
                // $.fn.dataTable.ext.errMode = 'throw';
            });
        });
    </script>
    <script>
        function setActiveMenus(element) {
            // Menghapus kelas 'active' dari semua link
            var links = document.getElementsByClassName('card');
            for (var i = 0; i < links.length; i++) {
                links[i].classList.remove('bg-primary');
            }
            // Menambahkan kelas 'active' ke link yang diklik
            element.classList.add('bg-primary');

        }
    </script>
    <script>
        $(document).ready(function() {

            fill_datatable_kejati();

            function fill_datatable_kejati(token = '', tahun = '', jaksa = '') {
                var token = $('#token').val();
                var tahun = $('#tahun').val();

                let kejatiData = new DataTable('#kejatiStatus', {
                    "responsive": true,
                    "serverSide": true,
                    "bProcessing": true,
                    "destroy": true,
                    "searching": true,
                    "lengthMenu": [
                        [10, 50, 100],
                        [10, 50, 100]
                    ],

                    "ajax": {
                        url: "<?php echo base_url() . 'home/fetch_nonlit_kejati'; ?>",
                        type: "POST",
                        data: {
                            tahun: tahun,
                            token: token,
                            jaksa: jaksa,
                        },
                    },

                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "tgl_nonlit"
                        },
                        {
                            "data": "team_nonlit"
                        },
                        {
                            "data": "bidang"
                        },
                        {
                            "data": "status"
                        },
                        {
                            "data": "keterangan"
                        }

                    ],
                    oLanguage: {
                        sProcessing: "<div id='loader'></div>"
                    }


                });
            }



            $('.btn_status_kejati').click(function() {
                // var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var token = $('#token').val();
                var tahun = $('#tahun').val();
                var jaksa = $(this).data("jaksa");

                var token = $('#token').val();
                $('#tahun').val(tahun);

                if (token != '' || tahun != '') {
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejati(token, tahun, jaksa);

                } else {
                    alert('Select Both filter option');
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejati(token, tahun, jaksa);
                }
                // new $.fn.dataTable.FixedHeader(dataAset);
                // $.fn.dataTable.ext.errMode = 'throw';
            });
            $('#modal_kejati').appendTo("body").modal({
                backdrop: 'static'
            })
        });
    </script>



    <script>
        $(document).ready(function() {

            fill_datatable_kejari_sby();

            function fill_datatable_kejari_sby(token = '', tahun = '', jaksa = '') {
                var token = $('#token').val();
                var tahun = $('#tahun').val();

                let kejatiData = new DataTable('#kejariSbyStatus', {
                    "responsive": true,
                    "serverSide": true,
                    "bProcessing": true,
                    "destroy": true,
                    "searching": true,

                    "lengthMenu": [
                        [10, 50, 100],
                        [10, 50, 100]
                    ],

                    "ajax": {
                        url: "<?php echo base_url() . 'home/fetch_nonlit_kejati'; ?>",
                        type: "POST",
                        data: {
                            tahun: tahun,
                            token: token,
                            jaksa: jaksa,
                        },
                    },


                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "tgl_nonlit"
                        },
                        {
                            "data": "team_nonlit"
                        },
                        {
                            "data": "bidang"
                        },
                        {
                            "data": "status"
                        },
                        {
                            "data": "keterangan"
                        }

                    ],
                    oLanguage: {
                        sProcessing: "<div id='loader'></div>"
                    }


                });
            }



            $('.btn_status_kejari_sby').click(function() {
                // var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var token = $('#token').val();
                var tahun = $('#tahun').val();
                var jaksa = $(this).data("jaksa");

                var token = $('#token').val();
                $('#tahun').val(tahun);

                if (token != '' || tahun != '') {
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejari_sby(token, tahun, jaksa);

                } else {
                    alert('Select Both filter option');
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejari_sby(token, tahun, jaksa);
                }
                // new $.fn.dataTable.FixedHeader(dataAset);
                // $.fn.dataTable.ext.errMode = 'throw';
            });
            $('#modal_kejari_sby').appendTo("body").modal({
                backdrop: 'static'
            })
        });
    </script>



    <script>
        $(document).ready(function() {

            fill_datatable_kejari_perak();

            function fill_datatable_kejari_perak(token = '', tahun = '', jaksa = '') {
                var token = $('#token').val();
                var tahun = $('#tahun').val();

                let kejatiData = new DataTable('#kejariPerakStatus', {
                    "responsive": true,
                    "serverSide": true,
                    "bProcessing": true,
                    "destroy": true,
                    "searching": true,

                    "lengthMenu": [
                        [10, 50, 100],
                        [10, 50, 100]
                    ],

                    "ajax": {
                        url: "<?php echo base_url() . 'home/fetch_nonlit_kejati'; ?>",
                        type: "POST",
                        data: {
                            tahun: tahun,
                            token: token,
                            jaksa: jaksa,
                        },
                    },



                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "permohonan_nonlit"
                        },
                        {
                            "data": "tgl_nonlit"
                        },
                        {
                            "data": "team_nonlit"
                        },
                        {
                            "data": "bidang"
                        },
                        {
                            "data": "status"
                        },
                        {
                            "data": "keterangan"
                        }

                    ],
                    oLanguage: {
                        sProcessing: "<div id='loader'></div>"
                    }


                });
            }



            $('.btn_status_kejari_perak').click(function() {
                // var tahun = $("#nonlit_filter_bytahun option:selected").val();
                var token = $('#token').val();
                var tahun = $('#tahun').val();
                var jaksa = $(this).data("jaksa");

                var token = $('#token').val();
                $('#tahun').val(tahun);

                if (token != '' || tahun != '') {
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejari_perak(token, tahun, jaksa);

                } else {
                    alert('Select Both filter option');
                    // $('#tabelData').DataTable().destroy();
                    fill_datatable_kejari_perak(token, tahun, jaksa);
                }
                // new $.fn.dataTable.FixedHeader(dataAset);
                // $.fn.dataTable.ext.errMode = 'throw';
            });
            $('#modal_kejari_perak').appendTo("body").modal({
                backdrop: 'static'
            })
        });
    </script>