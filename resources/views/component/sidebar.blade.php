{{-- sidebar --}}
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
            <span class="align-middle">Leave Management</span>
        </a>
        @if (Auth::user()->role)
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>

                <li class="sidebar-item" id="dashboard">
                    <a class="sidebar-link" href="/dashboard">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item" id="leave-approval">
                    <a class="sidebar-link" href="/leaveApproval">
                        <i class="bi bi-person-check align-middle fs-4"></i> <span class="align-middle">Leave
                            Approval</span>
                    </a>
                </li>

                <li class="sidebar-item" id="employee">
                    <a class="sidebar-link" href="/employee">
                        <i class="bi bi-people align-middle fs-4"></i>
                        <span class="align-middle">Employee</span>
                    </a>
                </li>
            </ul>
        @else
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>
                <li class="sidebar-item" id="dashboard">
                    <a class="sidebar-link" href="{{ route('dashboard') }}">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>


                <li class="sidebar-item" id="leaves">
                    <a class="sidebar-link" href="/leave-page">
                        <i class="bi bi-calendar-date align-middle fs-4"></i> <span class="align-middle">Leaves</span>
                    </a>
                </li>

                <li class="sidebar-item" id="salary">
                    <a class="sidebar-link" href="{{route('salary-page')}}">
                        <i class="bi bi-bank align-middle fs-4"></i>
                        <span class="align-middle">Salary</span>
                    </a>
                </li>
            </ul>
        @endif



    </div>
</nav>
{{-- sidebar end --}}
