<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-base-100 p-6 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-3xl font-bold text-primary"><?= $title; ?></h1>
            <p class="text-sm opacity-60">Manajemen berkas dan laporan non-litigasi</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <div class="relative flex-grow md:flex-grow-0">
                <input type="text" id="search" placeholder="Cari nama atau PIC..." class="input input-bordered w-full md:w-80 pl-10" />
                <i class="fas fa-search absolute left-4 top-4 opacity-30"></i>
            </div>
            <button onclick="openModalTambah()" class="btn btn-primary shadow-md">
                <i class="fas fa-plus"></i> Tambah Masalah
            </button>
        </div>
    </div>

    <div id="display-area" class="flex flex-col gap-3 w-full">
        <div class="flex justify-center p-10">
            <span class="loading loading-dots loading-lg text-primary"></span>
        </div>
    </div>

    <div id="display-area" class="flex flex-col gap-3 w-full">
    </div>

    <div id="pagination-area" class="flex justify-center my-10 hidden">
        <button id="btn-load-more" onclick="loadNextPage()" class="btn btn-wide btn-outline border-base-300 rounded-2xl font-black uppercase italic tracking-widest gap-3">
            <i class="mdi mdi-chevron-double-down animate-bounce"></i>
            Tampilkan Lebih Banyak
        </button>
    </div>

    <div id="end-of-data" class="hidden text-center py-10 opacity-20">
        <div class="divider font-black text-[10px] uppercase tracking-[0.4em]">Akhir dari Data</div>
    </div>
</div>

<dialog id="modal_form" class="modal">
    <div class="modal-box w-11/12 max-w-3xl border border-base-300">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 id="modal_title" class="font-bold text-2xl mb-6 text-primary border-b pb-2">Form Permasalahan</h3>

        <form id="formMasalah" enctype="multipart/form-data">
            <input type="hidden" name="id_masalah" id="id_masalah">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Masalah</span></label>
                    <input type="text" name="nama_masalah" id="in_nama" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">PIC Masalah</span></label>
                    <select name="pic_masalah" id="in_pic" class="select select-bordered w-full" required>
                        <option value="" disabled selected>-- Pilih PIC --</option>
                        <?php foreach ($list_pic as $pic): ?>
                            <option value="<?= $pic->nama_pic; ?>"><?= $pic->nama_pic; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-semibold">Alamat / Lokasi</span></label>
                    <textarea name="alamat_masalah" id="in_alamat" class="textarea textarea-bordered h-20"></textarea>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Tanggal</span></label>
                    <input type="date" name="tgl_masalah" id="in_tgl" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nomor Rak</span></label>
                    <input type="text" name="penyimpanan_rak" id="in_rak" class="input input-bordered">
                </div>

                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-semibold">Status Masalah</span></label>
                    <select name="status_masalah" id="in_status" class="select select-bordered w-full">
                        <option value="proses">PROSES</option>
                        <option value="selesai">SELESAI</option>
                    </select>
                </div>
            </div>

            <div id="section_detail_awal">
                <div class="divider uppercase text-xs opacity-50">Deskripsi Awal</div>
                <div class="form-control">
                    <textarea name="deskripsi" class="textarea textarea-bordered" placeholder="Masukkan kronologi singkat..."></textarea>
                </div>
            </div>

            <div class="modal-action border-t pt-4 mt-6">
                <button type="button" onclick="modal_form.close()" class="btn btn-ghost">Batal</button>
                <button type="submit" id="btnSave" class="btn btn-primary px-10 text-white">Simpan Data</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    $(document).ready(function() {
        // Load data pertama kali
        load_data();

        // Fitur Pencarian Real-time
        $('#search').on('keyup', function() {
            load_data($(this).val());
        });

        // Handle Submit Form (Simpan & Update)
        $('#formMasalah').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = $('#id_masalah').val() ? "<?= base_url('masalah/update') ?>" : "<?= base_url('masalah/simpan') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('button[type="submit"]').addClass('loading');
                },
                success: function(res) {
                    $('button[type="submit"]').removeClass('loading');
                    modal_form.close();
                    $('#formMasalah')[0].reset();
                    load_data();
                    // Optional: Berikan notifikasi sukses
                }
            });
        });
    });

    function load_data(search = '') {
        $.post("<?= base_url('masalah/fetch_data') ?>", {
            search: search
        }, function(data) {
            let res = JSON.parse(data);
            $('#display-area').hide().html(res.html).fadeIn(200);
        });
    }

    function openModalTambah() {
        $('#modal_title').text('Tambah Permasalahan');
        $('#id_masalah').val('');
        $('#formMasalah')[0].reset();
        $('#detail_section').show(); // Detail diinput saat baru buat
        modal_form.showModal();
    }

    function delete_data(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?')) {
            $.post("<?= base_url('masalah/hapus/') ?>" + id, function() {
                load_data($('#search').val());
            });
        }
    }

    function cetak_label(rak, nama, alamat) {
        const printWindow = window.open('', '_blank', 'width=900,height=400');

        // Pastikan jika data null/undefined/kosong akan diisi "-"
        const displayRak = (rak && rak.trim() !== "") ? rak : "-";
        const displayNama = (nama && nama.trim() !== "") ? nama : "-";
        const displayAlamat = (alamat && alamat.trim() !== "") ? alamat : "-";

        const htmlContent = `
        <html>
        <head>
            <title>Cetak Label Strip</title>
            <style>
                @page {
                    size: A4;
                    margin: 0;
                }
                body {
                    margin: 0;
                    padding: 5mm;
                    font-family: 'Arial', sans-serif;
                    text-transform: uppercase; /* Membuat semua teks otomatis kapital */
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
                .section-rak {
                    background: #000;
                    color: #fff;
                    font-weight: 900;
                    font-size: 14pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 100px;
                    border-right: 2pt solid #000;
                    -webkit-print-color-adjust: exact;
                }
                .section-nama {
                    flex: 1;
                    font-weight: 800;
                    font-size: 10pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    border-right: 1pt solid #000;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .section-alamat {
                    flex: 1.2;
                    font-size: 8pt;
                    padding: 0 15px;
                    display: flex;
                    align-items: center;
                    color: #000;
                    line-height: 1.1;
                }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="label-strip">
                <div class="section-rak">${displayRak}</div>
                <div class="section-nama">${displayNama}</div>
                <div class="section-alamat">${displayAlamat}</div>
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

    // Fungsi Edit (Memanggil data ke modal)
    function edit_data(id) {
        $.getJSON("<?= base_url('masalah/get_by_id/') ?>" + id, function(data) {
            $('#modal_title').text('Edit Permasalahan');
            $('#id_masalah').val(data.id_masalah);
            $('[name="nama_masalah"]').val(data.nama_masalah);
            $('[name="pic_masalah"]').val(data.pic_masalah);
            $('[name="alamat_masalah"]').val(data.alamat_masalah);
            $('[name="tgl_masalah"]').val(data.tgl_masalah);
            $('[name="penyimpanan_rak"]').val(data.penyimpanan_rak);
            $('[name="status_masalah"]').val(data.status_masalah);

            $('#detail_section').hide(); // Sembunyikan input detail saat edit header
            modal_form.showModal();
        });
    }
</script>

<script>
    // Fungsi untuk memicu Modal Edit
    function edit_data(id) {
        // 1. Reset Form & Ubah Judul
        $('#formMasalah')[0].reset();
        $('#modal_title').text('Edit Permasalahan');
        $('#btnSave').text('Update Perubahan');

        // 2. Sembunyikan input deskripsi awal (karena ini Edit Header, bukan tambah log detail)
        $('#section_detail_awal').hide();

        // 3. Ambil data dari Server lewat AJAX
        $.ajax({
            url: "<?= base_url('masalah/get_by_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // 4. Masukkan data ke masing-masing input
                $('#id_masalah').val(data.id_masalah);
                $('#in_nama').val(data.nama_masalah);
                $('#in_pic').val(data.pic_masalah);
                $('#in_alamat').val(data.alamat_masalah);
                $('#in_tgl').val(data.tgl_masalah);
                $('#in_rak').val(data.penyimpanan_rak);
                $('#in_status').val(data.status_masalah);

                // 5. Tampilkan Modal
                modal_form.showModal();
            },
            error: function() {
                alert('Gagal mengambil data dari server');
            }
        });
    }

    // Fungsi untuk memicu Modal Tambah (agar judul kembali normal)
    function openModalTambah() {
        $('#id_masalah').val(''); // Kosongkan ID
        $('#formMasalah')[0].reset();
        $('#modal_title').text('Tambah Permasalahan');
        $('#btnSave').text('Simpan Data');
        $('#section_detail_awal').show(); // Tampilkan deskripsi awal
        modal_form.showModal();
    }
</script>

<script>
    let currentPage = 0;

    function loadData(isNewSearch = false) {
        if (isNewSearch) {
            currentPage = 0;
            $('#display-area').html(''); // Kosongkan list
        }

        const keyword = $('#search').val();

        $.ajax({
            url: '<?= base_url("masalah/fetch_data") ?>',
            type: 'POST',
            data: {
                search: keyword,
                page: currentPage
            },
            dataType: 'json',
            success: function(res) {
                if (res.html !== '') {
                    if (isNewSearch) {
                        $('#display-area').html(res.html);
                    } else {
                        $('#display-area').append(res.html);
                    }

                    // Cek status halaman terakhir
                    if (res.is_last_page) {
                        $('#pagination-area').addClass('hidden');
                        $('#end-of-data').removeClass('hidden');
                    } else {
                        $('#pagination-area').removeClass('hidden');
                        $('#end-of-data').addClass('hidden');
                    }
                } else if (isNewSearch) {
                    $('#display-area').html(res.html); // Tampilan "Tidak Ditemukan"
                    $('#pagination-area').addClass('hidden');
                    $('#end-of-data').addClass('hidden');
                }
            }
        });
    }

    function loadNextPage() {
        currentPage++;
        loadData(false);
    }

    $(document).ready(function() {
        loadData(true);

        // Event Search dengan Delay
        $('#search').on('keyup', function() {
            clearTimeout($.data(this, 'timer'));
            $(this).data('timer', setTimeout(function() {
                loadData(true);
            }, 500));
        });
    });
</script>