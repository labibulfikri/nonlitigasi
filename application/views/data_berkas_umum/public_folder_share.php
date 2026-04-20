<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <title>Shared Folder | <?= $parent->nama_berkas_umum ?></title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.5.0/dist/full.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
</head>

<body class="bg-slate-50 min-h-screen p-6 md:p-20">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-5 mb-10 bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100">
            <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-3xl flex items-center justify-center shrink-0">
                <i class="mdi mdi-folder text-4xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black uppercase italic text-slate-800 tracking-tighter"><?= $parent->nama_berkas_umum ?></h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Dibagikan Publik • <?= count($files) ?> Item Berkas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($files as $f): ?>
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center group-hover:rotate-6 transition-transform">
                            <?php
                            $ext = pathinfo($f->nama_file, PATHINFO_EXTENSION);
                            $icon = ($ext == 'pdf') ? 'mdi-file-pdf-box text-red-500' : 'mdi-file-document-outline text-indigo-500';
                            ?>
                            <i class="mdi <?= $icon ?> text-3xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-black text-slate-700 text-xs uppercase truncate"><?= $f->nama_file ?></h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase"><?= strtoupper($ext) ?> • <?= date('d M Y', strtotime($f->tgl_upload)) ?></p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="<?= base_url('assets/berkas_umum/detail/' . $f->nama_file) ?>" target="_blank" class="btn btn-sm btn-block rounded-xl btn-ghost bg-slate-50 hover:bg-indigo-600 hover:text-white transition-all font-bold text-[10px]">
                            LIHAT / DOWNLOAD
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-20 text-center">
            <p class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.3em]">Digital Archive System v2.0</p>
        </div>
    </div>
</body>

</html>