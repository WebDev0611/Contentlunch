<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">
                                    {{ Auth::user()->name }}
                                </strong>
                            </span>
                            {{-- <span class="text-muted text-xs block">Example menu <b class="caret"></b></span> --}}
                        </span>
                    </a>
                </div>
                <div class="logo-element">
                    <img src="/images/logo.svg" style="width: 40px">
                </div>
            </li>
            <li>
                <a href="{{ route('admin.dashboard.index') }}">
                    <i class="fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a>
                    <i class="fa fa-user"></i>
                    <span class="nav-label">Users</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="{{ route('admin.users.index') }}">List of Users</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
