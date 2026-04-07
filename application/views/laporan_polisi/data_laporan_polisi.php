<<<<<<< HEAD
<div class="container mx-auto p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary uppercase italic tracking-tighter">Daftar Laporan Polisi</h1>
            <p class="text-xs font-bold opacity-50 uppercase tracking-widest">Monitoring Berkas & Agenda Kepolisian</p>
        </div>
        <div class="flex gap-2">
            <div class="relative">
                <input type="text" id="search_lp" placeholder="Cari No. Polisi / Judul..."
                    class="input input-bordered w-full max-w-xs rounded-xl pl-10 uppercase text-xs font-bold">
                <i class="mdi mdi-magnify absolute left-3 top-3 opacity-30"></i>
            </div>
            <button onclick="tambahLp()" class="btn btn-primary rounded-2xl px-6 shadow-lg shadow-primary/30 flex gap-2">
                <i class="mdi mdi-plus-circle text-xl"></i>
                <span class="font-black uppercase tracking-widest text-xs">Tambah Laporan</span>
            </button>
        </div>
    </div>

    <div id="lp_list_container" class="min-h-[100px] hidden">
        <div class="flex justify-center p-10">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    </div>

    <div id="card-list"></div>

    <div id="csrf-container">
        <?= crsf_ajax() ?>
    </div>

    <div id="pagination-area" class="flex justify-center my-10 hidden">
        <button id="btn-load-more" onclick="loadNextPage()" class="btn btn-wide btn-outline border-base-300 rounded-2xl font-black uppercase italic tracking-widest gap-3 shadow-sm hover:bg-slate-50 transition-all group">
            <i id="load-icon" class="mdi mdi-chevron-double-down group-hover:animate-bounce"></i>
            <span id="load-text">Tampilkan Lebih Banyak</span>
        </button>
    </div>

    <div id="end-of-data" class="hidden text-center py-10 opacity-20">
        <div class="divider font-black text-[10px] uppercase tracking-[0.4em]">Akhir dari Data</div>
    </div>
</div>
<dialog id="modal_master_lp" class="modal">
    <div class="modal-box max-w-2xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-primary p-6 text-primary-content flex justify-between items-center">
            <h3 class="font-black text-xl uppercase italic tracking-tighter" id="title_master">Tambah Laporan Baru</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?= base_url('laporan_polisi/save_master') ?>" method="POST" class="p-8 grid grid-cols-2 gap-4">
            <input type="hidden" name="id_laporan_polisi" id="m_id">
            <?= crsf() ?>

            <div class="form-control col-span-2">
                <label class="label text-[10px] font-black uppercase opacity-40">Judul Laporan / Kasus</label>
                <input type="text" name="judul" id="m_judul" class="input input-bordered rounded-xl font-bold uppercase" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nomor Polisi</label>
                <input type="text" name="nomor" id="m_nomor" class="input input-bordered rounded-xl uppercase" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Tanggal Laporan</label>
                <input type="date" name="tgl" id="m_tgl" class="input input-bordered rounded-xl" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nama Pelapor</label>
                <input type="text" name="pelapor" id="m_pelapor" class="input input-bordered rounded-xl uppercase">
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nama Terlapor</label>
                <input type="text" name="terlapor" id="m_terlapor" class="input input-bordered rounded-xl uppercase">
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">PIC / Penanggung Jawab</label>
                <select name="pic" id="m_pic" class="select select-bordered rounded-2xl font-bold focus:border-primary">
                    <option value="" disabled selected>-- Pilih PIC --</option>
                    <?php foreach ($list_pic as $pic): ?>
                        <option value="<?= $pic->nama_pic; ?>"><?= $pic->nama_pic; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control">

                <label class="label text-[10px] font-black uppercase opacity-40">Team Kepolisian</label>
                <select name="team_polisi" id="m_team" class="select select-bordered rounded-xl font-bold">
                    <option value="polrestabes_sby">Polrestabes SBY</option>
                    <option value="polres_gresik">Polres Gresik</option>
                    <option value="polres_sidoarjo">Polres Sidoarjo</option>
                    <option value="polres_pasuruan">Polres Pasuruan</option>
                    <option value="polres_malang">Polres Malang</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Penyimpanan Rak</label>
                <input type="text" name="rak" id="m_rak" class="input input-bordered rounded-xl uppercase">
            </div>

            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Status</label>
                <select name="status" id="m_status" class="select select-bordered rounded-xl font-bold">
                    <option value="LIDIK">LIDIK</option>
                    <option value="SIDIK">SIDIK</option>
                    <option value="SP3">SP3</option>
                    <option value="P21">P21</option>
                </select>
            </div>
            <div class="form-control col-span-2">
                <label class="label text-[10px] font-black uppercase opacity-40">Alamat Kejadian / TKP</label>
                <textarea name="alamat" id="m_alamat" class="textarea textarea-bordered rounded-xl h-20"></textarea>
            </div>
            <button type="submit" class="btn btn-primary col-span-2 mt-4 rounded-xl uppercase font-black shadow-lg">Simpan Laporan</button>
        </form>
    </div>
</dialog>
<script>
    $(document).ready(function() {
        load_lp_data(0);

        // Fungsi Search dengan Delay
        $('#search_lp').keyup(function() {
            load_lp_data(0);
        });
    });

    function load_lp_data(page) {
        const search = $('#search_lp').val();
        const token = $('#token').val();
        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            data: {
                search: search,
                page: page,
                token: token
            },
            dataType: "JSON",
            success: function(data) {
                $('#lp_list_container').html(data.html);
                $('#pagination_link').html(data.pagination);
            }
        });
    }
</script>

<script>
    // Fungsi untuk Tambah Data (Reset Form)
    function tambahLp() {
        $('#m_id').val(''); // ID kosong = Insert
        $('#m_judul').val('');
        $('#m_nomor').val('');
        $('#m_tgl').val('');
        $('#m_pelapor').val('');
        $('#m_terlapor').val('');
        $('#m_pic').val('');
        $('#m_status').val('LIDIK');
        $('#m_alamat').val('');
        $('#m_rak').val('');
        $('#m_team').val('');

        $('#title_master').text('Tambah Laporan Baru');
        document.getElementById('modal_master_lp').showModal();
    }

    // Fungsi untuk Edit Data (Ambil data via AJAX)
    function editLp(id) {
        $.getJSON("<?= base_url('laporan_polisi/get_master_by_id/') ?>" + id, function(data) {
            $('#m_id').val(data.id_laporan_polisi);
            $('#m_judul').val(data.judul_laporan_polisi);
            $('#m_nomor').val(data.nomor_polisi);
            $('#m_tgl').val(data.tgl_laporan_polisi);
            $('#m_pelapor').val(data.pelapor);
            $('#m_terlapor').val(data.terlapor);
            // Bagian Auto-Select PIC
            $('#m_pic').val(data.pic_laporan_polisi);
            $('#m_status').val(data.status_laporan_polisi);
            $('#m_alamat').val(data.alamat_laporan_polisi);
            $('#m_rak').val(data.penyimpanan_rak);
            $('#m_team').val(data.team_polisi);


            $('#title_master').text('Edit Laporan Polisi');
            document.getElementById('modal_master_lp').showModal();
        });
    }
</script>


<script>
    function editLp(id) {
        $.getJSON("<?= base_url('laporan_polisi/get_master_by_id/') ?>" + id, function(data) {
            // Isi field modal dengan data dari server
            $('#m_id').val(data.id_laporan_polisi);
            $('#m_judul').val(data.judul_laporan_polisi);
            $('#m_nomor').val(data.nomor_polisi);
            $('#m_tgl').val(data.tgl_laporan_polisi);
            $('#m_pelapor').val(data.pelapor);
            $('#m_terlapor').val(data.terlapor);
            $('#m_pic').val(data.pic_laporan_polisi);
            $('#m_status').val(data.status_laporan_polisi);
            $('#m_alamat').val(data.alamat_laporan_polisi);
            $('#m_rak').val(data.penyimpanan_rak);
            $('#m_team').val(data.team_polisi);

            // Ubah Judul Modal & Tampilkan
            $('#title_master').text('Edit Laporan Polisi');
            document.getElementById('modal_master_lp').showModal();
        });
    }
</script>

<script>
    function hapus_master(id) {
        var token = $('#token').val(); // Ambil token dari input tersembunyi
        Swal.fire({
            title: 'HAPUS LAPORAN?',
            text: "Seluruh data agenda/detail terkait laporan ini juga akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // warna error
            cancelButtonColor: '#6b7280', // warna gray
            confirmButtonText: 'YA, HAPUS PERMANEN',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-black uppercase italic',
                cancelButton: 'rounded-xl font-black uppercase italic'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('laporan_polisi/delete_master') ?>", {
                    id: id,
                    token: token // Kirim token dengan key dinamis
                }, function(res) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Laporan telah dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                    //refresh data setelah hapus

                    loadData(true);

                    // Refresh list data tanpa reload halaman
                });
            }
        });
    }
</script>

<script>
    function cetak_label_lp(no_pol, penyimpanan_rak, judul, status) {
        const printWindow = window.open('', '_blank', 'width=900,height=400');

        // Fallback jika data kosong
        const displayNoPol = (no_pol && no_pol.trim() !== "") ? no_pol : "-";
        const displayJudul = (judul && judul.trim() !== "") ? judul : "-";
        const displayStatus = (status && status.trim() !== "") ? status : "-";
        const displayRak = (penyimpanan_rak && penyimpanan_rak.trim() !== "") ? penyimpanan_rak : "-";

        const htmlContent = `
        <html>
        <head>
            <title>Cetak Label LP</title>
            <style>
                @page { size: A4; margin: 0; }
                body {
                    margin: 0;
                    padding: 5mm;
                    font-family: 'Arial', sans-serif;
                    text-transform: uppercase;
                }
                .label-strip {
                    display: flex;
                    align-items: stretch;
                    border: 2pt solid #000;
                    width: 100%;
                    max-width: 19cm;
                    height: 1.2cm;
                    overflow: hidden;
                }
                .section-status {
                    background: #000;
                    color: #fff;
                    font-weight: 900;
                    font-size: 12pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 90px;
                    border-right: 2pt solid #000;
                    -webkit-print-color-adjust: exact;
                }
                .section-nopol {
                    min-width: 200px;
                    font-weight: 800;
                    font-size: 10pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    border-right: 1.5pt solid #000;
                    background: #f3f4f6;
                    -webkit-print-color-adjust: exact;
                }
                .section-judul {
                    flex: 1;
                    font-size: 9pt;
                    font-weight: bold;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="label-strip">
                <div class="section-status">Rak : ${displayRak} | ${displayStatus}</div>
                <div class="section-nopol">${displayNoPol}</div>
                <div class="section-judul">${displayJudul}</div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 200);
                };
            <\/script>
        </body>
        </html>
    `;

        printWindow.document.write(htmlContent);
        printWindow.document.close();
    }
</script>
<!-- 
<script>
    let currentPage = 0;

    function loadData(isNewSearch = false) {
        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden'); // Tampilkan loader
        }

        // Pastikan ID selector sesuai dengan HTML (search_lp)
        const keyword = $('#search_lp').val();

        $.ajax({
            url: '<?= base_url("laporan_polisi/fetch_data") ?>',
            type: 'POST',
            data: {
                search: keyword,
                page: currentPage
            },
            dataType: 'json',
            success: function(res) {
                $('#lp_list_container').addClass('hidden'); // Sembunyikan loader

                if (res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    // Logika Tampilkan Tombol
                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                        $('#end-of-data').addClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html(res.html); // Menampilkan pesan "Tidak Ditemukan"
                        $('#pagination-area').addClass('hidden');
                    }
                }
            }
        });
    }
</script> -->
<!-- <script>
    // HAPUS SEMUA let/var currentPage di tempat lain. Cukup di sini satu kali.
    var currentPage = 0;
    var isLoading = false;

    // Fungsi Load Next Page (Harus Global)
    function loadNextPage() {
        if (isLoading) return;
        currentPage++;
        loadData(false);
    }

    function loadData(isNewSearch = false) {
        if (isLoading) return;

        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden');
            $('#pagination-area').addClass('hidden');
            $('#end-of-data').addClass('hidden');
        }

        isLoading = true;

        // --- LOGIKA TOKEN ANDA ---
        // Karena Anda menyebutkan var token = $('#token').val(), pastikan selector ini benar
        const token = $('#token').val();
        // const tokenName = '<?= $this->security->get_csrf_token_name(); ?>';
        const searchKeyword = $('#search_lp').val();

        const btn = $('#btn-load-more');
        const originalText = $('#load-text').text();
        $('#load-text').text('Memuat...');
        btn.addClass('loading');

        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                search: searchKeyword,
                page: currentPage,
                token: token // Mengirim token ke CodeIgniter
            },
            success: function(res) {
                $('#lp_list_container').addClass('hidden');

                // UPDATE TOKEN SETELAH REQUEST BERHASIL
                // Agar klik "Tampilkan Lebih Banyak" berikutnya tidak error 403
                if (res.csrf_hash) {
                    $('#token').val(res.csrf_hash);
                }

                if (res.html && res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    // Cek apakah data sudah habis
                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                        $('#end-of-data').addClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html('<div class="text-center py-20 opacity-30 font-black uppercase italic">Data tidak ditemukan</div>');
                        $('#pagination-area').addClass('hidden');
                    }
                }
            },
            error: function(xhr) {
                console.error("AJAX Error: ", xhr.responseText);
            },
            complete: function() {
                isLoading = false;
                btn.removeClass('loading');
                $('#load-text').text(originalText);
            }
        });
    }

    // Inisialisasi Event
    $(document).ready(function() {
        // Load data pertama kali saat halaman dibuka
        loadData(true);

        // Search dengan delay agar tidak lag
        let typingTimer;
        $('#search_lp').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                loadData(true);
            }, 500);
        });
    });
</script> -->
<script>
    // Deklarasi Global
    var currentPage = 0;
    var isLoading = false;
    var token = $('#token').val(); // Ambil token dari input tersembunyi

    $(document).ready(function() {
        // Inisialisasi awal
        loadData(true);

        // Pencarian Otomatis
        let typingTimer;
        $('#search_lp').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                loadData(true);
            }, 500);
        });
    });

    function loadNextPage() {
        if (isLoading) return;
        currentPage++;
        loadData(false);
    }

    function loadData(isNewSearch = false) {
        if (isLoading) return;

        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden');
            $('#pagination-area').addClass('hidden');
            $('#end-of-data').addClass('hidden');
        }

        isLoading = true;
        const searchKeyword = $('#search_lp').val();

        // TETAP MENGGUNAKAN FUNGSI ANDA UNTUK MENGAMBIL TOKEN
        const token = $('#token').val();

        const btn = $('#btn-load-more');
        const originalText = $('#load-text').text();
        $('#load-text').text('Memuat...');
        btn.addClass('loading');

        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                search: searchKeyword,
                page: currentPage,
                token: token // Kirim token dengan key dinamis
            },
            success: function(res) {
                $('#lp_list_container').addClass('hidden');

                // UPDATE TOKEN CSRF MENGGUNAKAN FUNGSI ANDA (#token)
                if (res.csrf_hash) {
                    $('#token').val(res.csrf_hash);
                }

                if (res.html && res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html(`
                            <div class="flex flex-col items-center justify-center p-24 border-4 border-dashed border-base-300 rounded-[4rem] opacity-20 bg-base-200/10">
                                <i class="mdi mdi-text-search text-8xl mb-6"></i>
                                <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Tidak Ditemukan</p>
                            </div>
                        `);
                    }
                }
            },
            error: function(xhr) {
                console.error("AJAX Error: ", xhr.responseText);
            },
            complete: function() {
                isLoading = false;
                btn.removeClass('loading');
                $('#load-text').text(originalText);
            }
        });
    }
=======
<div class="container mx-auto p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary uppercase italic tracking-tighter">Daftar Laporan Polisi</h1>
            <p class="text-xs font-bold opacity-50 uppercase tracking-widest">Monitoring Berkas & Agenda Kepolisian</p>
        </div>
        <div class="flex gap-2">
            <div class="relative">
                <input type="text" id="search_lp" placeholder="Cari No. Polisi / Judul..."
                    class="input input-bordered w-full max-w-xs rounded-xl pl-10 uppercase text-xs font-bold">
                <i class="mdi mdi-magnify absolute left-3 top-3 opacity-30"></i>
            </div>
            <button onclick="tambahLp()" class="btn btn-primary rounded-2xl px-6 shadow-lg shadow-primary/30 flex gap-2">
                <i class="mdi mdi-plus-circle text-xl"></i>
                <span class="font-black uppercase tracking-widest text-xs">Tambah Laporan</span>
            </button>
        </div>
    </div>

    <div id="lp_list_container" class="min-h-[100px] hidden">
        <div class="flex justify-center p-10">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    </div>

    <div id="card-list"></div>

    <div id="csrf-container">
        <?= crsf_ajax() ?>
    </div>

    <div id="pagination-area" class="flex justify-center my-10 hidden">
        <button id="btn-load-more" onclick="loadNextPage()" class="btn btn-wide btn-outline border-base-300 rounded-2xl font-black uppercase italic tracking-widest gap-3 shadow-sm hover:bg-slate-50 transition-all group">
            <i id="load-icon" class="mdi mdi-chevron-double-down group-hover:animate-bounce"></i>
            <span id="load-text">Tampilkan Lebih Banyak</span>
        </button>
    </div>

    <div id="end-of-data" class="hidden text-center py-10 opacity-20">
        <div class="divider font-black text-[10px] uppercase tracking-[0.4em]">Akhir dari Data</div>
    </div>
</div>
<dialog id="modal_master_lp" class="modal">
    <div class="modal-box max-w-2xl p-0 rounded-3xl border-none shadow-2xl">
        <div class="bg-primary p-6 text-primary-content flex justify-between items-center">
            <h3 class="font-black text-xl uppercase italic tracking-tighter" id="title_master">Tambah Laporan Baru</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <form action="<?= base_url('laporan_polisi/save_master') ?>" method="POST" class="p-8 grid grid-cols-2 gap-4">
            <input type="hidden" name="id_laporan_polisi" id="m_id">
            <?= crsf() ?>

            <div class="form-control col-span-2">
                <label class="label text-[10px] font-black uppercase opacity-40">Judul Laporan / Kasus</label>
                <input type="text" name="judul" id="m_judul" class="input input-bordered rounded-xl font-bold uppercase" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nomor Polisi</label>
                <input type="text" name="nomor" id="m_nomor" class="input input-bordered rounded-xl uppercase" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Tanggal Laporan</label>
                <input type="date" name="tgl" id="m_tgl" class="input input-bordered rounded-xl" required>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nama Pelapor</label>
                <input type="text" name="pelapor" id="m_pelapor" class="input input-bordered rounded-xl uppercase">
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Nama Terlapor</label>
                <input type="text" name="terlapor" id="m_terlapor" class="input input-bordered rounded-xl uppercase">
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">PIC / Penanggung Jawab</label>
                <select name="pic" id="m_pic" class="select select-bordered rounded-2xl font-bold focus:border-primary">
                    <option value="" disabled selected>-- Pilih PIC --</option>
                    <?php foreach ($list_pic as $pic): ?>
                        <option value="<?= $pic->nama_pic; ?>"><?= $pic->nama_pic; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control">

                <label class="label text-[10px] font-black uppercase opacity-40">Team Kepolisian</label>
                <select name="team_polisi" id="m_team" class="select select-bordered rounded-xl font-bold">
                    <option value="polrestabes_sby">Polrestabes SBY</option>
                    <option value="polres_gresik">Polres Gresik</option>
                    <option value="polres_sidoarjo">Polres Sidoarjo</option>
                    <option value="polres_pasuruan">Polres Pasuruan</option>
                    <option value="polres_malang">Polres Malang</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Penyimpanan Rak</label>
                <input type="text" name="rak" id="m_rak" class="input input-bordered rounded-xl uppercase">
            </div>

            <div class="form-control">
                <label class="label text-[10px] font-black uppercase opacity-40">Status</label>
                <select name="status" id="m_status" class="select select-bordered rounded-xl font-bold">
                    <option value="LIDIK">LIDIK</option>
                    <option value="SIDIK">SIDIK</option>
                    <option value="SP3">SP3</option>
                    <option value="P21">P21</option>
                </select>
            </div>
            <div class="form-control col-span-2">
                <label class="label text-[10px] font-black uppercase opacity-40">Alamat Kejadian / TKP</label>
                <textarea name="alamat" id="m_alamat" class="textarea textarea-bordered rounded-xl h-20"></textarea>
            </div>
            <button type="submit" class="btn btn-primary col-span-2 mt-4 rounded-xl uppercase font-black shadow-lg">Simpan Laporan</button>
        </form>
    </div>
</dialog>
<script>
    $(document).ready(function() {
        load_lp_data(0);

        // Fungsi Search dengan Delay
        $('#search_lp').keyup(function() {
            load_lp_data(0);
        });
    });

    function load_lp_data(page) {
        const search = $('#search_lp').val();
        const token = $('#token').val();
        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            data: {
                search: search,
                page: page,
                token: token
            },
            dataType: "JSON",
            success: function(data) {
                $('#lp_list_container').html(data.html);
                $('#pagination_link').html(data.pagination);
            }
        });
    }
</script>

<script>
    // Fungsi untuk Tambah Data (Reset Form)
    function tambahLp() {
        $('#m_id').val(''); // ID kosong = Insert
        $('#m_judul').val('');
        $('#m_nomor').val('');
        $('#m_tgl').val('');
        $('#m_pelapor').val('');
        $('#m_terlapor').val('');
        $('#m_pic').val('');
        $('#m_status').val('LIDIK');
        $('#m_alamat').val('');
        $('#m_rak').val('');
        $('#m_team').val('');

        $('#title_master').text('Tambah Laporan Baru');
        document.getElementById('modal_master_lp').showModal();
    }

    // Fungsi untuk Edit Data (Ambil data via AJAX)
    function editLp(id) {
        $.getJSON("<?= base_url('laporan_polisi/get_master_by_id/') ?>" + id, function(data) {
            $('#m_id').val(data.id_laporan_polisi);
            $('#m_judul').val(data.judul_laporan_polisi);
            $('#m_nomor').val(data.nomor_polisi);
            $('#m_tgl').val(data.tgl_laporan_polisi);
            $('#m_pelapor').val(data.pelapor);
            $('#m_terlapor').val(data.terlapor);
            // Bagian Auto-Select PIC
            $('#m_pic').val(data.pic_laporan_polisi);
            $('#m_status').val(data.status_laporan_polisi);
            $('#m_alamat').val(data.alamat_laporan_polisi);
            $('#m_rak').val(data.penyimpanan_rak);
            $('#m_team').val(data.team_polisi);


            $('#title_master').text('Edit Laporan Polisi');
            document.getElementById('modal_master_lp').showModal();
        });
    }
</script>


<script>
    function editLp(id) {
        $.getJSON("<?= base_url('laporan_polisi/get_master_by_id/') ?>" + id, function(data) {
            // Isi field modal dengan data dari server
            $('#m_id').val(data.id_laporan_polisi);
            $('#m_judul').val(data.judul_laporan_polisi);
            $('#m_nomor').val(data.nomor_polisi);
            $('#m_tgl').val(data.tgl_laporan_polisi);
            $('#m_pelapor').val(data.pelapor);
            $('#m_terlapor').val(data.terlapor);
            $('#m_pic').val(data.pic_laporan_polisi);
            $('#m_status').val(data.status_laporan_polisi);
            $('#m_alamat').val(data.alamat_laporan_polisi);
            $('#m_rak').val(data.penyimpanan_rak);
            $('#m_team').val(data.team_polisi);

            // Ubah Judul Modal & Tampilkan
            $('#title_master').text('Edit Laporan Polisi');
            document.getElementById('modal_master_lp').showModal();
        });
    }
</script>

<script>
    function hapus_master(id) {
        var token = $('#token').val(); // Ambil token dari input tersembunyi
        Swal.fire({
            title: 'HAPUS LAPORAN?',
            text: "Seluruh data agenda/detail terkait laporan ini juga akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // warna error
            cancelButtonColor: '#6b7280', // warna gray
            confirmButtonText: 'YA, HAPUS PERMANEN',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-black uppercase italic',
                cancelButton: 'rounded-xl font-black uppercase italic'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('laporan_polisi/delete_master') ?>", {
                    id: id,
                    token: token // Kirim token dengan key dinamis
                }, function(res) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Laporan telah dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                    //refresh data setelah hapus

                    loadData(true);

                    // Refresh list data tanpa reload halaman
                });
            }
        });
    }
</script>

<script>
    function cetak_label_lp(no_pol, penyimpanan_rak, judul, status) {
        const printWindow = window.open('', '_blank', 'width=900,height=400');

        // Fallback jika data kosong
        const displayNoPol = (no_pol && no_pol.trim() !== "") ? no_pol : "-";
        const displayJudul = (judul && judul.trim() !== "") ? judul : "-";
        const displayStatus = (status && status.trim() !== "") ? status : "-";
        const displayRak = (penyimpanan_rak && penyimpanan_rak.trim() !== "") ? penyimpanan_rak : "-";

        const htmlContent = `
        <html>
        <head>
            <title>Cetak Label LP</title>
            <style>
                @page { size: A4; margin: 0; }
                body {
                    margin: 0;
                    padding: 5mm;
                    font-family: 'Arial', sans-serif;
                    text-transform: uppercase;
                }
                .label-strip {
                    display: flex;
                    align-items: stretch;
                    border: 2pt solid #000;
                    width: 100%;
                    max-width: 19cm;
                    height: 1.2cm;
                    overflow: hidden;
                }
                .section-status {
                    background: #000;
                    color: #fff;
                    font-weight: 900;
                    font-size: 12pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 90px;
                    border-right: 2pt solid #000;
                    -webkit-print-color-adjust: exact;
                }
                .section-nopol {
                    min-width: 200px;
                    font-weight: 800;
                    font-size: 10pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    border-right: 1.5pt solid #000;
                    background: #f3f4f6;
                    -webkit-print-color-adjust: exact;
                }
                .section-judul {
                    flex: 1;
                    font-size: 9pt;
                    font-weight: bold;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="label-strip">
                <div class="section-status">Rak : ${displayRak} | ${displayStatus}</div>
                <div class="section-nopol">${displayNoPol}</div>
                <div class="section-judul">${displayJudul}</div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 200);
                };
            <\/script>
        </body>
        </html>
    `;

        printWindow.document.write(htmlContent);
        printWindow.document.close();
    }
</script>
<!-- 
<script>
    let currentPage = 0;

    function loadData(isNewSearch = false) {
        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden'); // Tampilkan loader
        }

        // Pastikan ID selector sesuai dengan HTML (search_lp)
        const keyword = $('#search_lp').val();

        $.ajax({
            url: '<?= base_url("laporan_polisi/fetch_data") ?>',
            type: 'POST',
            data: {
                search: keyword,
                page: currentPage
            },
            dataType: 'json',
            success: function(res) {
                $('#lp_list_container').addClass('hidden'); // Sembunyikan loader

                if (res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    // Logika Tampilkan Tombol
                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                        $('#end-of-data').addClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html(res.html); // Menampilkan pesan "Tidak Ditemukan"
                        $('#pagination-area').addClass('hidden');
                    }
                }
            }
        });
    }
</script> -->
<!-- <script>
    // HAPUS SEMUA let/var currentPage di tempat lain. Cukup di sini satu kali.
    var currentPage = 0;
    var isLoading = false;

    // Fungsi Load Next Page (Harus Global)
    function loadNextPage() {
        if (isLoading) return;
        currentPage++;
        loadData(false);
    }

    function loadData(isNewSearch = false) {
        if (isLoading) return;

        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden');
            $('#pagination-area').addClass('hidden');
            $('#end-of-data').addClass('hidden');
        }

        isLoading = true;

        // --- LOGIKA TOKEN ANDA ---
        // Karena Anda menyebutkan var token = $('#token').val(), pastikan selector ini benar
        const token = $('#token').val();
        // const tokenName = '<?= $this->security->get_csrf_token_name(); ?>';
        const searchKeyword = $('#search_lp').val();

        const btn = $('#btn-load-more');
        const originalText = $('#load-text').text();
        $('#load-text').text('Memuat...');
        btn.addClass('loading');

        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                search: searchKeyword,
                page: currentPage,
                token: token // Mengirim token ke CodeIgniter
            },
            success: function(res) {
                $('#lp_list_container').addClass('hidden');

                // UPDATE TOKEN SETELAH REQUEST BERHASIL
                // Agar klik "Tampilkan Lebih Banyak" berikutnya tidak error 403
                if (res.csrf_hash) {
                    $('#token').val(res.csrf_hash);
                }

                if (res.html && res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    // Cek apakah data sudah habis
                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                        $('#end-of-data').addClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html('<div class="text-center py-20 opacity-30 font-black uppercase italic">Data tidak ditemukan</div>');
                        $('#pagination-area').addClass('hidden');
                    }
                }
            },
            error: function(xhr) {
                console.error("AJAX Error: ", xhr.responseText);
            },
            complete: function() {
                isLoading = false;
                btn.removeClass('loading');
                $('#load-text').text(originalText);
            }
        });
    }

    // Inisialisasi Event
    $(document).ready(function() {
        // Load data pertama kali saat halaman dibuka
        loadData(true);

        // Search dengan delay agar tidak lag
        let typingTimer;
        $('#search_lp').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                loadData(true);
            }, 500);
        });
    });
</script> -->
<script>
    // Deklarasi Global
    var currentPage = 0;
    var isLoading = false;
    var token = $('#token').val(); // Ambil token dari input tersembunyi

    $(document).ready(function() {
        // Inisialisasi awal
        loadData(true);

        // Pencarian Otomatis
        let typingTimer;
        $('#search_lp').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                loadData(true);
            }, 500);
        });
    });

    function loadNextPage() {
        if (isLoading) return;
        currentPage++;
        loadData(false);
    }

    function loadData(isNewSearch = false) {
        if (isLoading) return;

        if (isNewSearch) {
            currentPage = 0;
            $('#card-list').html('');
            $('#lp_list_container').removeClass('hidden');
            $('#pagination-area').addClass('hidden');
            $('#end-of-data').addClass('hidden');
        }

        isLoading = true;
        const searchKeyword = $('#search_lp').val();

        // TETAP MENGGUNAKAN FUNGSI ANDA UNTUK MENGAMBIL TOKEN
        const token = $('#token').val();

        const btn = $('#btn-load-more');
        const originalText = $('#load-text').text();
        $('#load-text').text('Memuat...');
        btn.addClass('loading');

        $.ajax({
            url: "<?= base_url('laporan_polisi/fetch_data') ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                search: searchKeyword,
                page: currentPage,
                token: token // Kirim token dengan key dinamis
            },
            success: function(res) {
                $('#lp_list_container').addClass('hidden');

                // UPDATE TOKEN CSRF MENGGUNAKAN FUNGSI ANDA (#token)
                if (res.csrf_hash) {
                    $('#token').val(res.csrf_hash);
                }

                if (res.html && res.html !== '') {
                    if (isNewSearch) {
                        $('#card-list').html(res.html);
                    } else {
                        $('#card-list').append(res.html);
                    }

                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                    }
                } else {
                    if (isNewSearch) {
                        $('#card-list').html(`
                            <div class="flex flex-col items-center justify-center p-24 border-4 border-dashed border-base-300 rounded-[4rem] opacity-20 bg-base-200/10">
                                <i class="mdi mdi-text-search text-8xl mb-6"></i>
                                <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Tidak Ditemukan</p>
                            </div>
                        `);
                    }
                }
            },
            error: function(xhr) {
                console.error("AJAX Error: ", xhr.responseText);
            },
            complete: function() {
                isLoading = false;
                btn.removeClass('loading');
                $('#load-text').text(originalText);
            }
        });
    }
>>>>>>> Initial commit dari server
</script>