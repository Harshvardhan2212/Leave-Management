<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <!-- Small Screen Dropdown Toggle -->
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <!-- Large Screen Dropdown Toggle -->
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('UserProfile/' . (Auth::user()->image ?? 'userLogo.png')) ?? asset('UserProfile/userLogo.png')}}" class="avatar img-fluid rounded me-1" alt="John Snow" />
                    <span class="text-dark">{{Auth::user()->first_name ?? 'Jonh'}} {{Auth::user()->last_name ?? 'Snow'}}</span>
                </a>
                

                <!-- Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-end mt-3">
                    <div class="dropdown-header text-center">
                        <h6>{{Auth::user()->first_name ?? 'Jonh'}} {{Auth::user()->last_name ?? 'Snow'}}</h6>
                        <span>{{$employee->designation ?? 'Web Developer'}}</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route("profile")}}">
                        <i class="align-middle me-1" data-feather="user"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" id="logout">Log out</button>
                </div>
            </li>
        </ul>
    </div>
</nav>
<script src="{{asset('assets/js/navbar.js')}}"></script>
