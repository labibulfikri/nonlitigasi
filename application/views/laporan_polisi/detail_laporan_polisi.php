<div class="container mx-auto p-4 space-y-6">
    <div class="card bg-base-100 shadow-sm border border-base-200 rounded-[2rem] overflow-hidden">
        <div class="bg-primary h-2 w-full"></div>
        <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex-1 space-y-3">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <h1 class="text-3xl font-black text-primary uppercase italic tracking-tighter leading-none">
                        <?= $laporan->judul_laporan_polisi ?>
                    </h1>
                    <div class="badge badge-primary badge-outline border-2 font-black italic uppercase text-[10px] tracking-widest px-4 py-3 shadow-sm">
                        <?= $laporan->status_laporan_polisi ?>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <div class="flex items-center text-[11px] font-bold text-base-content/60 uppercase tracking-widest bg-base-200/50 px-3 py-1.5 rounded-lg border border-base-300/50">
                        <i class="mdi mdi-map-marker text-primary mr-2 text-sm"></i>
                        <?= $laporan->alamat_laporan_polisi ?>
                    </div>

                    <!-- <div class="flex items-center text-[11px] font-bold text-base-content/40 uppercase tracking-widest">
                        <i class="mdi mdi-clock-outline mr-2 text-sm"></i>
                        Diperbarui: <?= date('d M Y') ?>
                    </div> -->
                </div>
            </div>

            <div class="flex gap-3">
                <div class="bg-base-200/50 p-4 rounded-2xl border border-base-200 min-w-[120px] text-center">
                    <span class="text-[9px] font-black opacity-30 uppercase block mb-1">PIC TERKAIT</span>
                    <p class="text-xs font-black text-secondary uppercase"><?= $laporan->pic_laporan_polisi ?></p>
                </div>
                <div class="bg-base-200/50 p-4 rounded-2xl border border-base-200 min-w-[120px] text-center">
                    <span class="text-[9px] font-black opacity-30 uppercase block mb-1">NOMOR POLISI</span>
                    <p class="text-xs font-black uppercase"><?= $laporan->nomor_polisi ?></p>
                </div>
                <div class="bg-base-200/50 p-4 rounded-2xl border border-base-200 min-w-[120px] text-center">
                    <span class="text-[9px] font-black opacity-30 uppercase block mb-1">TGL. REGISTRASI</span>
                    <p class="text-xs font-black uppercase"><?= $laporan->tgl_laporan_polisi ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <div class="lg:col-span-3 card bg-base-100 shadow-sm border border-base-200 rounded-[2rem] p-6 h-fit">
            <div class="flex justify-between items-center mb-6 px-2">
                <h3 class="text-[10px] font-black uppercase tracking-widest opacity-40">Daftar Agenda</h3>
                <button onclick="tambahAgenda()" class="btn btn-primary btn-circle btn-xs">
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                <?php foreach ($histori as $h): ?>
                    <div onclick="loadAgenda(this)" data-id="<?= $h->id_laporan_polisi_det ?>"
                        class="group card bg-base-100 border border-base-200 hover:bg-primary transition-all cursor-pointer shadow-sm active:scale-95">
                        <div class="p-4 group-hover:text-white">
                            <h4 class="font-black text-sm uppercase leading-tight"><?= $h->agenda_laporan_polisi_det ?></h4>
                            <p class="text-[9px] font-bold opacity-50 mt-1 uppercase"><?= date('d M Y', strtotime($h->tgl_agenda_laporan_polisi_det)) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-9 card bg-base-100 shadow-sm border border-base-200 rounded-[2rem] p-10 min-h-[600px]" id="detail_agenda_content">
            <div class="flex flex-col items-center justify-center h-full opacity-20 italic">
                <i class="mdi mdi-text-search text-7xl mb-4"></i>
                <p class="font-black uppercase tracking-widest text-sm text-center">Pilih agenda di samping untuk melihat detail</p>
            </div>
        </div>

    </div>
</div>


<dialog id="modal_agenda" class="modal">
    <div class="modal-box max-w-2xl p-0 rounded-[2rem]  border-none shadow-2xl">
        <div class="bg-secondary p-8 text-secondary-content flex justify-between items-center">
            <h3 class="font-black text-2xl uppercase italic tracking-tighter" id="title_agenda">Agenda Baru</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>

        <form action="<?= base_url('laporan_polisi/save_detail') ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-5">
            <input type="hidden" name="id_laporan_polisi" value="<?= $laporan->id_laporan_polisi ?>">
            <input type="hidden" name="id_laporan_polisi_det" id="edit_id_det">

            <div class="form-control">
                <label class="label text-[10px] font-black opacity-40 uppercase">Agenda / Kegiatan</label>
                <input type="text" name="agenda" id="edit_agenda" class="input input-bordered rounded-2xl font-bold uppercase" required>
            </div>

            <div class="form-control">
                <label class="label text-[10px] font-black opacity-40 uppercase">Tanggal Pelaksanaan</label>
                <input type="date" name="tgl_agenda" id="edit_tgl" class="input input-bordered rounded-2xl" required>
            </div>

            <div class="form-control">
                <label class="label text-[10px] font-black opacity-40 uppercase">Kesimpulan / Uraian</label>
                <textarea name="kesimpulan" id="edit_kesimpulan" class="textarea textarea-bordered h-32 rounded-2xl font-medium" required></textarea>
            </div>

            <div class="form-control">
                <label class="label text-[10px] font-black opacity-40 uppercase">Update Berkas (PDF/JPG)</label>

                <div id="preview_berkas_area" class="mb-3 hidden">
                    <div class="relative w-24 h-24 group">
                        <div id="thumb_container" class="w-full h-full rounded-xl overflow-hidden border-2 border-secondary/30 shadow-sm">
                        </div>
                        <div class="absolute -top-2 -right-2 bg-secondary text-white rounded-full p-1 shadow-md">
                            <i class="mdi mdi-check-circle text-xs"></i>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-secondary mt-1 uppercase italic">Berkas saat ini terpasang</p>
                </div>

                <input type="file" name="berkas" class="file-input file-input-bordered file-input-secondary w-full rounded-2xl" />
            </div>

            <button type="submit" class="btn btn-secondary w-full rounded-2xl uppercase font-black text-white shadow-lg py-4 mt-4">
                Simpan Agenda & Berkas
            </button>
        </form>
    </div>
</dialog>


<script>
    function editAgenda(id) {
        $.getJSON("<?= base_url('laporan_polisi/get_det_by_id/') ?>" + id, function(data) {
            $('#edit_id_det').val(data.id_laporan_polisi_det);
            $('#edit_agenda').val(data.agenda_laporan_polisi_det);
            $('#edit_tgl').val(data.tgl_agenda_laporan_polisi_det);
            $('#edit_kesimpulan').val(data.kesimpulan);

            // Logika Thumbnail
            if (data.berkas_laporan) {
                const fileExt = data.berkas_laporan.split('.').pop().toLowerCase();
                let htmlThumb = '';

                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                    // Jika Gambar, tampilkan fotonya langsung
                    htmlThumb = `<img src="<?= base_url('assets/berkas_laporan/') ?>${data.berkas_laporan}" class="w-full h-full object-cover">`;
                } else if (fileExt === 'pdf') {
                    // Jika PDF, tampilkan ikon PDF
                    htmlThumb = `<div class="w-full h-full bg-error/10 flex items-center justify-center text-error">
                                <i class="mdi mdi-file-pdf-box text-4xl"></i>
                             </div>`;
                }

                $('#thumb_container').html(htmlThumb);
                $('#preview_berkas_area').removeClass('hidden');
            } else {
                $('#preview_berkas_area').addClass('hidden');
            }

            $('#title_agenda').text('Edit Agenda');
            document.getElementById('modal_agenda').showModal();
        });
    }

    function tambahAgenda() {
        // Reset form dan sembunyikan thumbnail saat tambah baru
        $('#edit_id_det').val('');
        $('#preview_berkas_area').addClass('hidden');
        // ... reset input lainnya ...
        $('#edit_agenda').val('');
        $('#edit_tgl').val('');
        $('#edit_kesimpulan').val('');

        $('#title_agenda').text('Tambah Agenda Baru');
        document.getElementById('modal_agenda').showModal();
    }

    function hapusAgenda(id) {
        Swal.fire({
            title: 'HAPUS AGENDA?',
            text: "Data perkembangan laporan ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('laporan_polisi/hapus_det') ?>", {
                    id: id
                }, function() {
                    location.reload();
                });
            }
        });
    }
</script>
<script>
    function loadAgenda(el) {
        // Memberi efek "Active" pada card yang diklik
        $('.group.card').removeClass('bg-primary text-white');
        $(el).addClass('bg-primary text-white');

        const id = $(el).data('id');
        $('#detail_agenda_content').html('<div class="flex justify-center p-20"><span class="loading loading-spinner loading-lg text-primary"></span></div>');

        $.post("<?= base_url('laporan_polisi/get_agenda_content') ?>", {
            id: id
        }, function(res) {
            $('#detail_agenda_content').hide().html(res).fadeIn(300);
        });
    }
</script>