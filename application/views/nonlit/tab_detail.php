
<style>
    .swal2-container {
    z-index: 999999 !important;
}
    </style>

<div class="p-2 text-base-content">
    <div class="flex items-center gap-2 mb-6 border-b border-base-300 pb-2">
        <div class="w-2 h-8 bg-primary rounded-full"></div>
        <h3 class="font-black text-lg uppercase tracking-wider">Informasi Master</h3>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="stats shadow bg-base-200 border border-base-300 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase text-primary">No. Rak</div>
                    <div class="stat-value text-xl font-black"><?= $master['penyimpanan_rak'] ?></div>
                    <div class="stat-desc text-[10px] italic opacity-70">Lokasi Arsip Fisik</div>
                </div>
            </div>
            <!-- <div class="stats shadow bg-base-200 border border-base-300 flex-1">
                <div class="stat p-4">
                    <div class="stat-title text-[10px] font-bold uppercase opacity-60">No. Register</div>
                    <div class="stat-value text-xl font-black"><?= $master['register_baru'] ?></div>
                </div>
            </div> -->

            <div class="stats shadow bg-base-200 border border-base-300 flex-1">
    <div class="stat p-4">
        <div class="stat-title text-[10px] font-bold uppercase opacity-60">No. Register (Klik untuk Fokus)</div>
        <div class="stat-value text-xl font-black flex flex-wrap gap-2">
            <?php 
            $regs = array_map('trim', explode(';', $master['register_baru']));
            foreach ($regs as $r): ?>
                <span class="badge badge-outline cursor-pointer hover:bg-blue-600 hover:text-white transition-all btn-focus-map" 
                      data-reg="<?= $r ?>">
                    <?= $r ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-4">
                <label class="text-[10px] font-bold uppercase opacity-50 tracking-widest">Permohonan Non-Litigasi</label>
                <p class="font-bold leading-tight uppercase text-primary"><?= $master['permohonan_nonlit'] ?></p>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-primary/10 p-4 rounded-2xl border border-primary/20">
            <div class="avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full w-10">
                    <span class="text-xs font-bold"><?= substr($master['team_nonlit'], 0, 2) ?></span>
                </div>
            </div>
            <div>
                <label class="text-[9px] font-bold uppercase text-primary block">Team Pelaksana</label>
                <span class="font-black text-base-content"><?= $master['team_nonlit'] ?></span>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-base-200 rounded-2xl border border-base-300">
            <input type="checkbox" checked />
            <div class="collapse-title text-xs font-bold uppercase opacity-60">
                Keterangan Detail
            </div>
            <div class="collapse-content text-sm leading-relaxed opacity-80">
                <p><?= nl2br($master['keterangan']) ?> </p>
            </div>
        </div>
    </div>
    <br/>
    <button onclick="edit_data(<?= $master['id'] ?>)" class="btn btn-sm btn-warning text-white shadow-md shadow-amber-100 rounded-lg group">
    <i class="mdi mdi-pencil-outline transition-transform group-hover:rotate-12"></i>
    EDIT
</button>           
</div>
 <dialog id="modal_edit" class="modal">
    <div class="modal-box max-w-3xl bg-white p-0 rounded-3xl border-none shadow-2xl">
        <div class="p-6 bg-amber-500 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg"><i class="mdi mdi-pencil-box-multiple text-2xl"></i></div>
                <div>
                    <h3 class="font-black text-lg leading-none uppercase">UPDATE DATA</h3>
                    <p class="text-xs text-amber-50 mt-1 uppercase tracking-wider italic">ID Perkara: <span id="display_edit_id"></span></p>
                </div>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
        </div>

        <form id="formUpdate2" class="p-8">
           <?= crsf_ajax() ?>
            <input type="hidden" name="id" id="edit_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Jenis Data</span></label>
                    <select name="jenis" id="edit_jenis" class="select select-bordered bg-amber-50 border-amber-200 focus:ring-2 focus:ring-amber-500 rounded-xl font-bold" required onchange="toggleInstansiUpdate()">
                        <option value="nonlit">NON-LITIGASI (KEJAKSAAN)</option>
                        <option value="laporan_polisi">LAPORAN POLISI (KEPOLISIAN)</option>
                        <option value="permasalahan">PERMASALAHAN</option>
                        <option value="data_umum">DATA UMUM</option>
                    </select>
                </div>

                <div class="form-control hidden" id="container_tanggal_update">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Tanggal</span></label>
                    <input type="date" name="tgl_nonlit" id="edit_tgl_nonlit" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                <div id="container_instansi_update" class="form-control col-span-full hidden border-l-4 border-amber-500 bg-amber-50/50 p-4 rounded-r-xl">
                    <label class="label"><span id="label_instansi_update" class="label-text font-bold text-amber-700 uppercase text-[11px]">Team / Instansi Terkait</span></label>
                    <select name="team_nonlit" id="edit_team_nonlit" class="select select-bordered bg-white rounded-xl"></select>
                </div>

                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                    <input type="text" name="permohonan_nonlit" id="edit_permohonan" class="input input-bordered bg-slate-50 rounded-xl uppercase font-bold" required>
                </div>

                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Alamat Terkait</span></label>
                    <input type="text" name="alamat" id="edit_alamat" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan alamat lokasi jika ada">
                </div>

                <!-- <div class="form-control col-span-full">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Register Baru</span></label>
                    <input type="text" name="register_baru" id="edit_register_baru" class="input input-bordered bg-slate-50 rounded-xl uppercase font-bold" required>
                </div> -->

                <div class="form-control col-span-full">
    <label class="label">
        <span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nomor Register Baru</span>
    </label>
    <select name="register_baru[]" id="edit_register_baru" multiple="multiple" class="w-full">
        <!-- Opsi akan diisi secara dinamis melalui JavaScript saat tombol edit diklik -->
    </select>
    <p class="text-[10px] text-slate-400 mt-2">*Tekan Enter untuk menambah atau mengubah nomor register</p>
</div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Bidang</span></label>
                    <select name="bidang" id="edit_bidang" class="select select-bordered bg-slate-50 rounded-xl">
                        <option value="ppsbmd">PPSBMD</option>
                        <option value="pppbmd">PPPBMD</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">PIC Perkara</span></label>
                    <div class="relative">
                        <i class="mdi mdi-account-circle absolute left-4 top-3 text-slate-400"></i>
                        <select name="id_pic" id="edit_id_pic" class="select select-bordered w-full pl-10 bg-slate-50 rounded-xl">
                            <option disabled selected>Pilih PIC...</option>
                            <?php foreach ($list_pic as $pic) : ?>
                                <option value="<?= $pic->id ?>"><?= $pic->nama_pic ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="edit_nama_pic" name="pic">
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Penyimpanan Rak</span></label>
                    <input type="text" name="penyimpanan_rak" id="edit_penyimpanan_rak" class="input input-bordered bg-slate-50 rounded-xl">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Status</span></label>
                    <select name="status" id="edit_status" class="select select-bordered bg-slate-50 rounded-xl font-bold text-amber-600">
                        <option value="proses">PROSES</option>
                        <option value="selesai">SELESAI</option>
                    </select>
                </div> 
            </div>

            <div class="form-control mt-4">
                <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Keterangan Detail</span></label>
                <textarea name="keterangan" id="edit_keterangan" class="textarea textarea-bordered h-24 bg-slate-50 rounded-xl" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="modal-action flex gap-3 mt-10">
                <button type="button" onclick="modal_edit.close()" class="btn btn-ghost flex-1 rounded-xl uppercase font-bold">Batal</button>
                <button type="submit" class="btn btn-warning text-white flex-1 shadow-lg shadow-amber-200 rounded-xl font-bold uppercase italic">
                    <i class="mdi mdi-update mr-2"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</dialog>
<script>  


$(document).ready(function() {
    // 1. Inisialisasi awal Select2 pada Modal Update
    const modalUpdate = document.getElementById('modal_edit'); // Sesuaikan ID modal update Anda
    
    // Inisialisasi Select2
    $('#edit_register_baru').select2({
        tags: true,
        tokenSeparators: [',', ';', ' '],
        placeholder: "Masukkan Nomor Register...",
        width: '100%',
        dropdownParent: $('#modal_edit .modal-box') // Agar tidak terkunci di modal DaisyUI
    });
});
function edit_data(id) {
    $.ajax({
        url: "<?= base_url('nonlit/get_data_by_id/') ?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

        // SINKRONISASI SELECT2 REGISTER_BARU
            let selectElement = $('#edit_register_baru');
            selectElement.empty(); // Kosongkan opsi lama

            if (data.register_baru) {
                // Pecah string "123; 456" menjadi array
                let regs = data.register_baru.split(';').map(item => item.trim());
                
                regs.forEach(function(reg) {
                    if (reg !== "") {
                        // Buat opsi baru dan tandai sebagai terpilih (selected)
                        let newOption = new Option(reg, reg, true, true);
                        selectElement.append(newOption);
                    }
                });
            }
            
            // Trigger refresh agar Select2 menampilkan tags yang baru ditambahkan
            selectElement.trigger('change');
            $('#display_edit_id').text(data.id);
            $('#edit_id').val(data.id);
            $('#edit_jenis').val(data.jenis);
            // $('#edit_register_baru').val(data.register_baru);
            
            // PENTING: Jalankan toggle dan isi nilai instansi
            toggleInstansiUpdate(data.team_nonlit);

            $('#edit_tgl_nonlit').val(data.tgl_nonlit);
            $('#edit_permohonan').val(data.permohonan_nonlit);
            $('#edit_alamat').val(data.alamat);
            $('#edit_bidang').val(data.bidang);
            if (data.id_pic) {
                $('#edit_id_pic').val(data.id_pic);
                // Ambil nama dari option yang terpilih dan masukkan ke hidden input 'pic'
                const selectedText = $("#edit_id_pic option:selected").text();
                $('#edit_nama_pic').val(selectedText);
            } else {
                $('#edit_id_pic').val('');
                $('#edit_nama_pic').val('');
            }
            $('#edit_penyimpanan_rak').val(data.penyimpanan_rak);
            $('#edit_status').val(data.status);
            $('#edit_keterangan').val(data.keterangan);

            modal_edit.showModal();
        }
    });
}

// Logic Tampilan Form Dinamis
function toggleInstansiUpdate(selectedValue = null) {
    const jenis = $('#edit_jenis').val();
    const containerTanggal = $('#container_tanggal_update');
    const containerInstansi = $('#container_instansi_update');
    const selectInstansi = $('#edit_team_nonlit');
    const labelInstansi = $('#label_instansi_update');

    selectInstansi.empty();

    if (jenis === 'nonlit') {
        containerTanggal.removeClass('hidden');
        containerInstansi.removeClass('hidden'); 
        labelInstansi.text("PILIH KEJAKSAAN (TEAM NON-LITIGASI)");
        selectInstansi.append(`
            <option value="kejati">KEJAKSAAN TINGGI JAWA TIMUR</option>
            <option value="kejari_sby">KEJAKSAAN NEGERI SURABAYA</option>
            <option value="kejari_perak">KEJAKSAAN NEGERI TANJUNG PERAK</option>
        `);
    } else if (jenis === 'laporan_polisi') {
        containerTanggal.removeClass('hidden');
        containerInstansi.removeClass('hidden');  
        labelInstansi.text("PILIH KEPOLISIAN (WILAYAH)");
        selectInstansi.append(`
            <option value="polda">POLDA JAWA TIMUR</option>
            <option value="polrestabes">POLRESTABES SURABAYA</option>
            <option value="polres_perak">POLRES TANJUNG PERAK</option>
        `);
    } else {
        containerTanggal.addClass('hidden');
        containerInstansi.addClass('hidden');
    }

    if (selectedValue) {
        selectInstansi.val(selectedValue);
    }
}

$(document).ready(function() {
    // Saat dropdown PIC diubah secara manual
    $('#edit_id_pic').on('change', function() {
        const namaPic = $(this).find('option:selected').text();
        $('#edit_nama_pic').val(namaPic);
    });
});
// Update Nama PIC Otomatis saat dropdown ID PIC berubah
$('#edit_id_pic').on('change', function() {
    $('#edit_nama_pic').val($(this).find('option:selected').text());
});
</script>

<script>

    // Proses Submit Update
$(document).ready(function() {

 
$('#formUpdate2').on('submit', function(e) {
      
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: "<?= base_url('nonlit/update_data') ?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response) {
            if (response.status) {

            const modal = document.getElementById('modal_edit');
                modal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data telah diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    });
});
});
</script>