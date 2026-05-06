<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-NONLIT | BPKAD Kota Surabaya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />

    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --header-height: 72px;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 88px;
            /* Warna Utama: Orange Terang / Amber */
            --primary-color: #f59e0b;
            --primary-dark: #d97706;
            --sidebar-bg: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfcfd;
            /* Background lebih terang */
            color: #1e293b;
        }

        /* --- SIDEBAR CLEAN LIGHT --- */
        .sidebar-clean {
            background: var(--sidebar-bg) !important;
            border-right: 1px solid #f1f5f9;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }

        /* Navigasi Sidebar */
        .menu li>a,
        .menu li>details>summary {
            margin: 4px 16px;
            border-radius: 12px !important;
            color: #64748b;
            /* Slate 500 */
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .menu li>a:hover,
        .menu li>details>summary:hover {
            background: #fff7ed !important;
            /* Orange 50 */
            color: var(--primary-color) !important;
        }

        /* Active State: Orange Gradient */
        .active-menu {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.25);
        }

        /* Dropdown styling */
        .menu li details ul {
            padding-left: 1.5rem;
            margin-top: 0.25rem;
            border-left: 2px solid #f1f5f9;
            margin-left: 2.5rem;
        }

        /* Navbar Blur Effect */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f1f5f9;
        }

        /* Card Soft Shadow */
        .card-custom {
            background: white;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            border-radius: 20px;
        }

        /* Utility Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            width: 0px;
        }

        @media (max-width: 1023px) {
            #sidebar-container {
                transform: translateX(-100%);
                position: fixed;
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.05);
            }

            #sidebar-container.mobile-open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="antialiased">

    <div id="overlay" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-[45] hidden opacity-0 transition-opacity"></div>

    <div class="flex h-screen w-full overflow-hidden">

        <aside id="sidebar-container" class="sidebar-clean h-full flex flex-col shrink-0 overflow-hidden" style="width: var(--sidebar-width);">

            <div class="flex items-center justify-between px-6 shrink-0" style="height: var(--header-height);">
                <div class="flex items-center gap-3" id="sidebar-branding">
                    <div class="bg-amber-500 p-2.5 rounded-2xl shadow-lg shadow-amber-200 shrink-0 flex items-center justify-center">
                        <i class="mdi mdi-shield-check text-white text-xl leading-none"></i>
                    </div>
                    <div class="flex flex-col sidebar-text">
                        <span class="text-base font-extrabold tracking-tight text-slate-800 leading-none">E-NONLIT</span>
                        <span class="text-[9px] text-amber-600 font-bold uppercase tracking-widest mt-1">Surabaya City</span>
                    </div>
                </div>
                <button id="toggle-sidebar" class="btn btn-ghost btn-xs text-slate-300 hover:text-amber-500 hidden lg:flex px-0">
                    <i class="mdi mdi-backburger text-2xl" id="toggle-icon"></i>
                </button>
            </div>

            <div class="flex-grow overflow-y-auto no-scrollbar py-6">
                <div class="px-8 mb-4 sidebar-text">
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Main Menu</span>
                </div>

                <ul class="menu p-0 w-full space-y-1">
                    <li>
                        <a href="<?= base_url('home') ?>" class="py-3 <?= $this->uri->segment(1) == 'home' || $this->uri->segment(1) == '' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-home-variant-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-1">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <details <?= in_array($this->uri->segment(1), ['nonlit', 'laporan_polisi', 'masalah']) ? 'open' : '' ?> class="group">
                            <summary class="py-3">
                                <i class="mdi mdi-database-outline text-xl"></i>
                                <span class="sidebar-text font-bold text-sm ml-1">Data Perkara</span>
                            </summary>
                            <ul>
                                <li><a href="<?= base_url('nonlit') ?>" class="<?= $this->uri->segment(1) == 'nonlit' ? 'text-amber-600 bg-amber-50/50' : '' ?>">Permasalahan</a></li>
                                <!-- <li><a href="<?= base_url('laporan_polisi') ?>" class="<?= $this->uri->segment(1) == 'laporan_polisi' ? 'text-amber-600 bg-amber-50/50' : '' ?>">Laporan Polisi</a></li>
                                <li><a href="<?= base_url('masalah') ?>" class="<?= $this->uri->segment(1) == 'masalah' ? 'text-amber-600 bg-amber-50/50' : '' ?>">Permasalahan</a></li> -->
                                <!-- <li><a href="<?= base_url('berkas_umum') ?>" class="<?= $this->uri->segment(1) == 'berkas_umum' ? 'text-amber-600 bg-amber-50/50' : '' ?>">Berkas Umum</a></li> -->
                            </ul>
                        </details>
                    </li>

                    <li>
                        <a href="<?= base_url('peta') ?>" class="py-3 <?= $this->uri->segment(1) == 'peta' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-map-marker-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-1">Peta Digital</span>
                        </a>
                    </li>

                    <li>
                        <details <?= in_array($this->uri->segment(1), ['laporan', 'report_lp', 'report_masalah']) ? 'open' : '' ?> class="group">
                            <summary class="py-3">
                                <i class="mdi mdi-file-chart-outline text-xl"></i>
                                <span class="sidebar-text font-bold text-sm ml-1">Laporan</span>
                            </summary>
                            <ul>
                                <li><a href="<?= base_url('laporan') ?>">Laporan Nonlit</a></li>
                                <!-- <li><a href="<?= base_url('report_lp') ?>">Laporan Polisi</a></li>
                                <li><a href="<?= base_url('report_masalah') ?>">Laporan Masalah</a></li> -->
                            </ul>
                        </details>
                    </li>

                    <div class="px-8 mt-6 mb-2 sidebar-text">
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Management</span>
                    </div>

                    <li>
                        <a href="<?= base_url('arsip') ?>" class="py-3 <?= $this->uri->segment(1) == 'arsip' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-archive-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-1">Penyimpanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pic') ?>" class="py-3 <?= $this->uri->segment(1) == 'pic' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-account-group-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-1">Manajemen PIC</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-6 shrink-0 border-t border-slate-50">
                <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 text-red-400 hover:text-red-600 transition-colors px-2">
                    <i class="mdi mdi-logout-variant text-xl"></i>
                    <span class="sidebar-text font-bold text-xs uppercase tracking-widest">Logout System</span>
                </a>
            </div>
        </aside>

        <div class="flex-grow flex flex-col h-full overflow-hidden">

            <nav class="shrink-0 flex items-center justify-between px-8 navbar-glass z-10" style="height: var(--header-height);">
                <div class="flex items-center gap-4">
                    <button class="btn btn-square btn-ghost lg:hidden text-slate-600" id="mobile-toggle">
                        <i class="mdi mdi-menu text-2xl"></i>
                    </button>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-tight">
                            <?= $this->uri->segment(1) ? str_replace('_', ' ', $this->uri->segment(1)) : 'Overview' ?>
                        </h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pemerintah Kota Surabaya </span>
                        </div>
                    </div>

                </div>
                <div class="relative w-full max-w-md hidden md:block">
                    <div class="relative">
                        <i class="mdi mdi-magnify absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                        <input type="text"
                            id="global_search"
                            placeholder="Cari Nama Perkara / Masalah..."
                            class="input input-bordered w-full pl-12 pr-4 h-12 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-sm"
                            autocomplete="off">

                        <div id="search_results" class="absolute top-full left-0 w-full bg-white mt-2 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[999] hidden">
                            <div id="results_list" class="max-h-64 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-[11px] font-black text-slate-800 leading-none mb-1"><?= $this->session->userdata('username') ?: 'Administrator' ?></p>
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-[8px] font-black text-amber-700 uppercase tracking-tighter">Super Admin</span>
                        </div>
                        <div class="avatar">
                            <div class="w-10 h-10 rounded-2xl ring-4 ring-slate-50">
                                <img src="https://ui-avatars.com/api/?name=Admin&background=f59e0b&color=fff" />
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow overflow-y-auto bg-[#fcfcfd] no-scrollbar">
                <div class="p-6 md:p-8 lg:p-10 max-w-[1600px] mx-auto min-h-screen">
                    <div class="animate-in fade-in duration-500">
                        <?php $this->load->view($content) ?>
                    </div>
                </div>

                <footer class="py-10 px-10 flex flex-col md:flex-row items-center justify-between gap-4 bg-white border-t border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                        &copy; 2026 Pemerintah Kota Surabaya &bull; BPKAD
                    </p>
                    <div class="flex gap-4">
                        <span class="text-[9px] font-black text-amber-500 uppercase italic">Terintegrasi Database Terpusat</span>
                    </div>
                </footer>
            </main>
        </div>
    </div>
    <style>
        #search_results {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar untuk hasil pencarian */
        #results_list::-webkit-scrollbar {
            width: 4px;
        }

        #results_list::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }
    </style>
    <script>
        $(document).ready(function() {
            let isCollapsed = false;
            const sidebar = $('#sidebar-container');
            const overlay = $('#overlay');
            const toggleIcon = $('#toggle-icon');

            $('#toggle-sidebar').on('click', function() {
                if (!isCollapsed) {
                    sidebar.css('width', 'var(--sidebar-collapsed-width)');
                    $('.sidebar-text').hide();
                    $('details').removeAttr('open');
                    toggleIcon.removeClass('mdi-backburger').addClass('mdi-forwardburger');
                    isCollapsed = true;
                } else {
                    sidebar.css('width', 'var(--sidebar-width)');
                    setTimeout(() => {
                        $('.sidebar-text').fadeIn(200);
                    }, 200);
                    toggleIcon.removeClass('mdi-forwardburger').addClass('mdi-backburger');
                    isCollapsed = false;
                }
            });

            $('#mobile-toggle').on('click', function() {
                sidebar.addClass('mobile-open');
                overlay.removeClass('hidden').addClass('block').css('opacity', '1');
            });

            $('#overlay, #close-sidebar-mobile').on('click', function() {
                sidebar.removeClass('mobile-open');
                overlay.css('opacity', '0');
                setTimeout(() => overlay.removeClass('block').addClass('hidden'), 300);
            });
        });
    </script>



    <script>

        $(document).on('keydown', function(e) {
    // CTRL + K untuk fokus ke pencarian
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        $('#global_search').focus();
    }
});
        $(document).ready(function() {
            $('#global_search').on('keyup', function() {
                let q = $(this).val();
                let resultsBox = $('#search_results');
                let resultsList = $('#results_list');

                if (q.length > 2) {
                    $.ajax({
                        url: "<?= base_url('masalah/search_global') ?>",
                        type: "GET",
                        data: {
                            q: q
                        },
                        success: function(res) {
                            resultsList.html(res);
                            resultsBox.removeClass('hidden');
                        }
                    });
                } else {
                    resultsBox.addClass('hidden');
                }
            });

            // Menutup pencarian saat klik di luar area
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#global_search, #search_results').length) {
                    $('#search_results').addClass('hidden');
                }
            });
        });
    </script>

</body>

</html>