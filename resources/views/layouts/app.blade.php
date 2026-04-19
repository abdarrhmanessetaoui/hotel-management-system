<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    @if(!isset($AdminView))
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet"/>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chatbot.css') }}" rel="stylesheet">
    <!-- Branding -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-brand.png') }}">
    <title>Hotelia</title>

</head>
<body>
    <!-- Spinner loading (Global) -->
    @include('components.spinner')

    <!-- Admin Panel -->
    @if(isset($AdminView))
        <!-- Mobile Sidebar Toggle & Overlay (Injected server-side for reliability) -->
        <button type="button" class="mobile-toggle-btn shadow"><i class="fa fa-bars"></i></button>
        <div class="sidebar-overlay"></div>

        <div class="container-fluid p-0 overflow-hidden">
            <div class="row g-0 admin-layout-row">
                <div class="admin-sidebar-col">
                    @if(Auth::check() && Auth::user()->isSuperAdmin())
                        @include('superadmin.sidebar')
                    @else
                        @include('admin.sidebar')
                    @endif
                </div>
                <div class="col admin-content-col py-3 px-3 px-md-4 overflow-auto" style="height: 100vh;">
                    @yield('content')
                </div>
            </div>
        </div>
    @endif


    <!-- Header & Hero (Full Width) -->
    @yield('header')

    @if(!isset($AdminView))
        <!-- Restricted Width Content Area -->
        <div class="container-xxl bg-white p-0">
            <!-- Content -->
            @yield('content')
            <!-- Footer -->
            @yield('footer')
        </div>
    @endif

    <!-- Global Chatbot FAB: visible on every page (public + admin + superadmin) -->
    @include('components.chatbot')

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    @if(!isset($AdminView))
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    @endif
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    {{-- Section-level pushed scripts (e.g. owl carousel init, date pickers) --}}
    @stack('scripts')
</body>
</html>
