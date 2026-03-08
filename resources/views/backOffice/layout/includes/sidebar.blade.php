
{{--Sidebar start--}}
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;margin-top: 10px;">
            <a href="{{route('dashboard')}}" class="site_title"><i class="fa fa-home"></i> <span>Appli </span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{Auth::user()->username}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('dashboard')}}">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-users"></i> Gestion Utilisateurs <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('users.index')}}">Liste des Utilisateurs</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html"
                   onclick="event.preventDefault();this.closest('form').submit();">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            </form>

        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
{{--Sidebar End--}}
