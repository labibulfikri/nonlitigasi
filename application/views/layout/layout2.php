<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-NONLIT | Professional Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --header-height: 72px;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 88px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Mencegah double scrollbar di body */
        }

        /* --- SIDEBAR GLASSMORPHISM --- */
        .sidebar-glass {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }

        /* Navigasi Sidebar */
        .menu li>a,
        .menu li>details>summary {
            margin: 4px 12px;
            border-radius: 12px !important;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .menu li>a:hover,
        .menu li>details>summary:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .active-menu {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%) !important;
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.5);
        }

        /* Animasi Teks Sidebar */
        .sidebar-text {
            transition: opacity 0.3s ease, transform 0.3s ease;
            display: inline-block;
        }

        /* Utility Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* Responsive Mobile */
        @media (max-width: 1023px) {
            #sidebar-container {
                position: fixed;
                transform: translateX(-100%);
            }

            #sidebar-container.mobile-open {
                transform: translateX(0);
                width: var(--sidebar-width) !important;
            }
        }

        /* Overlay */
        #overlay {
            transition: opacity 0.3s ease;
        }
    </style>
</head>

<body>

    <div id="overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[45] hidden opacity-0"></div>

    <div class="flex h-screen w-full overflow-hidden">

        <aside id="sidebar-container" class="sidebar-glass h-full flex flex-col shrink-0 overflow-hidden" style="width: var(--sidebar-width);">

            <div class="flex items-center justify-between px-6 shrink-0 border-b border-white/5" style="height: var(--header-height);">
                <div class="flex items-center gap-3" id="sidebar-branding">
                    <div class="bg-blue-600 p-2 rounded-xl shadow-lg shrink-0">
                        <img src="<?= base_url('assets/logononlit2.png') ?>" class="h-6 w-auto invert brightness-0" />
                    </div>
                    <div class="flex flex-col sidebar-text">
                        <span class="text-lg font-black tracking-tighter text-white leading-none">E-NONLIT</span>
                        <span class="text-[9px] text-blue-400 font-bold uppercase tracking-widest mt-1">Surabaya City</span>
                    </div>
                </div>
                <button id="toggle-sidebar" class="btn btn-ghost btn-xs text-white/30 hover:text-white hidden lg:flex px-0 shrink-0">
                    <i class="mdi mdi-circle-double text-2xl" id="toggle-icon"></i>
                </button>
                <button id="close-sidebar-mobile" class="btn btn-ghost btn-circle btn-sm text-white lg:hidden">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>

            <div class="flex-grow overflow-y-auto no-scrollbar py-6">
                <div class="px-8 mb-4 sidebar-text">
                    <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]">Menu Navigasi</span>
                </div>

                <ul class="menu p-0 w-full space-y-1">
                    <li>
                        <a href="<?= base_url('home') ?>" class="py-3.5 <?= $this->uri->segment(1) == 'home' || $this->uri->segment(1) == '' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-view-dashboard-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-2">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <details <?= in_array($this->uri->segment(1), ['nonlit', 'peta']) ? 'open' : '' ?> class="group">
                            <summary class="py-3.5">
                                <i class="mdi mdi-folder-outline text-xl"></i>
                                <span class="sidebar-text font-bold text-sm ml-2">Data Perkara</span>
                            </summary>
                            <ul class="before:hidden ml-6 mt-1 space-y-1">
                                <li>
                                    <a href="<?= base_url('nonlit') ?>" class="py-2.5 <?= $this->uri->segment(1) == 'nonlit' ? 'text-white font-bold bg-white/5' : '' ?>">
                                        <i class="mdi mdi-rhombus-medium text-[10px] mr-2"></i> Data Nonlit
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details <?= in_array($this->uri->segment(1), ['peta', 'peta']) ? 'open' : '' ?> class="group">
                            <summary class="py-3.5">
                                <i class="mdi mdi-folder-outline text-xl"></i>
                                <span class="sidebar-text font-bold text-sm ml-2">Data Peta</span>
                            </summary>
                            <ul class="before:hidden ml-6 mt-1 space-y-1">
                                <li>
                                    <a href="<?= base_url('peta') ?>" class="py-2.5 <?= $this->uri->segment(1) == 'peta' ? 'text-white font-bold bg-white/5' : '' ?>">
                                        <i class="mdi mdi-rhombus-medium text-[10px] mr-2"></i> Data Peta
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <li>
                        <a href="<?= base_url('laporan') ?>" class="py-3.5 <?= $this->uri->segment(1) == 'laporan' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-chart-box-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-2">Laporan Statistik</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pic') ?>" class="py-3.5 <?= $this->uri->segment(1) == 'pic' ? 'active-menu' : '' ?>">
                            <i class="mdi mdi-chart-box-outline text-xl"></i>
                            <span class="sidebar-text font-bold text-sm ml-2">Manajemen PIC</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-4 shrink-0 bg-black/20 border-t border-white/5">
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-ghost btn-sm btn-block text-red-400 hover:bg-red-500/10 border-none justify-start px-4 overflow-hidden">
                    <i class="mdi mdi-power text-lg mr-3 shrink-0"></i>
                    <span class="sidebar-text font-black text-xs uppercase tracking-widest">Logout</span>
                </a>
            </div>
        </aside>

        <div class="flex-grow flex flex-col h-full overflow-hidden">

            <nav class="shrink-0 flex items-center justify-between px-6 bg-white/80 backdrop-blur-md border-b border-slate-200 z-10" style="height: var(--header-height);">
                <div class="flex items-center gap-4">
                    <button class="btn btn-square btn-ghost lg:hidden text-slate-600" id="mobile-toggle">
                        <i class="mdi mdi-menu text-2xl"></i>
                    </button>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-tight leading-none">
                            <?= $this->uri->segment(1) ? ucfirst($this->uri->segment(1)) : 'Overview' ?>
                        </h2>
                        <span class="text-[10px] font-bold text-slate-400">Panel Kontrol Utama</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 pl-3 pr-1 py-1 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-[11px] font-black text-slate-800 leading-none"><?= $this->session->userdata('username') ?: 'Administrator' ?></p>
                            <p class="text-[9px] text-blue-600 font-bold uppercase tracking-tighter">Super Admin</p>
                        </div>
                        <div class="avatar">
                            <div class="w-8 rounded-xl ring ring-blue-500/20 ring-offset-1">
                                <img src="https://ui-avatars.com/api/?name=Admin&background=1e40af&color=fff" />
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow overflow-y-auto bg-[#f8fafc] no-scrollbar">
                <div class="p-6 md:p-10 max-w-[1600px] mx-auto min-h-screen">
                    <?php $this->load->view($content) ?>
                </div>

                <footer class="p-8 text-center bg-white border-t border-slate-100 mt-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                        &copy; 2026 Pemerintah Kota Surabaya &bull; BPKAD
                    </p>
                </footer>
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let isCollapsed = false;
            const sidebar = $('#sidebar-container');
            const overlay = $('#overlay');
            const toggleIcon = $('#toggle-icon');

            // --- DESKTOP TOGGLE LOGIC ---
            $('#toggle-sidebar').on('click', function() {
                if (!isCollapsed) {
                    // ACTION: MINIMIZE
                    sidebar.css('width', 'var(--sidebar-collapsed-width)');

                    // Sembunyikan teks dan branding detail
                    $('.sidebar-text').fadeOut(100);
                    $('details').removeAttr('open'); // Tutup menu dropdown

                    toggleIcon.removeClass('mdi-circle-double').addClass('mdi-menu-open');
                    isCollapsed = true;
                } else {
                    // ACTION: EXPAND (KEMBALI KE AWAL)
                    sidebar.css('width', 'var(--sidebar-width)');

                    // Tampilkan kembali teks setelah animasi lebar hampir selesai
                    setTimeout(() => {
                        $('.sidebar-text').fadeIn(200);
                    }, 200);

                    toggleIcon.removeClass('mdi-menu-open').addClass('mdi-circle-double');
                    isCollapsed = false;
                }
            });

            // --- MOBILE TOGGLE LOGIC ---
            $('#mobile-toggle').on('click', function() {
                sidebar.addClass('mobile-open');
                overlay.removeClass('hidden').addClass('block');
                setTimeout(() => overlay.css('opacity', '1'), 10);
            });

            $('#close-sidebar-mobile, #overlay').on('click', function() {
                sidebar.removeClass('mobile-open');
                overlay.css('opacity', '0');
                setTimeout(() => overlay.removeClass('block').addClass('hidden'), 300);
            });

            // Menutup dropdown otomatis jika sidebar di-minimize (opsional)
            $('.menu details').on('click', function() {
                if (isCollapsed) {
                    $('#toggle-sidebar').click(); // Auto expand jika menu diklik saat collapse
                }
            });
        });
    </script>

</body>

</html>