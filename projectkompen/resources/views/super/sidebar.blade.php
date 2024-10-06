<!-- wrapper -->
<div class="wrapper">
    <!--sidebar-wrapper-->
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div class="">
                <img src="{{asset('assets/images/logo_pnj.png')}}" class="logo-icon-2" alt="logo" />
            </div>
            <div>
                <h4 style="color: #ecf0f1" class="logo-text">SiKompen</h4>
            </div>
            <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <!--navigation-->
        <ul class="metismenu" id="menu">

            @if (auth()->guard('pengguna')->user()->role == 'Admin Prodi')
            <li>
                <a href="{{ route('master.dashboard') }}">
                    <div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Dashboard</div>
                </a>
            </li>
            <li class="menu-label" style="color: #ecf0f1">Menu Admin Prodi</li>
            <li>
                <a href="{{ route('master.pekerjaan.listpekerjaan') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-briefcase"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Pekerjaan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.user.listuser') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-user"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu User</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.mahasiswa.listmahasiswa') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-book"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Mahasiswa</div>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('master.kelas.listkelas') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-group"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Kelas</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.prodi.listprodi') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-building"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Prodi</div>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('master.setup.listsetup') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-cog"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Setup Bertugas</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.pengajuan.listpengajuan') }}">
                    <div class="parent-icon icon-color-4"><i class="bx bx-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Pengajuan</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'Pengawas')
            <li class="menu-label" style="color: #ecf0f1">Menu Pengawas</li>
            <li>
                <a href="{{ route('pengawas.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Pengajuan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('pengawas.listdisetujui') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-check"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Disetujui</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.pekerjaan.listpekerjaan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-briefcase"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Pekerjaan</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'Kepala Lab')
            <li class="menu-label" style="color: #ecf0f1">Menu Kepala Lab</li>
            <li>
                <a href="{{ route('kalab.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Pengajuan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('kalab.listdisetujui') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-check"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Disetujui</div>
                </a>
            </li>
            <li>
                <a href="{{ route('kalab.paksa.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-list-ul"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Semua Pengajuan(Untuk Pengajuan Paksa)</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.pekerjaan.listpekerjaan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-briefcase"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Pekerjaan</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'PLP')
            <li class="menu-label" style="color: #ecf0f1">Menu PLP</li>
            <li>
                <a href="{{ route('plp.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Pengajuan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('plp.listdisetujui') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-check"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Disetujui</div>
                </a>
            </li>
            <li>
                <a href="{{ route('plp.paksa.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-list-ul"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Semua Pengajuan(Untuk Pengajuan Paksa)</div>
                </a>
            </li>
            <li>
                <a href="{{ route('master.pekerjaan.listpekerjaan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-briefcase"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Menu Pekerjaan</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'Dosen Pembimbing Akademik')
            <li class="menu-label" style="color: #ecf0f1">Menu Dosen Pembimbing Akademik</li>
            <li>
                <a href="{{ route('dospem.listbebas') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-book"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Form Bebas Kompen</div>
                </a>
            </li>
            <li>
                <a href="{{ route('dospem.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-book"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Semua Pengajuan Kompen</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'KPS')
            <li class="menu-label" style="color: #ecf0f1">Menu KPS</li>
            <li>
                <a href="{{ route('kps.listmahasiswa') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-user"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Mahasiswa</div>
                </a>
            </li>
            <li>
                <a href="{{ route('kps.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-book"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Semua Pengajuan Kompen</div>
                </a>
            </li>
            @endif
            @if (auth()->guard('pengguna')->user()->role == 'Kajur')
            <li class="menu-label" style="color: #ecf0f1">Menu Kajur</li>
            <li>
                <a href="{{ route('kajur.listmahasiswa') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-user"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Mahasiswa</div>
                </a>
            </li>
            <li>
                <a href="{{ route('kajur.listpengajuan') }}">
                    <div class="parent-icon icon-color-2"><i class="bx bx-book"></i></div>
                    <div class="menu-title" style="color: #ecf0f1">Daftar Semua Pengajuan Kompen</div>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>