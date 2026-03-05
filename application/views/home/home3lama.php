 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dashboard Non-Litigasi</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
 <style>
     .card-stat {
         border-radius: 10px;
         color: white;
         padding: 15px;
     }

     .bg-green {
         background-color: #28a745;
     }

     .bg-orange {
         background-color: #fd7e14;
     }

     .bg-blue {
         background-color: #007bff;
     }

     .bg-red {
         background-color: #dc3545;
     }

     .stat-value {
         font-size: 1.8rem;
         font-weight: bold;
     }

     .stat-label {
         font-size: 0.9rem;
     }

     .chart-container {
         height: 350px;
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
 </style>

 <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

 <!-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script> -->



 <div class="container my-4">
     <div class="container container-custom">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <h1 class="hero-title"> E-NONLIT</h1>
                 <p class="hero-description">Sistem Informasi Non-Litigasi pada Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya</p>
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
     <h4 class="mb-4">Dashboard Non-Litigasi</h4>

     <!-- Bagian Statistik -->

     <div class="row">
         <div class="col-sm-12 flex-column d-flex stretch-card">
             <div class="card">
                 <div class="card-body">
                     <h5 class="text-dark"> FILTER PENCARIAN By Tahun </h5>
                     <?= crsf_ajax(); ?>
                     <div class="form-group row">
                         <div class="col-md-4">
                             <label for="filter_tahun" class="form-label">Pilih Tahun</label>
                             <select class="form-control" id="filter_tahun">
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

                         <div class="col-md-4">
                             <label for="filter_status" class="form-label">Status</label>
                             <select class="form-control" id="filter_status">
                                 <option value="" disabled selected> SILAHKAN PILIH </option>
                                 <option value="proses"> PROSES </option>
                                 <option value="selesai"> SELESAI </option>
                                 <option value="all"> ALL </option>
                             </select>
                         </div>
                         <div class="col-md-4">
                             <label for="nonlit_jaksa" class="form-label"> &nbsp;</label>

                             <button type="button" name="filter" id="btnFilter" class="form-control btn btn-primary ">Filter</button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <br />
     <br />
     <div class="row g-3">
         <div class="col-md-4 col-6">
             <div class="card-stat bg-blue text-center">
                 <div class="stat-value" id="totalKasus">0</div>
                 <div class="stat-label">Total Permohonan</div>
             </div>
         </div>
         <div class="col-md-4 col-6">
             <div class="card-stat bg-orange text-center">
                 <div class="stat-value" id="totalProses">0</div>
                 <div class="stat-label">Proses</div>
             </div>
         </div>
         <div class="col-md-4 col-6">
             <div class="card-stat bg-green text-center">
                 <div class="stat-value" id="totalSelesai">0</div>
                 <div class="stat-label">Selesai</div>
             </div>
         </div>
         <!-- <div class="col-md-3 col-6">
                <div class="card-stat bg-red text-center">
                    <div class="stat-value">10</div>
                    <div class="stat-label">Ditolak</div>
                </div>
            </div> -->
     </div>

     <!-- Bagian Grafik -->
     <div class="row mt-4">
         <div class="col-lg-8">
             <div class="card shadow-sm">
                 <div class="card-header bg-white fw-bold">Grafik Permohonan</div>
                 <div class="card-body">
                     <canvas id="barChart" class="chart-container"></canvas>
                 </div>
             </div>
         </div>
         <div class="col-lg-4">
             <div class="card shadow-sm">
                 <div class="card-header bg-white fw-bold">Progres Status</div>
                 <div class="card-body">
                     <canvas id="pieChart" class="chart-container"></canvas>
                 </div>
             </div>
         </div>
     </div>

 </div>

 <!-- Modal -->
 <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalDetailLabel">Detail Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <table id="modalTable" class="display" style="width:100%">
                     <thead>
                         <tr>
                             <th>No</th>
                             <th>Permohonan</th>
                             <th>Status</th>
                             <th>Team</th>
                             <th>Tanggal</th>
                         </tr>
                     </thead>
                     <tbody></tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>




 <!-- <script>
     let barChart, pieChart;

     $('#btnFilter').on('click', function() {
         let token = $('#token').val();
         let tahun = $('#filter_tahun').val();
         let status = $('#filter_status').val();

         $.ajax({
             url: '<?= base_url("home/get_data_chart"); ?>',
             type: 'POST',
             data: {
                 tahun: tahun,
                 status: status,
                 token: token
             },
             dataType: 'json',
             success: function(res) {
                 $('#totalKasus').text(res.total);
                 $('#totalSelesai').text(res.total_selesai);
                 $('#totalProses').text(res.total_proses);

                 // Bar chart
                 let labels = res.bar.map(r => r.team_nonlit);
                 let selesai = res.bar.map(r => r.selesai);
                 let proses = res.bar.map(r => r.proses);

                 if (barChart) barChart.destroy();
                 barChart = new Chart($('#barChart'), {
                     type: 'bar',
                     data: {
                         labels: labels,
                         datasets: [{
                                 label: 'Selesai',
                                 data: selesai,
                                 backgroundColor: 'green'
                             },
                             {
                                 label: 'Proses',
                                 data: proses,
                                 backgroundColor: 'orange'
                             }
                         ]
                     },
                     options: {
                         onClick: function(e, elements) {

                             if (elements.length > 0) {
                                 let index = elements[0].index;
                                 let team = labels[index];

                                 // Filter detail sesuai team_nonlit
                                 let detailTeam = res.detail.filter(d => d.team_nonlit === team);
                                 showmodalTable(detailTeam);
                             }
                         }
                     }
                 });

                 // Pie chart
                 if (pieChart) pieChart.destroy();
                 pieChart = new Chart($('#pieChart'), {
                     type: 'pie',
                     data: {
                         labels: ['Selesai', 'Proses'],
                         datasets: [{
                             data: [res.pie.selesai, res.pie.proses],
                             backgroundColor: ['green', 'orange']
                         }]
                     }
                 });
             }
         });
     });
 </script>

 <script>
     let modalTable;

     //  $(document).ready(function() {
     // Inisialisasi DataTable sekali saja
     modalTable = $('#modalTable').DataTable({
         pageLength: 10,
         searching: true,
         lengthChange: true,
         responsive: true,
         data: [],
         columns: [{
                 title: "No"
             },
             {
                 title: "Permohonan"
             },
             {
                 title: "Status"
             },
             {
                 title: "Team"
             },
             {
                 title: "Tanggal"
             }
         ]
     });
     //  });

     // Fungsi showmodalTable dipanggil dari klik chart
     function showmodalTable(detailData) {
         if (!modalTable) {
             console.error("DataTable belum diinisialisasi.");
             return;
         }

         // Kosongkan lalu isi ulang tabel
         modalTable.clear();
         let no = 1;
         let tableData = detailData.map(item => [
             no++,
             item.permohonan_nonlit,
             item.status,
             item.team_nonlit,
             item.tgl_nonlit
         ]);
         modalTable.rows.add(tableData).draw();

         // Tampilkan modal
         $('#modalTable').modal('show');
     }
 </script> -->

 <script>
     let barChart, pieChart;



     // Tombol filter ditekan
     $('#btnFilter').on('click', function() {
         let token = $('#token').val();
         let tahun = $('#filter_tahun').val();
         let status = $('#filter_status').val();

         $.ajax({
             url: '<?= base_url("home/get_data_chart"); ?>',
             type: 'POST',
             data: {
                 tahun,
                 status,
                 token
             },
             dataType: 'json',
             success: function(res) {
                 $('#totalKasus').text(res.total);
                 $('#totalSelesai').text(res.total_selesai);
                 $('#totalProses').text(res.total_proses);

                 // ----- BAR CHART -----
                 let labels = res.bar.map(r => r.team_nonlit);
                 let selesai = res.bar.map(r => r.selesai);
                 let proses = res.bar.map(r => r.proses);

                 if (barChart) barChart.destroy();
                 barChart = new Chart($('#barChart'), {
                     type: 'bar',
                     data: {
                         labels: labels,
                         datasets: [{
                                 label: 'Selesai',
                                 data: selesai,
                                 backgroundColor: 'green'
                             },
                             {
                                 label: 'Proses',
                                 data: proses,
                                 backgroundColor: 'orange'
                             }
                         ]
                     },
                     options: {
                         responsive: true,
                         plugins: {
                             legend: {
                                 position: 'top'
                             }
                         },
                         onClick: function(e, elements) {
                             if (elements.length > 0) {
                                 let index = elements[0].index;
                                 let team = labels[index];
                                 // load detail by filter tahun & status + team
                                 loadDetail(team, tahun, status);
                             }
                         }
                     }
                 });

                 // ----- PIE CHART -----
                 if (pieChart) pieChart.destroy();
                 pieChart = new Chart($('#pieChart'), {
                     type: 'pie',
                     data: {
                         labels: ['Selesai', 'Proses'],
                         datasets: [{
                             data: [res.pie.selesai, res.pie.proses],
                             backgroundColor: ['green', 'orange']
                         }]
                     }
                 });
             }
         });
     });
 </script>


 <script>
     let modalTable;
     //  $(document).ready(function() {
     modalTable = $('#modalTable').DataTable({
         pageLength: 10,
         searching: true,
         responsive: true,
         data: [],
         columns: [{
                 title: "No"
             },
             {
                 title: "Permohonan"
             },
             {
                 title: "Status"
             },
             {
                 title: "Team"
             },
             {
                 title: "Tanggal"
             }
         ]
     });



     // Fungsi ambil detail dari server
     function loadDetail(team, tahun, status) {
         let token = $('#token').val();

         $.ajax({
             url: '<?= base_url("home/get_data_detail"); ?>',
             type: 'POST',
             data: {
                 tahun,
                 status,
                 team_nonlit: team,
                 token
             },
             dataType: 'json',
             success: function(detailData) {
                 if (!modalTable) {
                     console.error("DataTable belum terinisialisasi");
                     return;
                 }

                 // Kosongkan tabel lalu isi ulang
                 modalTable.clear();

                 let no = 1;
                 let tableData = detailData.map(item => [
                     no++,
                     item.permohonan_nonlit,
                     item.status,
                     item.team_nonlit,
                     item.tgl_nonlit
                 ]);

                 modalTable.rows.add(tableData).draw();

                 // ✅ yang benar buka modal detail
                 $('#modalDetail').modal('show');
             }
         });


     }

     //  });
 </script>
 <!-- <script>
        let chartBar, chartPie;

        function loadDashboard(tahun = 'all', status = 'all') {
            var token = $('#token').val();

            $.ajax({
                url: "<?= site_url('home/get_dashboard_data') ?>",
                type: "POST",
                data: {
                    tahun: tahun,
                    status: status,
                    token: token
                },
                dataType: "json",
                success: function(res) {
                    // Update box info
                    $('#box_total').text(res.total);
                    $('#box_proses').text(res.proses);
                    $('#box_selesai').text(res.selesai);

                    // Ambil label unik team_nonlit
                    let teams = [...new Set(res.grafik.map(item => item.team_nonlit))];

                    // Ambil data untuk Selesai dan Proses
                    let selesaiData = [];
                    let prosesData = [];

                    teams.forEach(team => {
                        let selesaiItem = res.grafik.find(g => g.team_nonlit === team && g.status === 'selesai');
                        let prosesItem = res.grafik.find(g => g.team_nonlit === team && g.status === 'proses');

                        selesaiData.push(selesaiItem ? selesaiItem.jumlah : 0);
                        prosesData.push(prosesItem ? prosesItem.jumlah : 0);
                    });

                    // Bar Chart
                    if (chartBar) chartBar.destroy();
                    chartBar = new Chart(document.getElementById('chartBar'), {
                        type: 'bar',
                        data: {
                            labels: teams,
                            datasets: [{
                                    label: 'Selesai',
                                    data: selesaiData,
                                    backgroundColor: 'rgba(75, 192, 192, 0.7)'
                                },
                                {
                                    label: 'Proses',
                                    data: prosesData,
                                    backgroundColor: 'rgba(255, 159, 64, 0.7)'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Jumlah Kasus per Team & Status'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });

                    // Pie Chart
                    if (chartPie) chartPie.destroy();
                    chartPie = new Chart(document.getElementById('chartPie'), {
                        type: 'pie',
                        data: {
                            labels: ['Selesai', 'Proses'],
                            datasets: [{
                                data: [res.selesai, res.proses],
                                backgroundColor: ['green', 'orange']
                            }]
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            loadDashboard();

            $('#btn_filter').click(function() {
                let tahun = $('#filter_tahun').val();
                let status = $('#filter_status').val();
                loadDashboard(tahun, status);
            });
        });
    </script> -->
 <!-- 
    <script>
        // Chart Bar
        new Chart(document.getElementById('chartPermohonan'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Jumlah Permohonan',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: '#007bff'
                }]
            }
        });

        // Chart Pie
        new Chart(document.getElementById('chartStatus'), {
            type: 'pie',
            data: {
                labels: ['Selesai', 'Proses', 'Pending'],
                datasets: [{
                    data: [60, 25, 15],
                    backgroundColor: ['#28a745', '#fd7e14', '#dc3545']
                }]
            }
        });
    </script> -->