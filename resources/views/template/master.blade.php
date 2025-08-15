@include('template.head')
@yield('style')

<body>
    <div class="wrapper">
        @include('template.sidebar')
        <div class="main">
            @include('template.topbar')
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>
            @include('template.footer')
        </div>
    </div>
    @include('template.js')
    @yield('script')
</body>
</html>
