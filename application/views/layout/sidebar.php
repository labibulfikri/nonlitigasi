<aside class="bg-base-200 w-64 min-h-screen border-r border-base-300 hidden lg:block" id="sidebar">
  <div class="p-4 flex flex-col items-center border-b border-base-300 mb-2">
    <div class="avatar placeholder mb-2">
      <div class="bg-primary text-primary-content rounded-full w-12">
        <span class="text-xl">E</span>
      </div>
    </div>
    <span class="font-bold text-lg tracking-wide">E-Nonlit</span>
  </div>

  <ul class="menu menu-md w-full px-4 rounded-box">
    <li>
      <a href="<?php echo base_url('home') ?>" class="flex gap-3 items-center">
        <i class="mdi mdi-view-dashboard-outline text-xl"></i>
        Dashboard
      </a>
    </li>

    <li class="menu-title mt-4">
      <span class="text-xs font-bold uppercase opacity-50">Manajemen Data</span>
    </li>

    <li>
      <details id="ui-basic">
        <summary class="flex gap-3 items-center">
          <i class="mdi mdi-database-outline text-xl"></i>
          Nonlit
        </summary>
        <ul>
          <li>
            <a href="<?php echo base_url('nonlit') ?>" class="py-2">
              <i class="mdi mdi-format-list-bulleted"></i> List Nonlit
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('peta') ?>" class="py-2">
              <i class="mdi mdi-map-marker-radius"></i> List Peta
            </a>
          </li>
        </ul>
      </details>
    </li>

    <div class="mt-auto border-t border-base-300 pt-4">
      <li>
        <a href="<?php echo base_url('auth/logout') ?>" class="text-error flex gap-3 items-center hover:bg-error/10">
          <i class="mdi mdi-logout text-xl"></i>
          Logout
        </a>
      </li>
    </div>
  </ul>
</aside>