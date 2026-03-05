<div class="container mx-auto p-4 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-8 card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-4">
                <?= crsf_ajax() ?>
                <?php $this->load->view($peta) ?>
            </div>
        </div>
        <div class="lg:col-span-4 card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-6">
                <h2 class="card-title text-primary uppercase font-black tracking-widest mb-4">
                    <i class="mdi mdi-information-outline"></i> Detail
                </h2>
                <?php $this->load->view($tab) ?>
            </div>
        </div>
    </div>

    <div class="tabs tabs-boxed bg-base-200 p-2 mb-8 justify-center lg:justify-start">
        <a class="tab tab-lg flex-1 lg:flex-none font-bold <?= ($this->uri->segment(2) == 'detail') ? 'tab-active btn-primary' : '' ?>" 
           href="<?= base_url('nonlit/detail/' . $id) ?>">Detail Perkara</a>
        <a class="tab tab-lg flex-1 lg:flex-none font-bold <?= ($this->uri->segment(2) == 'tab_kronologi') ? 'tab-active btn-primary' : '' ?>" 
           href="<?= base_url('nonlit/tab_kronologi/' . $id) ?>">Berkas Pendukung</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-lg border border-base-200 sticky top-4">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-200 flex justify-between items-center bg-base-50">
                        <h3 class="font-black text-slate-700 uppercase text-sm tracking-widest">History Rapat</h3>
                        <button onclick="modal_tambah_rapat.showModal()" class="btn btn-circle btn-sm btn-primary shadow-lg">
                            <i class="mdi mdi-plus text-xl"></i>
                        </button>
                    </div>
                    
                    <ul class="menu menu-vertical p-4 gap-2" id="menu">
                        <?php foreach ($det as $key) { ?>
                            <li>
                                <a onclick="setActiveMenu(this)" id="<?= $key->id ?>" 
                                   class="flex flex-col items-start p-4 border border-base-200 hover:bg-primary hover:text-white transition-all rounded-xl">
                                    <span class="font-bold uppercase text-xs"><?= $key->judul_rapat ?></span>
                                    <span class="text-[10px] opacity-70 italic"><?= date('d M Y', strtotime($key->tgl_rapat)) ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-9">
            <div class="card bg-base-100 shadow-xl border border-base-200 min-h-[400px]">
                <div class="card-body" id="content">
                    <div class="flex flex-col items-center justify-center h-full text-center py-20 opacity-40">
                        <i class="mdi mdi-gesture-tap text-8xl mb-4 text-primary"></i>
                        <h2 class="text-2xl font-black italic uppercase">Pilih History Rapat</h2>
                        <p class="text-sm">Klik salah satu daftar rapat di samping untuk melihat notulensi detail</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_tambah_rapat" class="modal">
    <div class="modal-box max-w-4xl p-0 rounded-3xl border-none">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <h3 class="font-black text-xl uppercase tracking-tighter italic">Tambah Data Rapat</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        
        <form action="<?= base_url('nonlit/upload_berkas') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf(); ?>
            <input type="hidden" name="id_nonlit" value="<?= $id ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Tanggal Rapat</span></label>
                    <input type="date" name="tgl_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold uppercase text-xs">Acara / Judul Rapat</span></label>
                    <input type="text" name="judul_rapat" class="input input-bordered bg-base-200 rounded-xl uppercase" placeholder="Contoh: Rapat Koordinasi Aset" required>
                </div>
            </div>

            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold uppercase text-xs">Kesimpulan Rapat</span></label>
                <textarea name="kesimpulan" class="ckeditor"></textarea>
            </div>

            <div class="form-control mb-8 p-6 border-2 border-dashed border-base-300 rounded-2xl bg-base-50">
                <label class="label"><span class="label-text font-bold uppercase text-xs italic">Upload Berkas Pendukung (PDF/IMG)</span></label>
                <input type="file" name="file" class="file-input file-input-bordered file-input-primary w-full rounded-xl" />
            </div>

            <div class="modal-action flex gap-2">
                <button class="btn btn-ghost w-full rounded-xl">Batal</button> 
                <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-blue-200 rounded-xl uppercase">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</dialog>


<!-- MODAL edit data MASTER -->
 <dialog id="modal_edit_nonlit_det" class="modal">
    <div class="modal-box max-w-4xl p-0  rounded-3xl border-none shadow-2xl">
        
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="mdi mdi-pencil-box-outline text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-xl uppercase tracking-tighter">Edit Data Rapat</h3>
                    <p class="text-[10px] opacity-70 uppercase font-bold tracking-widest">Pembaharuan Notulensi & Berkas</p>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
            </form>
        </div>
        
        <form action="<?= base_url('nonlit/update_nonlit_det') ?>" method="POST" enctype="multipart/form-data" class="p-8">
            <?= crsf() ?>
            
            <input type="hidden" name="id_nonlit" id="edit_id_nonlit">
            <input type="hidden" name="id" id="edit_id_det">
            <input type="hidden" name="old_image" id="edit_berkas_old">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Tanggal Rapat</span></label>
                        <input type="date" name="tgl_rapat" id="edit_tgl_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Judul / Agenda Rapat</span></label>
                        <input type="text" name="judul_rapat" id="edit_judul_rapat" class="input input-bordered bg-base-200 rounded-xl focus:ring-2 focus:ring-primary w-full" placeholder="Masukkan judul rapat..." required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Kesimpulan</span></label>
                        <textarea name="kesimpulan" id="edit_kesimpulan" class="textarea textarea-bordered h-48 bg-base-200 rounded-xl focus:ring-2 focus:ring-primary text-sm leading-relaxed" placeholder="Tuliskan poin-poin penting hasil rapat..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Ganti Berkas (PDF/Gambar)</span></label>
                        <input type="file" name="new_image" class="file-input file-input-bordered file-input-primary w-full rounded-xl">
                        <label class="label"><span class="label-text-alt text-error italic">*Kosongkan jika tidak ingin mengubah berkas</span></label>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="label"><span class="label-text font-bold uppercase text-xs text-slate-500">Preview Berkas Saat Ini</span></label>
                    <div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-[450px] overflow-hidden flex items-center justify-center">
                        <iframe id="edit_berkas" class="w-full h-full rounded-xl shadow-inner" src="" style="display: none;"></iframe>
                        <!-- <div id="no_file_preview" class="text-center opacity-30">
                            <i class="mdi mdi-file-hidden text-6xl"></i>
                            <p class="text-xs font-bold uppercase">Tidak ada berkas</p>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="modal-action mt-8 flex gap-3 border-t pt-6 border-base-200">
                <button type="button" onclick="document.getElementById('modal_edit_nonlit_det').close()" class="btn btn-primary flex-1 text-white shadow-lg shadow-blue-100 rounded-xl uppercase font-bold" id="btnCloseDet">Batal</button> 
                <button type="submit" class="btn btn-primary flex-1 text-white shadow-lg shadow-blue-100 rounded-xl uppercase font-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog>

 

<script>
    // Inisialisasi variabel global untuk editor
let editKesimpulanEditor;

$(document).on('click', '#btnEditDet', function() {
    // 1. Ambil Data dari Atribut Button
    const id_nonlit     = $(this).data("idnonlit");
    const id            = $(this).data("id");
    const tgl_rapat     = $(this).data("tglrapat");
    const judul_rapat   = $(this).data("judulrapat");
    const kesimpulan    = $(this).data("kesimpulan");
    const berkas        = $(this).data("berkas");

    // 2. Isi Input Biasa
    $('#edit_id_nonlit').val(id_nonlit);
    $('#edit_id_det').val(id);
    $('#edit_tgl_rapat').val(tgl_rapat);
    $('#edit_judul_rapat').val(judul_rapat);
    $('#edit_berkas_old').val(berkas);
    $('#edit_kesimpulan').val(kesimpulan);

    // 3. Handle Preview Berkas
    const iframe = $('#edit_berkas');
    if (berkas) {
        iframe.attr('src', "<?= base_url() ?>assets/berkas_nonlit/" + berkas).show();
    } else {
        iframe.hide();
    }
 

    // 5. Tampilkan Modal (Gunakan ID Modal DaisyUI Anda)
    // Jika masih pakai Bootstrap: $('#modal_edit_nonlit_det').modal('show');
    // Jika sudah pakai DaisyUI:
    document.getElementById('modal_edit_nonlit_det').showModal();
});

//  

// 6. Cleanup saat Modal Ditutup
// Untuk DaisyUI/HTML5 Dialog gunakan event 'close'
// Untuk Bootstrap gunakan 'hidden.bs.modal'
$('#modal_edit_nonlit_det').on('hidden.bs.modal close', function() {
    // Reset form di dalam modal
    $(this).find('form')[0].reset();
    // Sembunyikan iframe preview
    $('#edit_berkas').attr('src', '').hide();
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
   /**
 * Fungsi untuk mengaktifkan menu History Rapat
 * @param {HTMLElement} element - Elemen <a> yang diklik
 */
function setActiveMenu(element) {
    // 1. Ambil semua item di dalam list menu
    const menuItems = document.querySelectorAll('#menu a');

    // 2. Hapus semua class active/styling khusus dari semua item
    menuItems.forEach(item => {
        item.classList.remove('bg-primary', 'text-white', 'shadow-md', 'scale-105');
        item.classList.add('bg-base-100', 'text-slate-700');
    });

    // 3. Tambahkan styling active ke elemen yang sedang diklik
    element.classList.add('bg-primary', 'text-white', 'shadow-md', 'scale-105');
    element.classList.remove('bg-base-100', 'text-slate-700');

    // 4. Tampilkan Loading State pada container konten
    const contentArea = document.getElementById('content');
    contentArea.innerHTML = `
        <div class="flex flex-col items-center justify-center h-64">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <p class="mt-4 text-xs font-bold uppercase tracking-widest animate-pulse">Memuat Notulensi...</p>
        </div>
    `;

    // 5. Jalankan AJAX untuk ambil data detail rapat
    const id = element.id;
    const token = $('#token').val();

    $.ajax({
        url: "<?= base_url('nonlit/get_content') ?>", // Sesuaikan rute Anda
        type: "POST",
        data: { id: id, token: token },
        success: function(response) {
            // Masukkan response ke area konten
            $('#content').html(response);
            
            // Opsional: Scroll otomatis ke area konten di mobile
            if (window.innerWidth < 1024) {
                contentArea.scrollIntoView({ behavior: 'smooth' });
            }
        },
        error: function() {
            $('#content').html(`
                <div class="alert alert-error shadow-lg">
                    <i class="mdi mdi-alert-circle"></i>
                    <span>Gagal mengambil data rapat. Silakan coba lagi.</span>
                </div>
            `);
        }
    });
}
</script>