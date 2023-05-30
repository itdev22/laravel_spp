<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/dist/img/smapa.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SMAN 4 KEDIRI</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="my-auto ml-3">
                <i class="nav-icon fas fa-user-circle fa-2x text-light"></i>
            </div>
            <div class="info">
                <a href="javascript:void(0)" class="d-block">{{ Auth::user()->username }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                @role('admin')
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}"
                            class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home.index') }}"
                            class="nav-link {{ Request::segment(1) == 'home' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </li>
                @endrole

                @role('petugas')
                    <li class="nav-item">
                        <a href="{{ route('home.index') }}"
                            class="nav-link {{ Request::segment(1) == 'home' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </li>
                    @elserole('siswa')
                    <li class="nav-item">
                        <a href="{{ route('home.index') }}"
                            class="nav-link {{ Request::segment(1) == 'home' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </li>
                @endrole

                @role('admin')
                    <li class="nav-header">MANAJEMEN DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.index') }}"
                            class="nav-link {{ Request::segment(2) == 'siswa' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Siswa
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('pembayaran-spp.index') }}"
                            class="nav-link {{ Request::segment(2) == 'pembayaran-spp' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Pembayaran
                            </p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('kelas.index') }}"
                            class="nav-link {{ Request::segment(2) == 'kelas' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>
                                Kelas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin-list.index') }}"
                            class="nav-link {{ Request::segment(2) == 'admin-list' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Admin
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}"
                            class="nav-link {{ Request::segment(2) == 'user' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('petugas.index') }}"
                            class="nav-link {{ Request::segment(2) == 'petugas' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Petugas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('spp.index') }}"
                            class="nav-link {{ Request::segment(2) == 'spp' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Parmas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.index') }}"
                            class="nav-link {{ Request::segment(1) == 'admin' && Request::segment(2) == 'tagihan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Tagihan
                            </p>
                        </a>
                    </li>
                @endrole

                @role('petugas')
                    <li class="nav-header">MANAJEMEN DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.index') }}"
                            class="nav-link {{ Request::segment(2) == 'siswa' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Siswa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pembayaran-spp.index') }}"
                            class="nav-link {{ Request::segment(2) == 'pembayaran-spp' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Pembayaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kelas.index') }}"
                            class="nav-link {{ Request::segment(2) == 'kelas' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>
                                Kelas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('spp.index') }}"
                            class="nav-link {{ Request::segment(2) == 'spp' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Parmas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.index') }}"
                            class="nav-link {{ Request::segment(2) == 'tagihan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Tagihan
                            </p>
                        </a>
                    </li>
                @endrole

                @role('admin|petugas')
                    <li class="nav-header">PEMBAYARAN PARMAS</li>
                    <li class="nav-item">
                        <a href="{{ route('parmas.pembayaran.index') }}"
                            class="nav-link {{ Request::segment(2) == 'parmas' && Request::segment(3) == 'bayar' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Pembayaran Parmas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('parmas.pembayaran.status-pembayaran') }}"
                            class="nav-link {{ Request::segment(2) == 'parmas' && Request::segment(3) == 'status-pembayaran' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Status Pembayaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('parmas.pembayaran.history-pembayaran') }}"
                            class="nav-link {{ Request::segment(2) == 'parmas' && Request::segment(3) == 'history-pembayaran' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                History Pembayaran
                            </p>
                        </a>
                    </li>
                @endrole

                @role('admin|petugas')
                    <li class="nav-header">PEMBAYARAN TAGIHAN</li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.pembayaran.index') }}"
                            class="nav-link {{ Request::segment(2) == 'tagihan' && Request::segment(3) == 'bayar' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Pembayaran Tagihan
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.pembayaran.status-pembayaran') }}"
                            class="nav-link {{ Request::segment(2) == 'tagihan' && Request::segment(3) == 'status-pembayaran' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Status Pembayaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.pembayaran.history-pembayaran') }}"
                            class="nav-link {{ Request::segment(2) == 'tagihan' && Request::segment(3) == 'history-pembayaran' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                History Pembayaran
                            </p>
                        </a>
                    </li>
                @endrole

                @role('admin|petugas')
                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('parmas.pembayaran.laporan') }}"
                            class="nav-link {{ Request::segment(2) == 'laporan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Laporan Pembayaran Parmas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tagihan.pembayaran.laporan') }}"
                            class="nav-link {{ Request::segment(2) == 'laporan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Laporan Pembayaran Tagihan
                            </p>
                        </a>
                    </li>
                @endrole

                @role('siswa')
                    <li class="nav-header">PEMBAYARAN</li>
                    <li class="nav-item {{ Request::segment(2) == 'permas' ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::segment(2) == 'permas' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Permas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ Request::segment(2) == 'permas' ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.pembayaran-permas.index') }}"
                                    class="nav-link {{ Request::is('siswa/permas/pembayaran*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-money-bill-wave-alt"></i>
                                    <p>
                                        Pembayaran
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.history-permas.index') }}"
                                    class="nav-link {{ Request::is('siswa/permas/history*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        History
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.laporan-permas.index') }}"
                                    class="nav-link {{ Request::is('siswa/permas/laporan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Laporan
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ Request::segment(2) == 'tagihan' ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::segment(2) == 'tagihan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Tagihan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ Request::segment(2) == 'tagihan' ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.pembayaran-tagihan.index') }}"
                                    class="nav-link {{ Request::is('siswa/tagihan/pembayaran*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-money-bill-wave-alt"></i>
                                    <p>
                                        Pembayaran
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.history-tagihan.index') }}"
                                    class="nav-link {{ Request::is('siswa/tagihan/history*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        History
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a href="{{ route('siswa.laporan-tagihan.index') }}"
                                    class="nav-link {{ Request::is('siswa/tagihan/laporan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Laporan
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">BUKTI PEMBAYARAN</li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.pembayaran-spp') }}"
                            class="nav-link {{ Request::segment(2) == 'pembayaran-spp' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Status Pembayaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.history-pembayaran') }}"
                            class="nav-link {{ Request::is('siswa/history-pembayaran') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                History Pembayaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.laporan-pembayaran') }}"
                            class="nav-link {{ Request::is('siswa/laporan-pembayaran') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Laporan
                            </p>
                        </a>
                    </li>
                @endrole

                @role('admin')
                    <li class="nav-header">ROLES - PERMISSIONS</li>
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}"
                            class="nav-link {{ Request::segment(2) == 'roles' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Roles List
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('permissions.index') }}"
                            class="nav-link {{ Request::segment(2) == 'permissions' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>
                                Permissions List
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('role-permission.index') }}"
                            class="nav-link {{ Request::segment(2) == 'role-permission' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Role - Permission
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-role.index') }}"
                            class="nav-link {{ Request::segment(2) == 'user-role' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                User - Role
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-permission.index') }}"
                            class="nav-link {{ Request::segment(2) == 'user-permission' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                User - Permission
                            </p>
                        </a>
                    </li>
                @endrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
