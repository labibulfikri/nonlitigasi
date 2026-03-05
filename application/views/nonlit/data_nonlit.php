 <style>
    .card-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid transparent;
    }
    .card-row:hover {
        transform: translateX(8px);
        border-left-color: #2563eb; /* Warna biru saat hover */
        background-color: #f8fafc;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style> 
<div class="p-4 md:p-8 max-w-7xl mx-auto min-h-screen bg-slate-50">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">DATA NON-LITIGASI</h1>
            <p class="text-slate-500 text-sm font-medium">BPKAD System • <span id="total-count">0</span> Data ditemukan</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative flex-grow">
                <i class="mdi mdi-magnify absolute left-3 top-2.5 text-slate-400 text-xl"></i>
                <input type="text" id="search-input" placeholder="Cari permohonan..." class="input input-bordered pl-10 w-full rounded-xl focus:ring-2 focus:ring-blue-500">
            </div>
            <button onclick="modal_tambah.showModal()" class="btn btn-primary rounded-xl px-6">
                <i class="mdi mdi-plus-circle text-lg"></i>
            </button>
        </div>
    </div>
    <?= crsf_ajax() ?>
    <div id="card-list" class="grid grid-cols-1 gap-4">
        <div class="col-span-full flex flex-col items-center py-20 opacity-50">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <p class="mt-4 font-bold text-slate-500">Memuat data perkara...</p>
        </div>
    </div>

    <div class="mt-10 flex justify-center pb-10">
        <div class="join bg-white shadow-sm border border-slate-200 rounded-xl" id="pagination-wrapper">
            </div>
    </div>
</div>
<dialog id="modal_tambah" class="modal">
    <div class="modal-box max-w-3xl bg-white p-0 rounded-3xl border-none shadow-2xl">
        <div class="p-6 bg-blue-600 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg"><i class="mdi mdi-plus-circle text-2xl"></i></div>
                <div>
                    <h3 class="font-black text-lg leading-none">TAMBAH PERKARA</h3>
                    <p class="text-xs text-blue-100 mt-1 uppercase tracking-wider">Input Data Non-Litigasi Baru</p>
                </div>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>
        
        <form id="formSimpan" class="p-8">
            <?= crsf_ajax() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                    <input type="text" name="permohonan_nonlit" class="input input-bordered bg-slate-50 focus:ring-2 focus:ring-blue-500 rounded-xl" placeholder="Contoh: Permohonan Pendampingan Hukum..." required>
                </div>

                  <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Alamat Terkait</span></label>
                    <input type="text" name="alamat" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan alamat lokasi jika ada">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Team Non-Litigasi</span></label>
                    <select name="team_nonlit" class="select select-bordered bg-slate-50 rounded-xl">
                        <option disabled selected>Pilih Team...</option>
                        <option value="kejati">KEJAKSAAN TINGGI JAWA TIMUR</option>
                        <option value="kejari_sby">KEJAKSAAN NEGERI SURABAYA</option>
                        <option value="kejari_perak">KEJAKSAAN NEGERI TANJUNG PERAK</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Bidang</span></label>
                    <select name="bidang" class="select select-bordered bg-slate-50 rounded-xl">
                        <option disabled selected>Pilih Bidang...</option>
                        <option value="ppsbmd">PPSBMD</option>
                        <option value="pppbmd">PPPBMD</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">PIC Perkara</span></label>
                    <select name="pic" class="select select-bordered bg-slate-50 rounded-xl">
                          <option disabled selected>Pilih PIC...</option>
                        <option value="cavita">CAVITA</option>
                        <option value="rendy">RENDY</option>
                        <option value="widi">WIDI</option>
                        <option value="qowi">QOWI</option>
                        <option value="dennis">DENNIS</option>
                        <option value="andi">ANDI</option>
                        <option value="elia">ELIA</option>
                        <option value="iqbal">IQBAL</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Tanggal Non-Litigasi</span></label>
                    <input type="date" name="tgl_nonlit" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nomor Register Baru</span></label>
                    <input type="text" name="register_baru" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan No. Register">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Luas</span></label>
                    <input type="text" name="luas" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Luas">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Penyimpanan Rak</span></label>
                    <div class="relative">
                        <i class="mdi mdi-archive absolute left-4 top-3 text-slate-400"></i>
                        <input type="text" name="penyimpanan_rak" class="input input-bordered w-full pl-10 bg-slate-50 rounded-xl" placeholder="Contoh: R.01-A">
                    </div>
                </div>

              
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Status Perkara</span></label>
                    <select name="status" class="select select-bordered bg-slate-50 rounded-xl font-bold">
                        <option value="proses">PROSES</option>
                        <option value="selesai">SELESAI</option>
                    </select>
                </div>
            </div>

            <div class="form-control mt-6">
                <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Keterangan Detail</span></label>
                <textarea name="keterangan" class="textarea textarea-bordered h-24 bg-slate-50 rounded-xl" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="modal-action flex gap-3 mt-10"> 
                    <button type="button" onclick="modal_tambah.close()" class="btn btn-primary flex-1 shadow-lg shadow-blue-200 rounded-xl font-bold italic">Batalkan</button> 
                <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-blue-200 rounded-xl font-bold italic">
                    <i class="mdi mdi-content-save mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_edit" class="modal">
    <div class="modal-box max-w-3xl bg-white p-0 rounded-3xl border-none shadow-2xl">
        <div class="p-6 bg-amber-500 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg"><i class="mdi mdi-pencil-box-multiple text-2xl"></i></div>
                <div>
                    <h3 class="font-black text-lg leading-none uppercase">UPDATE PERKARA</h3>
                    <p class="text-xs text-amber-50 mt-1 uppercase tracking-wider italic">ID Perkara: <span id="display_edit_id"></span></p>
                </div>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>
        
        <form id="formUpdate" class="p-8">
            <?= crsf_ajax() ?>
            <input type="hidden" name="id" id="edit_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                    <input type="text" name="permohonan_nonlit" id="edit_permohonan" class="input input-bordered bg-slate-50 rounded-xl uppercase" required>
                </div>
<div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Alamat Terkait</span></label>
                    <input type="text" name="alamat" id="edit_alamat" class="input input-bordered bg-slate-50 rounded-xl">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Team Non-Litigasi</span></label>
                    <select name="bidang" id="edit_bidang" class="select select-bordered bg-slate-50 rounded-xl">
                        <option disabled selected>Pilih Bidang...</option>
                        <option value="ppsbmd">PPSBMD</option>
                        <option value="pppbmd">PPPBMD</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Kejaksaan</span></label>
                    <select name="team_nonlit" id="edit_team_nonlit" class="select select-bordered bg-slate-50 rounded-xl">
                         <option disabled selected>Pilih Team...</option>
                        <option value="kejati">KEJAKSAAN TINGGI JAWA TIMUR</option>
                        <option value="kejari_sby">KEJAKSAAN NEGERI SURABAYA</option>
                        <option value="kejari_perak">KEJAKSAAN NEGERI TANJUNG PERAK</option>
                   
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">PIC Perkara</span></label>
                    <select name="pic" id="edit_pic" class="select select-bordered bg-slate-50 rounded-xl">
                        <option disabled selected>Pilih PIC...</option>
                        <option value="cavita">CAVITA</option>
                        <option value="rendy">RENDY</option>
                        <option value="widi">WIDI</option>
                        <option value="qowi">QOWI</option>
                        <option value="dennis">DENNIS</option>
                        <option value="andi">ANDI</option>
                        <option value="elia">ELIA</option>
                        <option value="iqbal">IQBAL</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Tanggal Non-Litigasi</span></label>
                    <input type="date" name="tgl_nonlit" id="edit_tgl_nonlit" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nomor Register Baru</span></label>
                    <input type="text" name="register_baru" id="edit_register_baru" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Penyimpanan Rak</span></label>
                    <input type="text" name="penyimpanan_rak" id="edit_penyimpanan_rak" class="input input-bordered bg-slate-50 rounded-xl">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Luas </span></label>
                    <input type="text" name="luas" id="edit_luas" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Status Perkara</span></label>
                    <select name="status" id="edit_status" class="select select-bordered bg-slate-50 rounded-xl font-bold">
                        <option value="proses">PROSES</option>
                        <option value="selesai">SELESAI</option>
                    </select>
                </div>
            </div>

            <div class="form-control mt-6">
                <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Keterangan Detail</span></label>
                <textarea name="keterangan" id="edit_keterangan" class="textarea textarea-bordered h-24 bg-slate-50 rounded-xl"></textarea>
            </div>

            <div class="modal-action flex gap-3 mt-10">
                <button type="button" onclick="modal_edit.close()" class="btn btn-ghost flex-1 rounded-xl">Batalkan</button>
                <button type="submit" class="btn btn-warning text-white flex-1 shadow-lg rounded-xl font-bold italic">
                    <i class="mdi mdi-update mr-2"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let searchQuery = "";

    // Fungsi utama mengambil data
    function loadData(page = 1, search = "") {
        const token = $('#token').val();
        
        $.ajax({
            url: "<?= base_url('nonlit/fetch_nonlit') ?>",
            type: "POST",
            data: {
                start: (page - 1) * 10, // DataTables parameter tetap kita kirim
                length: 10,
                draw: 1,
                search: { value: search },
                token: token
            },
            dataType: "json",
            success: function(response) {
               // 1. Simpan data ke globalData untuk keperluan Edit
            globalData = response.data; 
            
            // 2. Render kartu ke layar (Cukup panggil sekali)
            renderCards(globalData);
            
            // 3. Update informasi pagination
            renderPagination(response.recordsFiltered, page);
            $('#total-count').text(response.recordsFiltered);
            },
            error: function() {
                $('#card-list').html('<div class="col-span-full text-center text-red-500">Gagal memuat data. Periksa koneksi server.</div>');
            }
        });
    }


    function renderCards(data) {
    let html = '';
    if (data.length === 0) {
        html = `
            <div class="bg-white p-20 text-center rounded-3xl border-2 border-dashed border-slate-200">
                <i class="mdi mdi-folder-open-outline text-6xl text-slate-200"></i>
                <p class="text-slate-400 font-bold mt-4">Belum ada data perkara yang tersimpan</p>
            </div>`;
    } else {
        data.forEach(item => {
            const isSelesai = item.status.toLowerCase() === 'selesai';
            const statusTheme = isSelesai 
                ? 'bg-emerald-100 text-emerald-700 border-emerald-200' 
                : 'bg-amber-100 text-amber-700 border-amber-200';
            
            html += `
            <div class="card-row bg-white rounded-2xl border border-slate-200 p-5 mb-4 shadow-sm group hover:shadow-md transition-all">
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                    
                    <div class="flex lg:flex-col items-center justify-between lg:justify-center min-w-[110px] w-full lg:w-auto gap-2">
                        <div class="px-3 py-1.5 rounded-full border ${statusTheme} text-[10px] font-black uppercase tracking-wider shadow-sm">
                            ${item.status}
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-400">#${item.no}</span>
                    </div>

                    <div class="flex-grow w-full">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-tighter">${item.team_nonlit || ' - '}</span>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-tighter">• Reg: ${item.register_baru || '-'}</span>
                            
                        </div>

                        
                        <h3 class="text-slate-800 font-black text-sm lg:text-base leading-snug mb-3 uppercase group-hover:text-blue-600 transition-colors">
                            ${item.permohonan_nonlit}
                        </h3>
                        <div class="relative bg-slate-50 rounded-xl p-3 border-l-4 border-blue-500 mb-4">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-600 rounded-lg p-1.5">
                        <i class="mdi mdi-clock-fast text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-0.5">Update Progres Terakhir</p>
                        <p class="text-slate-700 font-bold text-xs leading-relaxed italic">
                            "${item.kesimpulan || 'Belum ada update kesimpulan dari hasil rapat.'}"
                        </p>
                    </div>
                </div>
            </div>
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-tighter">Alamat: ${item.alamat}</span>
                         
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <i class="mdi mdi-account-tie-outline text-lg text-blue-500"></i>
                                <div>
                                    <p class="text-slate-400 text-[9px] uppercase font-bold leading-none mb-1">PIC</p>
                                    <p class="text-slate-700 font-bold">${item.pic}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <i class="mdi mdi-calendar-check-outline text-lg text-emerald-500"></i>
                                <div>
                                    <p class="text-slate-400 text-[9px] uppercase font-bold leading-none mb-1">Tanggal</p>
                                    <p class="text-slate-700 font-bold">${item.tgl_nonlit}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <i class="mdi mdi-archive-marker-outline text-lg text-purple-500"></i>
                                <div>
                                    <p class="text-slate-400 text-[9px] uppercase font-bold leading-none mb-1">Rak</p>
                                    <p class="text-slate-700 font-bold">${item.penyimpanan_rak}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex lg:flex-col flex-row items-center gap-3 w-full lg:w-auto lg:pl-6 lg:border-l border-slate-100 justify-end">
                        <a href="<?= base_url('nonlit/detail/') ?>${item.id}" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all shadow-sm" 
                           title="Detail">
                            <i class="mdi mdi-eye-outline text-xl"></i>
                        </a>
                        <button onclick="editData(${item.id})" 
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                title="Edit">
                            <i class="mdi mdi-pencil-outline text-xl"></i>
                        </button>
                        <button onclick="hapusData(${item.id})" 
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" 
                                title="Hapus">
                            <i class="mdi mdi-trash-can-outline text-xl"></i>
                        </button>
                    </div>
                    
                </div>
            </div>`;
        });
    }
    $('#card-list').html(html);
} 
    // Fungsi Render Pagination Manual
    function renderPagination(totalRecords, activePage) {
        let totalPages = Math.ceil(totalRecords / 9);
        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            let activeClass = i === activePage ? 'btn-active bg-blue-600 text-white' : '';
            html += `<button class="join-item btn btn-sm ${activeClass} page-link" data-page="${i}">${i}</button>`;
        }
        $('#pagination-wrapper').html(html);
    }

    // Event Pencarian
    $('#search-input').on('keyup', function() {
        searchQuery = $(this).val();
        currentPage = 1;
        loadData(currentPage, searchQuery);
    });

    // Event Klik Pagination
    $(document).on('click', '.page-link', function() {
        currentPage = $(this).data('page');
        loadData(currentPage, searchQuery);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

// Handle Simpan (Tambah Baru)
$('#formSimpan').on('submit', function(e) {
    e.preventDefault();
    
    // Animasi loading pada tombol simpan
    const btnSubmit = $(this).find('button[type="submit"]');
    const originalText = btnSubmit.html();
    btnSubmit.prop('disabled', true).html('<span class="loading loading-spinner loading-xs"></span> Menyimpan...');

    $.ajax({
        url: "<?= base_url('nonlit/tambah_data_nonlit') ?>",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            console.log(response);
            // 1. Tutup Modal Tambah
            modal_tambah.close();

            // 2. Reset Form agar kosong saat dibuka kembali
            $('#formSimpan')[0].reset();

            // 3. Tampilkan Alert Berhasil
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data perkara baru telah berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: '#2563eb', // Warna biru sesuai tema tambah
            }).then(() => {
                // 4. Refresh data card tanpa reload halaman
                loadData(); 
            });
        },
        error: function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan sistem saat menyimpan data.',
                icon: 'error'
            });
        },
        complete: function() {
            // Kembalikan tombol ke kondisi semula
            btnSubmit.prop('disabled', false).html(originalText);
        }
    });
});

$('#formUpdate').on('submit', function(e) {
    e.preventDefault();
    
    // Tampilkan loading sebentar agar user tahu proses sedang berjalan
    const btnSubmit = $(this).find('button[type="submit"]');
    const originalText = btnSubmit.html();
    btnSubmit.prop('disabled', true).html('<span class="loading loading-spinner loading-xs"></span> Menyimpan...');

    $.ajax({
        url: "<?= base_url('nonlit/update_data') ?>",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        dataType: "json", // Pastikan controller mengirimkan json_encode
        success: function(response) {
            // 1. Tutup Modal Edit
            modal_edit.close();

            // 2. Munculkan Alert Berhasil
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data perkara telah diperbarui.',
                icon: 'success',
                confirmButtonColor: '#f59e0b', // Warna amber sesuai tema edit
            }).then(() => {
                // 3. Refresh data di Card tanpa reload halaman
                loadData(); 
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui data.',
                icon: 'error'
            });
        },
        complete: function() {
            // Kembalikan tombol ke kondisi semula
            btnSubmit.prop('disabled', false).html(originalText);
        }
    });
});

    // Load Pertama Kali
    loadData();
});
</script>


<script>
    
    let globalData = [];
function editData(id) {
    const item = globalData.find(d => d.id == id);
    
    if (item) {
        // Mengisi ID dan Header
        $('#edit_id').val(item.id);
        $('#display_edit_id').text(item.id);

        // Mengisi Field Text & Select
        $('#edit_permohonan').val(item.permohonan_nonlit);
        $('#edit_team_nonlit').val(item.team_nonlit);
        $('#edit_bidang').val(item.bidang);
        $('#edit_pic').val(item.pic);
        $('#edit_tgl_nonlit').val(item.tgl_nonlit_raw); // Pastikan tgl formatnya YYYY-MM-DD
        $('#edit_register_baru').val(item.register_baru);
        $('#edit_penyimpanan_rak').val(item.penyimpanan_rak);
        $('#edit_luas').val(item.luas);
        $('#edit_alamat').val(item.alamat);
        $('#edit_status').val(item.status);
        $('#edit_keterangan').val(item.keterangan);
        
        modal_edit.showModal();
    }
}

</script>
<script>
    function hapusData(id) {
        var token = $('#token').val()
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // warna merah tailwind
        cancelButtonColor: '#64748b', // warna slate tailwind
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('nonlit/remove_nonlit') ?>", // pastikan route ini ada di controller
                type: "POST",
                data: { 
                    id_nonlit: id,
                    token:token
                },
                success: function(response) {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success')
                    .then(() => {
                        // Reload data tanpa refresh halaman penuh
                        location.reload(); 
                    });
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
        }
    });
}
</script>