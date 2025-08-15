<nav id="sidebar" class="sidebar js-sidebar">

    <div class="sidebar-content js-simplebar">

        <a class="sidebar-brand" href="{{ route('dashboard.index') }}">
            <span class="align-middle">MyApp</span>
        </a>

        <ul class="sidebar-nav">

            <li class="sidebar-item @yield('dashboard-active')">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Keuangan
            </li>

            <li class="sidebar-item @yield('accounts-active')">
                <a class="sidebar-link" href="{{ route('accounts.index') }}">
                    <i class="align-middle" data-feather="database"></i> <span class="align-middle">Akun</span>
                </a>
            </li>

            <li class="sidebar-item @yield('journals-active')">
                <a class="sidebar-link" href="{{ route('journals.index') }}">
                    <i class="align-middle" data-feather="edit-3"></i> <span class="align-middle">Jurnal</span>
                </a>
            </li>

            <li class="sidebar-header">
                Jadwal
            </li>

            <li class="sidebar-item @yield('schedules-active')">
                <a class="sidebar-link" href="{{ route('schedules.index') }}">
                    <i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Jadwal</span>
                </a>
            </li>

            <li class="sidebar-header">
                Pekerjaan
            </li>

            <li class="sidebar-item @yield('projects-active')">
                <a class="sidebar-link" href="{{ route('projects.index') }}">
                    <i class="align-middle" data-feather="package"></i> <span class="align-middle">Proyek</span>
                </a>
            </li>

            {{-- <li class="sidebar-item @yield('reports-active')">
                <a class="sidebar-link" href="">
                    <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Reports</span>
                </a>
            </li> --}}

        </ul>

    </div>

</nav>
