 <style>
     .card-row:focus-within {
         z-index: 100;
     }
/* Dalam file globals.css atau input.css Anda */
.swal2-styled.swal2-confirm {
    @apply btn btn-primary border-none !important;
}

.swal2-styled.swal2-cancel {
    @apply btn btn-ghost !important;
}
     /* Memaksa card yang sedang dibuka dropdown-nya untuk berada di paling depan */
     #card-list>div:focus-within {
         z-index: 50 !important;
         position: relative;
     }

     /* Memastikan dropdown tidak terpotong */
     .dropdown-content {
         margin-top: 0.5rem;
     }

     .card-row {
         transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
         border-left: 4px solid transparent;
     }

     .card-row:hover {
         transform: translateX(8px);
         border-left-color: #2563eb;
         /* Warna biru saat hover */
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
     <div id="stats-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
             <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                 <i class="mdi mdi-scale-balance text-2xl"></i>
             </div>
             <div>
                 <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Non-Litigasi</p>
                 <h3 id="count-nonlit" class="text-xl font-black text-slate-800">0</h3>
             </div>
         </div>

         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
             <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                 <i class="mdi mdi-police-badge text-2xl"></i>
             </div>
             <div>
                 <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">LP Polisi</p>
                 <h3 id="count-lp" class="text-xl font-black text-slate-800">0</h3>
             </div>
         </div>

         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
             <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                 <i class="mdi mdi-alert-circle-outline text-2xl"></i>
             </div>
             <div>
                 <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Permasalahan</p>
                 <h3 id="count-masalah" class="text-xl font-black text-slate-800">0</h3>
             </div>
         </div>

         <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
             <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                 <i class="mdi mdi-folder-outline text-2xl"></i>
             </div>
             <div>
                 <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Data Umum</p>
                 <h3 id="count-umum" class="text-xl font-black text-slate-800">0</h3>
             </div>
         </div>
     </div>
     
       <div id="csrf-holder">
            <?= crsf_ajax() ?>
        </div>
     <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
         <div>
             <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic">Master Permasalahan</h1>
             <p class="text-slate-500 text-sm font-medium">BPKAD System • <span id="total-count" class="text-blue-600 font-black">0</span> Data ditemukan</p>
         </div>
         <div class="flex gap-3 w-full md:w-auto">
             <div class="relative flex-grow">
                 <i class="mdi mdi-magnify absolute left-3 top-2.5 text-slate-400 text-xl"></i>
                 <input type="text" id="search-input" onkeyup="loadData(1, this.value)" placeholder="Cari permohonan atau PIC..." class="input input-bordered pl-10 w-full rounded-xl focus:ring-2 focus:ring-blue-500 font-bold text-sm">
             </div>

             <div class="join bg-slate-100 p-1 rounded-xl">
                 <button onclick="changeView('list')" id="btn-list" class="btn btn-sm join-item btn-ghost bg-white shadow-sm rounded-lg text-blue-600">
                     <i class="mdi mdi-view-sequential text-lg"></i>
                 </button>
                 <button onclick="changeView('grid')" id="btn-grid" class="btn btn-sm join-item btn-ghost rounded-lg">
                     <i class="mdi mdi-view-grid text-lg"></i>
                 </button>
             </div>

             <button onclick="modal_tambah.showModal()" class="btn btn-primary rounded-xl px-6 shadow-lg shadow-blue-200">
                 <i class="mdi mdi-plus-circle text-lg"></i>
             </button>
         </div>
     </div>
     <div id="card-list" class="grid grid-cols-1 gap-4">
         <div class="col-span-full flex flex-col items-center py-20 opacity-50">
             <span class="loading loading-spinner loading-lg text-primary"></span>
             <p class="mt-4 font-bold text-slate-500">Memuat data perkara...</p>
         </div>
     </div>

     <div id="pagination-container" class="mt-10 flex justify-center pb-20">
    </div>
     
 </div>
 <dialog id="modal_tambah" class="modal">
     <div class="modal-box max-w-3xl bg-white p-0 rounded-3xl border-none shadow-2xl">
         <div class="p-6 bg-blue-600 text-white flex justify-between items-center">
             <div class="flex items-center gap-3">
                 <div class="p-2 bg-white/20 rounded-lg"><i class="mdi mdi-plus-circle text-2xl"></i></div>
                 <div>
                     <h3 class="font-black text-lg leading-none">TAMBAH NONLITIGASI</h3>
                     <p class="text-xs text-blue-100 mt-1 uppercase tracking-wider">Input Data Non-Litigasi Baru</p>
                 </div>
             </div>
             <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
         </div>

         <form id="formSimpan" class="p-8">
             <?= crsf_ajax() ?>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                     <input type="text" name="permohonan_nonlit" class="input input-bordered bg-slate-50 focus:ring-2 focus:ring-blue-500 rounded-xl" placeholder="Contoh: Permohonan Pendampingan Hukum..." required>
                 </div> -->



                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Jenis Data</span></label>
                     <select name="jenis" id="select_jenis" require class="select select-bordered bg-blue-50 border-blue-200 focus:ring-2 focus:ring-blue-500 rounded-xl font-bold" required onchange="toggleInstansiTambah()">
                         <option value="" disabled selected>Pilih Jenis...</option>
                         <option value="nonlit">NON-LITIGASI (KEJAKSAAN)</option>
                         <option value="laporan_polisi">LAPORAN POLISI (KEPOLISIAN)</option>
                         <option value="permasalahan">PERMASALAHAN</option>
                         <option value="data_umum">DATA UMUM</option>
                     </select>
                 </div>

                     <div class="form-control hidden" id="container_tanggal_tambah">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Tanggal Non-Litigasi</span></label>
                     <input type="date" name="tgl_nonlit" class="input input-bordered bg-slate-50 rounded-xl">
                 </div>

                 <div id="container_instansi" class="form-control col-span-full hidden border-l-4 border-blue-500 bg-blue-50/50 p-4 rounded-r-xl">
                     <label class="label"><span id="label_instansi" class="label-text font-bold text-blue-700 uppercase text-[11px]">Team / Instansi Terkait</span></label>
                     <select name="team_nonlit" id="select_instansi" class="select select-bordered bg-white rounded-xl">
                     </select>
                 </div>
                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                     <input type="text" name="permohonan_nonlit" class="input input-bordered bg-slate-50 rounded-xl" placeholder="..." required>
                 </div>


                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Alamat Terkait</span></label>
                     <input type="text" name="alamat" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan alamat lokasi jika ada">
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

                     <div class="relative">
                         <i class="mdi mdi-account-circle absolute left-4 top-3 text-slate-400"></i>
                         <i class="fa fa-picture-o" aria-hidden="true"></i>
                         <select name="id_pic" id="id_pic" class="select select-bordered w-full pl-10 bg-slate-50 rounded-xl" require>
                             <option disabled selected>Pilih PIC...</option>
                             <?php foreach ($list_pic as $pic) : ?>
                                 <option value="<?= $pic->id ?>"><?= $pic->nama_pic ?></option>
                             <?php endforeach; ?>
                         </select>
                         <input type="hidden" name="pic" id="pic" class="input input-bordered bg-slate-50 rounded-xl mt-2" placeholder="Nama PIC akan muncul di sini..." readonly>
                     </div>
                 </div>

             
                 <div class="form-control">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nomor Register Baru</span></label>
                     <input type="text" name="register_baru" require class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan No. Register">
                 </div>
                 <div class="form-control">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Luas</span></label>
                     <input type="text" name="luas" require class="input input-bordered bg-slate-50 rounded-xl" placeholder="Luas">
                 </div>

                 <div class="form-control">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Penyimpanan Rak</span></label>
                     <div class="relative">
                         <i class="mdi mdi-archive absolute left-4 top-3 text-slate-400"></i>
                         <input type="text" require name="penyimpanan_rak" class="input input-bordered w-full pl-10 bg-slate-50 rounded-xl" placeholder="Contoh: R.01-A">
                     </div>
                 </div>


                 <div class="form-control">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Status Perkara</span></label>
                     <select name="status" require class="select select-bordered bg-slate-50 rounded-xl font-bold">
                         <option value="proses">PROSES</option>
                         <option value="selesai">SELESAI</option>
                     </select>
                 </div>
             </div>

             <div class="form-control mt-6">
                 <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Keterangan Detail</span></label>
                 <textarea name="keterangan" require class="textarea textarea-bordered h-24 bg-slate-50 rounded-xl" placeholder="Catatan tambahan..."></textarea>
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
                     <h3 class="font-black text-lg leading-none uppercase">UPDATE DATA</h3>
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
                     <select name="team_nonlit" id="edit_team_nonlit" class="select select-bordered bg-white rounded-xl">
                     </select>
                 </div>

                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Nama Permohonan / Judul Perkara</span></label>
                     <input type="text" name="permohonan_nonlit" id="edit_permohonan" class="input input-bordered bg-slate-50 rounded-xl uppercase font-bold" required>
                 </div>
                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Alamat Terkait</span></label>
                     <input type="text" name="alamat" id="edit_alamat" class="input input-bordered bg-slate-50 rounded-xl" placeholder="Masukkan alamat lokasi jika ada">
                 </div>


                 <div class="form-control col-span-full">
                     <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Register Baru</span></label>
                     <input type="text" name="register_baru" id="edit_register_baru" class="input input-bordered bg-slate-50 rounded-xl uppercase font-bold" required>
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
                         <input type="hidden" id="edit_nama_pic" name="pic" class="input input-bordered bg-slate-50 rounded-xl mt-2 w-full" readonly>
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
<div class="form-control">
                 <label class="label"><span class="label-text font-bold text-slate-600 uppercase text-[11px]">Keterangan Detail</span></label>
                 <textarea name="keterangan" id="edit_keterangan"  class="textarea textarea-bordered h-24 bg-slate-50 rounded-xl" placeholder="Catatan tambahan..."></textarea>
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
     // --- 1. VARIABEL GLOBAL ---
     let currentView = 'list';
     let globalData = [];

     // --- 2. FUNGSI HELPER (KONFIGURASI JENIS) ---
     function getJenisConfig(jenis) {
         const map = {
             'nonlit': {
                 icon: 'mdi-scale-balance',
                 color: 'text-blue-600',
                 bg: 'bg-blue-50',
                 hex: 'bg-blue-500',
                 label: 'Non-Litigasi'
             },
             'laporan_polisi': {
                 icon: 'mdi-police-badge',
                 color: 'text-red-600',
                 bg: 'bg-red-50',
                 hex: 'bg-red-500',
                 label: 'LP Polisi'
             },
             'permasalahan': {
                 icon: 'mdi-alert-circle',
                 color: 'text-amber-600',
                 bg: 'bg-amber-50',
                 hex: 'bg-amber-500',
                 label: 'Permasalahan'
             },
             'data_umum': {
                 icon: 'mdi-folder',
                 color: 'text-emerald-600',
                 bg: 'bg-emerald-50',
                 hex: 'bg-emerald-500',
                 label: 'Data Umum'
             }
         };
         return map[jenis] || {
             icon: 'mdi-file',
             color: 'text-slate-600',
             bg: 'bg-slate-50',
             hex: 'bg-slate-500',
             label: 'Lainnya'
         };
     }

     // --- 3. FUNGSI RENDER (LIST VIEW) ---
     function renderListView(item) {
        const cfg = getJenisConfig(item.jenis);
    const statusClass = item.status.toLowerCase() === 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';

    return `
    <div class="group bg-white rounded-2xl border border-slate-200 p-3 mb-3 flex items-center justify-between hover:shadow-xl hover:border-blue-400 transition-all duration-300">
        <div class="flex items-center gap-5 flex-grow truncate">
            <div class="relative shrink-0">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center ${cfg.bg} ${cfg.color} shadow-inner">
                    <i class="mdi ${cfg.icon} text-2xl"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white ${statusClass} flex items-center justify-center">
                    <i class="mdi ${item.status.toLowerCase() === 'selesai' ? 'mdi-check-bold' : 'mdi-clock-outline'} text-[10px]"></i>
                </div>
            </div>

            <a href="<?php echo base_url('nonlit/detail/'.'${item.id}') ?>">
            <div class="truncate">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-md ${cfg.bg} ${cfg.color} tracking-widest">${cfg.label}</span>
                    <span class="text-[8px] font-bold text-slate-400">ID: #${item.id}</span>
                </div>
                 <h4 class="font-black text-slate-800 text-sm uppercase truncate group-hover:text-blue-600 transition-colors italic">${item.permohonan_nonlit} </h4> 
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-[10px] font-bold text-slate-500 flex items-center gap-1"><i class="mdi mdi-account-circle-outline text-blue-500"></i> ${item.pic}</span>
                    <span class="text-[10px] font-bold text-slate-400">•</span>
                    <span class="text-[10px] font-bold text-purple-600 flex items-center gap-1 uppercase tracking-tighter"><i class="mdi mdi-archive-outline"></i> RAK: ${item.penyimpanan_rak || '-'}</span>
                </div>
                </div>
                </a>
        </div>

        <div class="flex items-center gap-4 shrink-0">
            <div class="hidden md:block text-right">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Registrasi</p>
                <p class="text-[10px] font-bold text-slate-700 uppercase">${item.tgl_nonlit}</p>
            </div>
            ${renderActionMenu(item)}
        </div>
    </div>`;
     }

     // --- 4. FUNGSI RENDER (GRID VIEW) ---
     function renderGridView(item) {
         const cfg = getJenisConfig(item.jenis);
    const isSelesai = item.status.toLowerCase() === 'selesai';
    
    return `  
    <div class="group bg-white rounded-[2.5rem] border border-slate-200 p-1 flex flex-col hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative">
        <div class="p-5 flex flex-col h-full">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 rounded-[1.5rem] ${cfg.bg} ${cfg.color} flex items-center justify-center shadow-lg shadow-slate-100 group-hover:scale-110 transition-transform duration-500">
                    <i class="mdi ${cfg.icon} text-3xl"></i>
                </div>
                <div class="flex flex-col items-end gap-2">
                    ${renderActionMenu(item)}
                    <span class="text-[8px] font-black px-2 py-1 rounded-lg ${isSelesai ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'} uppercase tracking-widest">
                        ${item.status}
                    </span>
                </div>
            </div>

            <a href="<?php echo base_url('nonlit/detail/'.'${item.id}')?>">
            <div class="mb-6">
                <p class="text-[9px] font-black ${cfg.color} uppercase tracking-[0.2em] mb-1">${cfg.label}</p>
                <h4 class="font-black text-slate-800 text-sm uppercase leading-tight line-clamp-3 min-h-[3rem] italic group-hover:text-blue-600 transition-colors">
                    ${item.permohonan_nonlit} 
                </h4>
            </div>
            </a>


            <div class="bg-slate-50 rounded-2xl p-3 flex flex-col gap-2 border border-slate-50">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase leading-none">PIC LAPANGAN</span>
                    <span class="text-[10px] font-black text-slate-700 uppercase truncate w-24 text-right">${item.pic}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase leading-none">POSISI ARSIP</span>
                    <span class="text-[10px] font-black text-purple-600 uppercase italic">RAK ${item.penyimpanan_rak || '-'}</span>
                </div>
            </div>
        </div> 
        <div class="mt-auto bg-slate-900 rounded-b-[2.4rem] p-4 flex justify-between items-center mx-[1px] mb-[1px]">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full ${isSelesai ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400 animate-pulse'}"></div>
                <span class="text-[9px] font-black text-white uppercase tracking-widest italic">${item.tgl_nonlit}</span>
            </div>
            <span class="text-[9px] font-black text-slate-500 uppercase tracking-tighter">#${item.id}</span>
        </div>
        </div> 
     `;}

     // --- 5. FUNGSI DROPDOWN AKSI ---
     function renderActionMenu(item) {
var sumber="";
     if (item.jenis === "nonlit"){
        sumber = "NONLIT";
     }else if (item.jenis === "laporan_polisi"){
         sumber = "LAPORAN_POLISI";
}else {
         sumber = "PERMASALAHAN";

     }


         return `
    <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-sm btn-circle text-slate-400 hover:bg-slate-100">
            <i class="mdi mdi-dots-vertical text-xl"></i>
        </label>
        <ul tabindex="0" class="dropdown-content z-[50] menu p-2 shadow-2xl bg-base-100 rounded-2xl w-52 border border-slate-100 text-[11px] font-bold uppercase tracking-tighter">
            <li><a href="<?= base_url('nonlit/detail/') ?>${item.id}" class="py-3"><i class="mdi mdi-eye-outline text-blue-500 text-lg"></i> Detail</a></li>
            <li><a href="<?= base_url('peta/edit/') ?>${item.id}" class="py-3 text-emerald-600"><i class="mdi mdi-map-marker-path text-lg"></i> Edit Area Peta</a></li>
            <li><button onclick="shareFolder('${sumber}', '${item.id}')" class="py-3"><i class="mdi mdi-share-variant text-lg">Share Link</i>
             </button> 
             </li>
            <li><button onclick="editData(${item.id})" class="py-3 text-amber-500"><i class="mdi mdi-pencil-outline text-lg"></i> Update Data</button></li>
            <li><button onclick="cetak_label_nonlit('${item.penyimpanan_rak}', '${item.permohonan_nonlit}', '${item.alamat}')" class="py-3 text-slate-600"><i class="mdi mdi-printer-outline text-lg"></i> Cetak Label</button></li>
            <div class="divider my-0 opacity-50"></div>
            <li><button onclick="hapusData(${item.id})" class="py-3 text-red-500"><i class="mdi mdi-trash-can-outline text-lg"></i> Hapus Permanen</button></li>
            </ul>
            </div>`;
        }
        // <li><button onclick="shareFolder(${item.id})" class="py-3 text-indigo-600"><i class="mdi mdi-share-variant text-lg"></i> Bagikan Link</button></li>

     // --- 6. FUNGSI RENDER CARDS (YANG ERROR TADI) ---
     function renderCards(data) {
         let html = '';
         if (!data || data.length === 0) {
             html = `<div class="col-span-full py-20 text-center opacity-40 font-black uppercase tracking-widest text-xs">Data Tidak Ditemukan</div>`;
         } else {
             data.forEach(item => {
                 html += (currentView === 'grid') ? renderGridView(item) : renderListView(item);
             });
         }
         $('#card-list').html(html);
     }

     // --- 7. FUNGSI LOAD DATA ---
     function loadData(page = 1, search = "") {
         $.ajax({
             url: "<?= base_url('nonlit/fetch_nonlit') ?>",
             type: "POST",
             data: {
                 start: (page - 1) * 10,
                 length: 10,
                 draw: 1,
                 search: {
                     value: search
                 },
                 token: $('#token').val()
             },
             dataType: "json",
             success: function(response) {
                 globalData = response.data;
                 renderCards(response.data); // PASTI TERDEFINISI KARENA ADA DI ATAS

               // 2. Render Pagination (PENTING: Ambil keyword search dari input)
    const currentSearch = $('#search-input').val();
    renderPagination(response.recordsFiltered, page, currentSearch);

    // 3. Update Stats Card
    $('#total-count').text(response.recordsFiltered);
    if (response.stats) {
        $('#count-nonlit').text(response.stats.nonlit);
        $('#count-lp').text(response.stats.laporan_polisi);
        $('#count-masalah').text(response.stats.permasalahan);
        $('#count-umum').text(response.stats.data_umum);
    }
             },
             error: function(xhr) {
                 console.error("AJAX Error: ", xhr.responseText);
             }
         });
     }

     function renderPagination(totalRecords, currentPage, search) {
         const limit = 10; // Sesuaikan dengan length di ajax
    const totalPages = Math.ceil(totalRecords / limit);
    let html = '';

    if (totalPages > 1) {
        html += `<div class="join bg-white p-1 rounded-2xl shadow-sm border border-slate-200">`;
        
        // Tombol Previous
        if (currentPage > 1) {
            html += `<button onclick="loadData(${currentPage - 1}, '${search}')" class="btn btn-sm join-item rounded-xl btn-ghost"><i class="mdi mdi-chevron-left"></i></button>`;
        }

        // Looping Halaman
        for (let i = 1; i <= totalPages; i++) {
            // Logika sederhana: tampilkan semua jika halaman sedikit, atau gunakan limit jika banyak
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                const activeClass = (i === currentPage) ? 'btn-primary text-white shadow-md' : 'btn-ghost text-slate-500';
                html += `<button onclick="loadData(${i}, '${search}')" class="btn btn-sm join-item rounded-xl px-4 ${activeClass}">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<button class="btn btn-sm join-item btn-disabled">...</button>`;
            }
        }

        // Tombol Next
        if (currentPage < totalPages) {
            html += `<button onclick="loadData(${currentPage + 1}, '${search}')" class="btn btn-sm join-item rounded-xl btn-ghost"><i class="mdi mdi-chevron-right"></i></button>`;
        }

        html += `</div>`;
    }

    // Masukkan ke container
    $('#pagination-container').html(html);
}

     // --- 8. INITIALIZE ---
     $(document).ready(function() {
         loadData();
         // Trigger untuk Modal Tambah
    $('#select_jenis').on('change', function() {
        toggleInstansiTambah();
    });

    // Trigger untuk Modal Edit
    $('#edit_jenis').on('change', function() {
        toggleInstansiUpdate();
    });
     });

     // --- 9. VIEW SWITCHER ---
     function changeView(type) {
         currentView = type;
         const list = $('#card-list');
         if (type === 'grid') {
             $('#btn-grid').addClass('bg-white shadow-sm text-blue-600');
             $('#btn-list').removeClass('bg-white shadow-sm text-blue-600');
             list.removeClass('grid-cols-1').addClass('grid-cols-1 md:grid-cols-2 lg:grid-cols-3');
         } else {
             $('#btn-list').addClass('bg-white shadow-sm text-blue-600');
             $('#btn-grid').removeClass('bg-white shadow-sm text-blue-600');
             list.removeClass('grid-cols-1 md:grid-cols-2 lg:grid-cols-3').addClass('grid-cols-1');
         }
         renderCards(globalData);
     }
 </script>
 <script>
     $(document).ready(function() {
         $('#edit_pic').on('change', function() {
             const namaPic = $(this).find('option:selected').text();
             if ($(this).val()) {
                 $('#edit_nama_pic').val(namaPic);
             }
         });
         $('#id_pic').on('change', function() {
             const namaPic = $(this).find('option:selected').text();
             if ($(this).val()) {
                 $('#pic').val(namaPic);
             }
         });
         let currentPage = 1;
         let searchQuery = "";

         // Fungsi utama mengambil data


         function updateStats(data) {
             let nonlit = 0;
             let lp = 0;
             let masalah = 0;
             let umum = 0;

             data.forEach(item => {
                 if (item.jenis === 'nonlit') nonlit++;
                 else if (item.jenis === 'laporan_polisi') lp++;
                 else if (item.jenis === 'permasalahan') masalah++;
                 else if (item.jenis === 'data_umum') umum++;
             });

             // Update Angka di Card Stats
             $('#count-nonlit').text(nonlit);
             $('#count-lp').text(lp);
             $('#count-masalah').text(masalah);
             $('#count-umum').text(umum);
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
             window.scrollTo({
                 top: 0,
                 behavior: 'smooth'
             });
         });
         // Bagian Tombol Aksi yang ringkas

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
                     if (response.status === 'success') {
                         Swal.fire({
                             title: 'Berhasil!',
                             text: 'Data perkara baru telah berhasil disimpan.',
                             icon: 'success',
                             customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false
                            
                         }).then(() => {
                             // 4. Refresh data card tanpa reload halaman
                             loadData();
                         });
                     } else {
                         Swal.fire({
                             title: 'Gagal!',
                               customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false,
                             text: response.message || 'Gagal menyimpan data perkara baru.',
                             icon: 'error'
                         });
                     }
                 },
                 complete: function() {
                     // Kembalikan tombol ke kondisi semula
                     btnSubmit.prop('disabled', false).html(originalText);
                 }
             });
         });

         //  $('#edit_pic').on('change', function() {
         //      const selectedNama = $(this).find(':selected').data('nama');
         //      if (selectedNama) {
         //          $('#edit_nama_pic').val(selectedNama);
         //      }
         //  });

         $('#formUpdate').on('submit', function(e) {
             e.preventDefault();
             // PAKSA isi edit_nama_pic dari teks option yang terpilih jika masih kosong
             if ($('#edit_nama_pic').val() === "") {
                 const currentText = $('#edit_pic option:selected').text();
                 $('#edit_nama_pic').val(currentText);
             }
             // Tampilkan loading sebentar agar user tahu proses sedang berjalan
             const btnSubmit = $(this).find('button[type="submit"]');
             const originalText = btnSubmit.html();
             btnSubmit.prop('disabled', true).html('<span class="loading loading-spinner loading-xs"></span> Menyimpan...');
             let formData = new FormData(this);
             $.ajax({
                 url: "<?= base_url('nonlit/update_data') ?>",
                 type: "POST",
                 data: formData,
                 processData: false,
                 contentType: false,
                 dataType: "json", // Pastikan controller mengirimkan json_encode
                 success: function(response) {
                     // 1. Tutup Modal Edit
                     modal_edit.close();
                     if (response.status !== 'success') {
                         Swal.fire({
                             theme: 'auto',
                             title: 'Gagal!',
                             text: response.message || 'Gagal memperbarui data perkara.',
                             icon: 'error'
                         });
                         return;
                     }

                     // 2. Munculkan Alert Berhasil
                     Swal.fire({ 
                         title: 'Berhasil!',
                         text: 'Data perkara telah diperbarui.',
                         icon: 'success',
                           customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false
                     }).then(() => {
                         // 3. Refresh data di Card tanpa reload halaman
                         loadData();
                     });
                 },
                 error: function(xhr) {
                     Swal.fire({
                        //  theme: 'auto',
                         title: 'Gagal!',
                         text: 'Terjadi kesalahan saat memperbarui data.',
                         icon: 'error',
                           customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false
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
function updateTokenGlobal(newToken) {
        if (newToken) {
            $('#token').val(newToken);
            $('input[name="token"]').val(newToken);
            console.log("CSRF Token Synchronized");
        }
    }


function shareFolder(sumber, id) {
        const currentToken = $('#token').val();// Ambil token CSRF yang ada di hidden input

    $.ajax({
        url: "<?= base_url('nonlit/generate_share_link') ?>",
        type: "POST",
        data: {
            sumber: sumber,
                id_data: id,
                durasi: 24, // Misal default 24 jam
                token: currentToken
        },
        dataType: "json",
        success: function(res) {
            if (res.status) {
                // Update CSRF token di halaman agar tidak expired
                $('#token').val(res.new_token);

                // Tampilkan Link dengan UI yang Cantik
                Swal.fire({
                    title: '<strong>Link Berhasil Dibuat!</strong>',
                    icon: 'success',
                    html: `
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Link Aktif Sampai: <br><span class="text-indigo-600">${res.expired}</span></p>
                        <div class="relative group mt-4">
                            <input type="text" id="link_publik" value="${res.url}" readonly 
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-none font-bold text-xs text-center pr-12">
                            <button onclick="copyLinkOnly('${res.url}')" class="absolute right-2 top-1 btn btn-ghost btn-sm rounded-xl">
                                <i class="mdi mdi-content-copy"></i>
                            </button>
                        </div>
                    `,
                    showCloseButton: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="mdi mdi-share-variant"></i> Bagikan Sekarang',
                    confirmButtonColor: '#4f46e5',
                    customClass: {
                        popup: 'rounded-[2.5rem] p-10',
                        confirmButton: 'rounded-2xl font-black italic uppercase text-xs px-8'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jalankan Native Share Browser
                        if (navigator.share) {
                            navigator.share({
                                title: 'Laporan BPKAD',
                                text: 'Berikut link akses publik untuk laporan perkara:',
                                url: res.url,
                            });
                        } else {
                            copyLinkOnly(res.url);
                        }
                    }
                });
            }
        }
    });
}

function copyLinkOnly(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Tampilkan toast kecil
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({
            icon: 'success',
            title: 'Link berhasil disalin!'
        });
    });
}
</script>

 <script>
     $(document).ready(function() {
         // Event listener untuk Modal Edit PIC
         $('#edit_id_pic').on('change', function() {
             const namaSelected = $(this).find('option:selected').text();
             // Cek jika yang dipilih bukan default "Pilih PIC"
             if ($(this).val()) {
                 $('#edit_nama_pic').val(namaSelected);
             } else {
                 $('#edit_nama_pic').val('');
             }
         });
     });

     function editData(id) {
         $.ajax({
             url: "<?= base_url('nonlit/get_data_by_id/') ?>" + id,
             type: "GET",
             dataType: "JSON",
             success: function(item) {
                 if (item) {
                     // 1. Isi field teks dasar
                     $('#edit_id').val(item.id);
                     $('#display_edit_id').text(item.id);
                     $('#edit_permohonan').val(item.permohonan_nonlit);
                     $('#edit_alamat').val(item.alamat);
                     $('#edit_register_baru').val(item.register_baru);
                     $('#edit_tgl_nonlit').val(item.tgl_nonlit);
                     $('#edit_penyimpanan_rak').val(item.penyimpanan_rak);
                     $('#edit_luas').val(item.luas);
                     $('#edit_status').val(item.status);
                     $('#edit_bidang').val(item.bidang);
                     $('#edit_keterangan').val(item.keterangan);

                     // 2. Set JENIS DATA dan panggil TOGGLE (Agar container Instansi muncul)
                     $('#edit_jenis').val(item.jenis);
                     toggleInstansiUpdate(item.team_nonlit); // Kirim value team_nonlit dari DB

                     // 3. SET PIC PERKARA (OTOMATIS PILIH)
                     if (item.id_pic) {
                         // Masukkan ID ke select
                         $('#edit_id_pic').val(item.id_pic);

                         // Trigger manual event change agar input nama_pic terisi teksnya
                         const namaPic = $(`#edit_id_pic option[value="${item.id_pic}"]`).text();
                         $('#edit_nama_pic').val(namaPic);
                     } else {
                         $('#edit_id_pic').val('');
                         $('#edit_nama_pic').val('');
                     }

                     // Munculkan Modal
                     modal_edit.showModal();
                 }
             },
             error: function() {
                 alert('Gagal mengambil data dari server');
             }
         });
     }

     function toggleInstansiTambah() {
    const jenis = $('#select_jenis').val(); // ID select di modal tambah
    const container = $('#container_instansi');
    const selectInstansi = $('#select_instansi');
    const labelInstansi = $('#label_instansi');
const containerTanggal = $('#container_tanggal_tambah');
    selectInstansi.empty();

    if (jenis === 'nonlit') { 

    containerTanggal.removeClass('hidden');
        container.removeClass('hidden');
        labelInstansi.text("PILIH KEJAKSAAN (TEAM NON-LITIGASI)");
        selectInstansi.append(`
            <option value="" disabled selected>Pilih Kejaksaan...</option>
            <option value="kejati">KEJAKSAAN TINGGI JAWA TIMUR</option>
            <option value="kejari_sby">KEJAKSAAN NEGERI SURABAYA</option>
            <option value="kejari_perak">KEJAKSAAN NEGERI TANJUNG PERAK</option>
        `);
    } else if (jenis === 'laporan_polisi') {
        containerTanggal.removeClass('hidden'); 
        container.removeClass('hidden');
        labelInstansi.text("PILIH KEPOLISIAN (WILAYAH)");
        selectInstansi.append(`
            <option value="" disabled selected>Pilih Kepolisian...</option>
            <option value="polda">POLDA JAWA TIMUR</option>
            <option value="polrestabes">POLRESTABES SURABAYA</option>
            <option value="polres_perak">POLRES TANJUNG PERAK</option>
        `);
    }else if (jenis === "permasalahan"){
        containerTanggal.addClass('hidden');
        container.addClass('hidden');

    }
     else {
        container.addClass('hidden');
     containerTanggal.addClass('hidden');
        // containerTanggal.classList.add('hidden');

    }
}
     function toggleInstansiUpdate(selectedValue = null) {
        const containerTanggal = $('#container_tanggal_update');
         const jenis = $('#edit_jenis').val();
         const containerInstansi = $('#container_instansi_update');
         const selectInstansi = $('#edit_team_nonlit');
         const labelInstansi = $('#label_instansi_update');

         selectInstansi.empty();

         if (jenis === 'nonlit') {
            containerTanggal.removeClass('hidden')
            containerInstansi.removeClass('hidden') 
             labelInstansi.text("PILIH KEJAKSAAN (TEAM NON-LITIGASI)");
             selectInstansi.append(`
            <option value="kejati">KEJAKSAAN TINGGI JAWA TIMUR</option>
            <option value="kejari_sby">KEJAKSAAN NEGERI SURABAYA</option>
            <option value="kejari_perak">KEJAKSAAN NEGERI TANJUNG PERAK</option>
        `);
         } else if (jenis === 'laporan_polisi') {
                  containerTanggal.removeClass('hidden')
            containerInstansi.removeClass('hidden')  
             labelInstansi.text("PILIH KEPOLISIAN (WILAYAH)");
             selectInstansi.append(`
            <option value="polda">POLDA JAWA TIMUR</option>
            <option value="polrestabes">POLRESTABES SURABAYA</option>
            <option value="polres_perak">POLRES TANJUNG PERAK</option>
        `);
         } else if (jenis === "permasalahan"){
containerInstansi.addClass('hidden')
      containerTanggal.addClass('hidden')
         }
         else {
            containerTanggal.addClass('hidden');
            containerInstansi.addClass('hidden')
         }

         if (selectedValue) {
             selectInstansi.val(selectedValue);
         }
     }
 </script>

 <script>
     function cetak_label_nonlit(DisplayRak, DisplayPermohonan, DisplayAlamat) {
         const printWindow = window.open('', '_blank', 'width=900,height=400');

         // Fallback jika data kosong
         const rak = (DisplayRak && DisplayRak.trim() !== "") ? DisplayRak : "-";
         const permohonan = (DisplayPermohonan && DisplayPermohonan.trim() !== "") ? DisplayPermohonan : "-";
         const alamat = (DisplayAlamat && DisplayAlamat.trim() !== "") ? DisplayAlamat : "-";

         const htmlContent = `
        <html>
        <head>
            <title>Cetak Label Non-Lit</title>
            <style>
                @page { size: A4; margin: 0; }
                body {
                    margin: 0;
                    padding: 5mm;
                    /* Arial Narrow lebih hemat ruang secara horizontal */
                    font-family: 'Arial Narrow', Arial, sans-serif;
                    text-transform: uppercase;
                }
                .label-strip {
                    display: flex;
                    align-items: stretch;
                    border: 1.5pt solid #000;
                    width: 100%;
                    max-width: 19cm;
                    height: 1cm; /* Tinggi diperkecil sedikit agar lebih proporsional */
                    overflow: hidden;
                }
                .section-status {
                    background: #000;
                    color: #fff;
                    font-weight: 900;
                    font-size: 9pt; /* Ukuran Rak diperkecil */
                    padding: 0 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 60px;
                    border-right: 1.5pt solid #000;
                    -webkit-print-color-adjust: exact;
                }
                .section-nopol {
                    min-width: 180px;
                    font-weight: 800;
                    font-size: 8pt; /* Font Permohonan diperkecil */
                    padding: 0 10px;
                    display: flex;
                    align-items: center;
                    border-right: 1pt solid #000;
                    background: #f3f4f6;
                    -webkit-print-color-adjust: exact;
                    white-space: nowrap;
                }
                .section-judul {
                    flex: 1;
                    font-size: 7.5pt; /* Font Alamat dibuat paling kecil agar muat panjang */
                    font-weight: bold;
                    padding: 0 10px;
                    display: flex;
                    align-items: center;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis; /* Jika tetap kepanjangan akan jadi ... */
                }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="label-strip">
                <div class="section-status">RAK: ${rak}</div>
                <div class="section-nopol"> ${permohonan} </div>
                <div class="section-judul">${alamat}</div>
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
 <!-- <script>
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

             $('#edit_pic option').prop('selected', false);
             if (item.id_pic) {
                 $(`#edit_pic option[value="${item.id_pic}"]`).prop('selected', true);
             }
             //  $('#edit_pic').val(item.id_pic).trigger('change'); // Pastikan opsi PIC sudah terisi dengan benar
             //  $('#edit_pic').val(item.id_pic).trigger('change'); // Pastikan opsi PIC sudah terisi dengan benar

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
 </script> -->
 <script>
     function hapusData(id) {
         var token = $('#token').val()
         Swal.fire({
             title: 'Hapus Data?',
             text: "Data yang dihapus tidak dapat dikembalikan!",
             icon: 'warning',
             
             showCancelButton: true,
             
             confirmButtonText: 'Ya, Hapus!',
             cancelButtonText: 'Batal',
               customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false

         }).then((result) => {
             if (result.isConfirmed) {
                 $.ajax({
                     url: "<?= base_url('nonlit/remove_nonlit') ?>", // pastikan route ini ada di controller
                     type: "POST",
                     data: {
                         id_nonlit: id,
                         token: token
                     },
                     success: function(response) {
                         Swal.fire({
                                 title: 'Terhapus!',
                                 text: 'Data perkara telah dihapus.',
                                 icon: 'success',  customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false
                             })
                             .then(() => {
                                 // Reload data tanpa refresh halaman penuh
                                 location.reload();
                             });
                     },
                     error: function() {
                         Swal.fire({
                             title: 'Gagal!',
                             text: 'Terjadi kesalahan saat menghapus data.',
                             icon: 'error',
                               customClass: {
                                confirmButton: 'btn btn-error mx-2', // Menggunakan class DaisyUI
                                cancelButton: 'btn btn-ghost mx-2'
                                },
                        // Penting: Matikan styling bawaan tombol SweetAlert agar class Tailwind bekerja
                        buttonsStyling: false
                         });
                     }
                 });
             }
         });
     }
 </script>