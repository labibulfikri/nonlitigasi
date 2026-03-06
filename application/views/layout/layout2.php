<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-NONLIT | Professional Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            background-color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        :root {
            --header-height: 72px;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 88px;
        }

        /* Sidebar Gradient & Glass */
        .sidebar-gradient {
            background: linear-gradient(195deg, #0f172a 0%, #1e3a8a 100%);
        }

        #sidebar-container {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            width: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Glassmorphism Navbar */
        .navbar-fixed {
            height: var(--header-height);
            min-height: var(--header-height);
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 40;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0 1.5rem;
        }

        /* Menu Styling */
        .menu li>a,
        .menu li>details>summary {
            margin: 4px 12px;
            border-radius: 12px !important;
            transition: all 0.2s ease;
            color: rgba(255, 255, 255, 0.7);
        }

        .menu li>a:hover,
        .menu li>details>summary:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .active-menu {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
        }

        .sidebar-text {
            transition: opacity 0.3s, transform 0.3s;
            white-space: nowrap;
        }

        /* Custom Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .no-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        /* Responsive Logic */
        @media (min-width: 1024px) {
            .ml-sidebar {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }

            .ml-sidebar-collapsed {
                margin-left: var(--sidebar-collapsed-width);
                width: calc(100% - var(--sidebar-collapsed-width));
            }
        }

        @media (max-width: 1023px) {
            #sidebar-container {
                transform: translateX(-100%);
            }

            #sidebar-container.mobile-open {
                transform: translateX(0);
                width: var(--sidebar-width) !important;
            }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.5);
                z-index: 45;
                backdrop-filter: blur(4px);
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Styling agar DataTable selaras dengan DaisyUI */
        .dataTables_wrapper {
            padding: 1.5rem;
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .dataTables_wrapper .dataTables_length select {
            @apply select select-bordered select-sm rounded-lg mr-2;
        }

        .dataTables_wrapper .dataTables_filter input {
            @apply input input-bordered input-sm rounded-lg ml-2;
        }

        table.dataTable {
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            @apply my-4 rounded-lg overflow-hidden;
        }

        table.dataTable thead th {
            @apply bg-slate-50 text-slate-600 font-bold text-xs uppercase tracking-wider py-4 px-4 border-b border-slate-200;
        }

        table.dataTable tbody td {
            @apply py-4 px-4 text-sm text-slate-600 border-b border-slate-100;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply btn btn-primary btn-sm rounded-lg text-white border-none !important;
            background: #2563eb !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current) {
            @apply btn btn-ghost btn-sm rounded-lg !important;
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="overlay"></div>

    <aside id="sidebar-container" class="sidebar-gradient shadow-2xl" style="width: var(--sidebar-width);">
        <div class="flex flex-col h-full no-scrollbar overflow-y-auto overflow-x-hidden">

            <div class="flex items-center justify-between px-6 shrink-0" style="height: var(--header-height);">
                <div class="flex items-center gap-3 sidebar-text">
                    <div class="bg-white/10 backdrop-blur-md p-2 rounded-xl border border-white/20 shadow-inner">
                        <img src="<?= base_url('assets/logononlit2.png') ?>" class="h-7 w-auto" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold tracking-tight text-white leading-none">E-NONLIT</span>
                        <span class="text-[9px] text-blue-400 font-bold tracking-widest uppercase mt-1">Surabaya City</span>
                    </div>
                </div>
                <button id="toggle-sidebar" class="btn btn-ghost btn-sm text-white/50 hover:text-white hidden lg:flex px-0">
                    <i class="mdi mdi-circle-double text-2xl" id="toggle-icon"></i>
                </button>
                <button id="close-sidebar-mobile" class="btn btn-ghost btn-circle btn-sm text-white lg:hidden">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <div class="px-4 py-2 mt-4 sidebar-text">
                <span class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] ml-4">Navigasi Utama</span>
            </div>

            <ul class="menu p-0 w-full space-y-1 flex-grow">
                <li>
                    <a href="<?= base_url('home') ?>" class="flex gap-4 py-3 <?= $this->uri->segment(1) == 'home' || $this->uri->segment(1) == '' ? 'active-menu' : '' ?>">
                        <i class="mdi mdi-grid-large text-xl"></i>
                        <span class="sidebar-text font-medium text-sm">Dashboard Overview</span>
                    </a>
                </li>

                <li>
                    <details <?= in_array($this->uri->segment(1), ['nonlit', 'peta']) ? 'open' : '' ?> class="group">
                        <summary class="flex gap-4 py-3">
                            <i class="mdi mdi-database-outline text-xl"></i>
                            <span class="sidebar-text font-medium text-sm">Data Perkara</span>
                        </summary>
                        <ul class="before:hidden ml-4 mt-1 space-y-1">
                            <li>
                                <a href="<?= base_url('nonlit') ?>" class="py-2.5 rounded-lg hover:bg-white/5 <?= $this->uri->segment(1) == 'nonlit' ? 'text-white font-bold bg-white/10' : '' ?>">
                                    <i class="mdi mdi-minus mr-2 opacity-50"></i> Data Nonlit
                                </a>
                            </li>
                            <!-- <li>
                                <a href="<?= base_url('peta') ?>" class="py-2.5 rounded-lg hover:bg-white/5 <?= $this->uri->segment(1) == 'peta' ? 'text-white font-bold bg-white/10' : '' ?>">
                                    <i class="mdi mdi-minus mr-2 opacity-50"></i> Data Peta
                                </a>
                            </li> -->
                        </ul>
                    </details>
                </li>

                <li>
                    <a href="<?= base_url('laporan') ?>" class="flex gap-4 py-3 <?= $this->uri->segment(1) == 'laporan' ? 'active-menu' : '' ?>">
                        <i class="mdi mdi-chart-box-outline text-xl"></i>
                        <span class="sidebar-text font-medium text-sm">Laporan Statistik</span>
                    </a>
                </li>
            </ul>

            <!-- <div class="p-5 sidebar-text">
                <div class="bg-blue-500/10 border border-blue-400/20 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2 text-blue-200">
                        <i class="mdi mdi-information-outline text-lg"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Bantuan</span>
                    </div>
                    <p class="text-[11px] text-blue-200/60 leading-relaxed mb-3">Jika terjadi kendala sistem, silakan hubungi tim teknis.</p>
                    <button class="btn btn-xs btn-primary btn-block rounded-lg shadow-lg shadow-primary/20">IT Support</button>
                </div>
            </div> -->

            <div class="p-4 bg-black/20">
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-ghost btn-sm btn-block text-error hover:bg-error/10 border-none justify-start px-4">
                    <i class="mdi mdi-logout-variant text-lg mr-3"></i>
                    <span class="sidebar-text font-bold">KELUAR</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="main-wrapper ml-sidebar" id="main-content">

        <nav class="navbar-fixed flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <button class="btn btn-circle btn-ghost lg:hidden bg-slate-100" id="mobile-toggle">
                    <i class="mdi mdi-menu text-xl text-slate-700"></i>
                </button>

                <div class="flex flex-col">
                    <h2 class="text-lg font-bold text-slate-800 leading-tight">
                        <?= $this->uri->segment(1) ? ucfirst($this->uri->segment(1)) : 'Overview' ?>
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Live Monitoring</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="flex items-center gap-3 pl-3 pr-1 py-1 rounded-full border border-slate-200 hover:border-primary transition-all cursor-pointer bg-white shadow-sm group">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 leading-none mb-1"><?= $this->session->userdata('username') ?: 'Administrator' ?></p>
                            <p class="text-[10px] text-primary font-bold uppercase tracking-tight opacity-70">Super Admin</p>
                        </div>
                        <div class="avatar">
                            <div class="w-9 rounded-full ring ring-primary/20 ring-offset-2 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name=<?= $this->session->userdata('username') ?>&background=1e40af&color=fff" />
                            </div>
                        </div>
                    </div>
                    <ul tabindex="0" class="mt-4 z-[1] p-2 shadow-2xl menu menu-sm dropdown-content bg-base-100 rounded-2xl w-60 border border-slate-100">
                        <li class="menu-title text-slate-400 uppercase text-[10px]">Akun Saya</li>
                        <li><a><i class="mdi mdi-account-circle-outline text-lg"></i> Profil Lengkap</a></li>
                        <li><a><i class="mdi mdi-shield-check-outline text-lg"></i> Keamanan Data</a></li>
                        <div class="divider my-1 opacity-50"></div>
                        <li><a href="<?= base_url('auth/logout') ?>" class="text-error font-bold"><i class="mdi mdi-logout-variant text-lg"></i> Keluar Aplikasi</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content-scroll no-scrollbar overflow-y-auto overflow-x-hidden flex-grow bg-[#f8fafc]">
            <div class="p-6 md:p-10 max-w-[1600px] mx-auto">
                <?php $this->load->view($content) ?>
            </div>

            <footer class="p-8 text-center bg-white border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    &copy; 2026 Pemerintah Kota Surabaya &bull; Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya
                </p>
            </footer>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            let isCollapsed = false;
            const sidebar = $('#sidebar-container');
            const mainContent = $('#main-content');
            const overlay = $('#overlay');

            // Toggle Desktop Function
            $('#toggle-sidebar').on('click', function() {
                if (!isCollapsed) {
                    sidebar.css('width', 'var(--sidebar-collapsed-width)');
                    mainContent.removeClass('ml-sidebar').addClass('ml-sidebar-collapsed');
                    $('.sidebar-text').addClass('opacity-0 invisible').css('width', '0');
                    $('details').removeAttr('open');
                    $('#toggle-icon').removeClass('mdi-circle-double').addClass('mdi-menu-open');
                    isCollapsed = true;
                } else {
                    sidebar.css('width', 'var(--sidebar-width)');
                    mainContent.removeClass('ml-sidebar-collapsed').addClass('ml-sidebar');
                    $('.sidebar-text').removeClass('opacity-0 invisible').css('width', 'auto');
                    $('#toggle-icon').removeClass('mdi-menu-open').addClass('mdi-circle-double');
                    isCollapsed = false;
                }
            });

            // Mobile Logic
            $('#mobile-toggle').on('click', function() {
                sidebar.addClass('mobile-open');
                overlay.addClass('active');
            });

            $('#close-sidebar-mobile, #overlay').on('click', function() {
                sidebar.removeClass('mobile-open');
                overlay.removeClass('active');
            });
        });
    </script>

</body>

</html>