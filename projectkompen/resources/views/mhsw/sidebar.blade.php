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
        <hr>
        <!--navigation-->
        <ul class="metismenu" id="menu">

            <li>
                <a href="{{ route('mahasiswa.listdata') }}">
                    <div style="color: #ecf0f1" class="parent-icon icon-color-4"><i class="bx bxs-user"></i></div>
                    <div class="menu-title" style="color: #ecf0f1;">Data Mahasiswa</div>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.listpekerjaan') }}">
                    <div style="color: #ecf0f1" class="parent-icon icon-color-4"><i class="bx bxs-briefcase"></i></div>
                    <div class="menu-title" style="color: #ecf0f1;">List Pekerjaan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.pekerjaan.listdiambil') }}">
                    <div style="color: #ecf0f1" class="parent-icon icon-color-4"><i class="bx bxs-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1;">List Pengajuan Pekerjaan</div>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.bebas.listbebas') }}">
                    <div style="color: #ecf0f1" class="parent-icon icon-color-4"><i class="bx bxs-file"></i></div>
                    <div class="menu-title" style="color: #ecf0f1;">List Pengajuan Bebas Kompen</div>
                </a>
            </li>
        </ul>
    </div>
</div>