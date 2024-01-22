<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="sidebar" data-color="purple" data-background-color="black"
    data-image="../assets/img/sidebar-2.jpg">
    <div class="logo">
        <a href="#" class="simple-text logo-normal">
            Dashboard
        </a>
    </div>
    <div class="sidebar-wrapper flex-wrap flex-column">
        <ul class="nav">
            <li class="nav-item {{ Request::is('business.create') ? 'active' : '' }} ">
                <a class="nav-link" href="{{ url('business-create') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Add Business</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('businesses') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('businesses') }}">
                    <i class="material-icons">person</i>
                    <p>Business-list</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('user') }}">
                    <i class="material-icons">person</i>
                    <p>Users-list</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('user.create') ? 'active' : '' }} ">
                <a class="nav-link" href="{{ url('user-create') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Add User</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('logout') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('logout') }}">
                    <i class="material-icons">person</i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </div>
</div>