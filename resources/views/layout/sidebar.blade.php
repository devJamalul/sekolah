@php
  $menuAdministrasi = navIsResource('schools') || navIsResource('users') ? 'show' : '';
  $menuKeuangan = navIsResource('tuition-type') || navIsResource('tuition') || navIsResource('expense') || navIsResource('transactions') ? 'show' : '';
  $menuSekolah = navIsResource('grade') || navIsResource('academy-year') || navIsResource('students') || navIsResource('classroom') || navIsResource('assign-classroom-student') ? 'show' : '';
  $menuKonfigurasi = navIsResource('config') || navIsResource('master-configs') ? 'show' : '';
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
    <div class="sidebar-brand-icon">
      <i class="fas fa-school"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('home') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Administrasi Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuAdministrasi"
      aria-expanded="true" aria-controls="menuAdministrasi">
      <i class="fas fa-wrench"></i>
      <span>Administrasi</span>
    </a>
    <div id="menuAdministrasi" class="collapse {{ $menuAdministrasi }}" aria-labelledby="menuAdministrasi"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('schools.index')
          <a href="{{ route('schools.index') }}" class="collapse-item {{ navIsResource('schools') }}">
            Sekolah
          </a>
        @endcan
        @can('users.index')
          <a href="{{ route('users.index') }}" class="collapse-item {{ navIsResource('users') }}">
            Pengguna
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Administrasi Menu -->

  <!-- Keuangan Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuKeuangan" aria-expanded="true"
      aria-controls="menuKeuangan">
      <i class="fas fa-home"></i>
      <span>Keuangan</span>
    </a>
    <div id="menuKeuangan" class="collapse {{ $menuKeuangan }}" aria-labelledby="menuKeuangan"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('tuition-type.index')
          <a href="{{ route('tuition-type.index') }}" class="collapse-item {{ navIsResource('tuition-type') }}">
            Tipe Biaya
          </a>
        @endcan
        @can('tuition.index')
          <a href="{{ route('tuition.index') }}" class="collapse-item {{ navIsResource('tuition') }}">
            Biaya
          </a>
        @endcan
        @can('expense.index')
          <a href="{{ route('expense.index') }}" class="collapse-item {{ navIsResource('expense') }}">
            Pengeluaran Biaya
          </a>
        @endcan
        @can('transactions.index')
          <a href="{{ route('transactions.index') }}" class="collapse-item {{ navIsResource('transactions') }}">
            Transaksi
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Keuangan Menu -->

  <!-- Sekolah Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuSekolah" aria-expanded="true"
      aria-controls="menuSekolah">
      <i class="fas fa-school"></i>
      <span>Sekolah</span>
    </a>
    <div id="menuSekolah" class="collapse {{ $menuSekolah }}" aria-labelledby="menuSekolah"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('grade.index')
          <a href="{{ route('grade.index') }}" class="collapse-item {{ navIsResource('grade') }}">
            Tingkat
          </a>
        @endcan
        @can('academy-year.index')
          <a href="{{ route('academy-year.index') }}" class="collapse-item {{ navIsResource('academy-year') }}">
            Tahun Akademik
          </a>
        @endcan
        @can('students.index')
          <a href="{{ route('students.index') }}" class="collapse-item {{ navIsResource('students') }}">
            Data Siswa
          </a>
        @endcan
        @can('classroom.index')
          <a href="{{ route('classroom.index') }}" class="collapse-item {{ navIsResource('classroom') }}">
            Ruang Kelas
          </a>
        @endcan
        @can('assign-classroom-student.index')
          <a href="{{ route('assign-classroom-student.index') }}" class="collapse-item {{ navIsResource('assign-classroom-student') }}">
            Rombongan Belajar
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Sekolah Menu -->

  <!-- Konfigurasi Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuKonfigurasi" aria-expanded="true"
      aria-controls="menuKonfigurasi">
      <i class="fas fa-home"></i>
      <span>Konfigurasi</span>
    </a>
    <div id="menuKonfigurasi" class="collapse {{ $menuKonfigurasi }}" aria-labelledby="menuKonfigurasi"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('master-configs.index')
          <a href="{{ route('master-configs.index') }}" class="collapse-item {{ navIsResource('master-configs') }}">
            Master Konfigurasi
          </a>
        @endcan
        @can('config.index')
          <a href="{{ route('config.index') }}" class="collapse-item {{ navIsResource('config') }}">
            Konfigurasi
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Konfigurasi Menu -->

  {{-- <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Addons
  </div>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item active">
    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
      aria-controls="collapsePages">
      <i class="fas fa-fw fa-folder"></i>
      <span>Pages</span>
    </a>
    <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Login Screens:</h6>
        <a class="collapse-item active" href="login.html">Login</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Charts -->
  <li class="nav-item">
    <a class="nav-link" href="charts.html">
      <i class="fas fa-fw fa-chart-area"></i>
      <span>Charts</span></a>
  </li> --}}

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
