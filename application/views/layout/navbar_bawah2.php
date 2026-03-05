<nav class="bottom-navbar">
    <div class="container">
        <ul class="nav page-navigation">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('home') ?>">
                    <i class="mdi mdi-file-document-box menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="mdi mdi-cube-outline menu-icon"></i>
                    <span class="menu-title">Nonlit</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                    <ul>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url('nonlit') ?>">List Nonlit</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="../../pages/ui-features/typography.html">Typography</a></li> -->
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="mdi mdi-cube-outline menu-icon"></i>
                    <span class="menu-title">GIS</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                    <ul>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url('peta') ?>">PETA</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="../../pages/ui-features/typography.html">Typography</a></li> -->
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('laporan') ?>">
                    <i class="mdi mdi-file-document-box menu-icon"></i>
                    <span class="menu-title">Laporan</span>
                </a>
            </li>

            <?php if ($this->session->userdata('role') == "superadmin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('users') ?>">
                        <i class="mdi mdi-file-document-box menu-icon"></i>
                        <span class="menu-title">User </span>
                    </a>
                </li>
            <?php } ?>

        </ul>
    </div>
</nav>

<script>
    function submitOnEnter(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Mencegah submit form default
            document.getElementById("searchForm").submit();
        }
    }
</script>