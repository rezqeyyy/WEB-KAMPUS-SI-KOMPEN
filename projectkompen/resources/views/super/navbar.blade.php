<!--header-->
<header class="top-header">
    <nav class="navbar navbar-expand" style="background-color: #2c3e50;">
        <div class="left-topbar d-flex align-items-center">
            <a href="javascript:;" class="toggle-btn"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <div class="media-body user-info">
            <h2 style="color: #ecf0f1; class=" user-name mb-0">{{$nama_user}}</h2>
            </h1>
        </div>
        <div class="right-topbar ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown dropdown-user-profile">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                        data-bs-toggle="dropdown">
                        <div class="d-flex user-box align-items-center" style="margin-top: 8px;">
                            <<div class="media user-box align-items-center">
                                <img src="{{asset('assets/images/icons/user.png')}}" class="user-img rounded-circle"
                                    alt="user avatar">
                        </div>
        </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="{{ route('user.profile.edit') }}"><i class="bx bx-user"></i><span>User
                    Setting
                </span></a>
            <a class="dropdown-item" href="{{ route('logout') }}"><i class="bx bx-log-out"></i><span>Logout</span></a>
        </div>
        </li>
        </ul>
        </div>
    </nav>
</header>
<!--end header-->