@php
  $menuAdministrasi = Request::is('schools') || Request::is('schools/*') || Request::is('users') || Request::is('users/*') ? 'show' : '';
  $menuKeuangan = Request::is('tuition-type') || Request::is('tuition-type/*') || Request::is('tuition') || Request::is('tuition/*') || Request::is('wallet') || Request::is('wallet/*') || Request::is('payment-type') || Request::is('payment-type/*') ? 'show' : '';
  $menuSekolah = Request::is('grade') || Request::is('grade/*') || Request::is('academy-year') || Request::is('academy-year/*') || Request::is('students') || Request::is('students/*') || Request::is('classroom') || Request::is('classroom/*') || Request::is('assign-classroom-student') || Request::is('assign-classroom-student/*') ? 'show' : '';
  $menuKonfigurasi = Request::is('config') || Request::is('config/*') || Request::is('master-configs') || Request::is('master-configs/*') ? 'show' : '';
  $menuTransaksi = Request::is('transactions') || Request::is('transactions/*') || Request::is('invoices') || Request::is('invoices/*') || Request::is('expense') || Request::is('expense/*') ? 'show' : '';
  $menuLaporan = Request::is('report-student-tuition') || Request::is('report-student-tuition/*') || Request::is('expense-report') || Request::is('expense-report/*') || Request::is('reports/students') || Request::is('reports/students/*') || Request::is('reports/invoices') || Request::is('reports/invoices/*') ? 'show' : '';
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
    <div class="sidebar-brand-icon">
      <i class="fas fa-school"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item {{ Request::is('home') || Request::is('home/*') ? 'active' : '' }}">
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
      <i class="fas fa-chess"></i>
      <span>Administrasi</span>
    </a>
    <div id="menuAdministrasi" class="collapse {{ $menuAdministrasi }}" aria-labelledby="menuAdministrasi"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('schools.index')
          <a href="{{ route('schools.index') }}"
            class="collapse-item  {{ Request::is('schools') || Request::is('schools/*') ? 'active' : '' }}">
            Sekolah
          </a>
        @endcan
        @can('users.index')
          <a href="{{ route('users.index') }}"
            class="collapse-item  {{ Request::is('users') || Request::is('users/*') ? 'active' : '' }}">
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
      <i class="fas fa-coins"></i>
      <span>Keuangan</span>
    </a>
    <div id="menuKeuangan" class="collapse {{ $menuKeuangan }}" aria-labelledby="menuKeuangan"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('tuition-type.index')
          <a href="{{ route('tuition-type.index') }}"
            class="collapse-item {{ Request::is('tuition-type') || Request::is('tuition-type/*') ? 'active' : '' }}">
            Tipe Biaya
          </a>
        @endcan
        @can('tuition.index')
          <a href="{{ route('tuition.index') }}"
            class="collapse-item {{ Request::is('tuition') || Request::is('tuition/*') ? 'active' : '' }}">
            Biaya
          </a>
        @endcan
        @can('payment-type.index')
          <a href="{{ route('payment-type.index') }}"
            class="collapse-item {{ Request::is('payment-type') || Request::is('payment-type/*') ? 'active' : '' }}">
            Tipe Pembayaran
          </a>
        @endcan
        @can('wallet.index')
          <a href="{{ route('wallet.index') }}"
            class="collapse-item {{ Request::is('wallet') || Request::is('wallet/*') ? 'active' : '' }}">
            Dompet
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
          <a href="{{ route('grade.index') }}"
            class="collapse-item {{ Request::is('grade') || Request::is('grade/*') ? 'active' : '' }}">
            Tingkat
          </a>
        @endcan
        @can('academy-year.index')
          <a href="{{ route('academy-year.index') }}"
            class="collapse-item {{ Request::is('academy-year') || Request::is('academy-year/*') ? 'active' : '' }}">
            Tahun Akademik
          </a>
        @endcan
        @can('students.index')
          <a href="{{ route('students.index') }}"
            class="collapse-item {{ Request::is('students') || Request::is('students/*') ? 'active' : '' }}">
            Data Siswa
          </a>
        @endcan
        @can('classroom.index')
          <a href="{{ route('classroom.index') }}"
            class="collapse-item {{ Request::is('classroom') || Request::is('classroom/*') ? 'active' : '' }}">
            Ruang Kelas
          </a>
        @endcan
        @can('assign-classroom-student.index')
          <a href="{{ route('assign-classroom-student.index') }}"
            class="collapse-item {{ Request::is('assign-classroom-student') || Request::is('assign-classroom-student/*') ? 'active' : '' }}">
            Rombongan Belajar
          </a>
        @endcan
        @can('approvals.index')
          <a href="{{ route('approvals.index') }}"
            class="collapse-item {{ Request::is('approvals') || Request::is('approvals/*') ? 'active' : '' }}">
            Persetujuan
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Sekolah Menu -->

  <!-- Transaksi Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuTransaksi"
      aria-expanded="true" aria-controls="menuTransaksi">
      <i class="fas fa-chart-line"></i>
      <span>Transaksi</span>
    </a>
    <div id="menuTransaksi" class="collapse {{ $menuTransaksi }}" aria-labelledby="menuTransaksi"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Pemasukan</h6>
        @can('invoices.index')
          <a href="{{ route('invoices.index') }}"
            class="collapse-item {{ Request::is('invoices') || Request::is('invoices/*') ? 'active' : '' }}">
            Invoice
          </a>
        @endcan
        @can('transactions.index')
          <a href="{{ route('transactions.index') }}"
            class="collapse-item {{ Request::is('transactions') || Request::is('transactions/*') ? 'active' : '' }}">
            Pembayaran Sekolah
          </a>
        @endcan
        <h6 class="collapse-header">Pengeluaran</h6>
        @can('expense.index')
          <a href="{{ route('expense.index') }}"
            class="collapse-item {{ Request::is('expense') || Request::is('expense/*') ? 'active' : '' }}">
            Pengeluaran Biaya
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Transaksi Menu -->

  <!-- Laporan Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuLaporan" aria-expanded="true"
      aria-controls="menuLaporan">
      <i class="fas fa-flag-checkered"></i>
      <span>Laporan</span>
    </a>
    <div id="menuLaporan" class="collapse {{ $menuLaporan }}" aria-labelledby="menuLaporan"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Pemasukan</h6>
        @can('invoices.report')
          <a href="{{ route('invoices.report') }}"
            class="collapse-item {{ Request::is('reports/invoices') || Request::is('reports/invoices/*') ? 'active' : '' }}">
            Invoice
          </a>
        @endcan
        @can('transaction-report.index')
          <a href="{{ route('report-student-tuition') }}"
            class="collapse-item {{ Request::is('report-student-tuition') || Request::is('report-student-tuition/*') ? 'active' : '' }}">
            Pembayaran Sekolah
          </a>
        @endcan
        <h6 class="collapse-header">Pengeluaran</h6>
        @can('expense-report.index')
          <a href="{{ route('expense-report.index') }}"
            class="collapse-item {{ Request::is('expense-report') || Request::is('expense-report/*') ? 'active' : '' }}">
            Pengeluaran Biaya
          </a>
        @endcan
        <h6 class="collapse-header">Lainnya</h6>
        @can('students.report')
          <a href="{{ route('reports.students') }}"
            class="collapse-item {{ Request::is('reports/students') || Request::is('reports/students/*') ? 'active' : '' }}">
            Siswa
          </a>
        @endcan
      </div>
    </div>
  </li>
  <!-- End Laporan Menu -->

  <!-- Konfigurasi Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuKonfigurasi"
      aria-expanded="true" aria-controls="menuKonfigurasi">
      <i class="fas fa-wrench"></i>
      <span>Konfigurasi</span>
    </a>
    <div id="menuKonfigurasi" class="collapse {{ $menuKonfigurasi }}" aria-labelledby="menuKonfigurasi"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @can('master-configs.index')
          <a href="{{ route('master-configs.index') }}"
            class="collapse-item {{ Request::is('master-configs') || Request::is('master-configs/*') ? 'active' : '' }}">
            Master Konfigurasi
          </a>
        @endcan
        @can('config.index')
          <a href="{{ route('config.index') }}"
            class="collapse-item {{ Request::is('config') || Request::is('config/*') ? 'active' : '' }}">
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
