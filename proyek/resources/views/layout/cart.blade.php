<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @stack("style")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Animate.css -->
    {{-- <link rel="stylesheet" href="{{ asset('coba/css/animate.css') }}"> --}}
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="{{ asset('coba/css/icomoon.css') }}">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="{{ asset('coba/css/bootstrap.css') }}">
    <!-- Flexslider  -->
    <link rel="stylesheet" href="{{ asset('coba/css/flexslider.css') }}">
    <!-- Theme style  -->
    <link rel="stylesheet" href="{{ asset('css/category.css') }}">
    <!-- Modernizr JS -->
    <script src="{{ asset('coba/js/modernizr-2.6.2.min.js') }}"></script>

    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key={{env('MIDTRANS_CLIENT_KEY')}}></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
</head>

<body>
    <nav class="fh5co-nav" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center logo-wrap">
                    <div id="fh5co-logo"><a href="{{ route('home') }}">ChicOut<span>.</span></a></div>
                </div>
                <div class="col-xs-12 text-center menu-1 menu-wrap">
                    <ul>
                        @yield('menu')
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer id="fh5co-footer" role="contentinfo" class="fh5co-section">
        <div class="container">
            <div class="row copyright">
                <div class="col-md-12 text-center">
                    <p>
                        <small class="block">&copy; 2024. All Rights Reserved.</small>
                    </p>
                    <p>
                    <ul class="fh5co-social-icons">
                        <li><a href="#"><i class="icon-twitter2"></i></a></li>
                        <li><a href="#"><i class="icon-facebook2"></i></a></li>
                        <li><a href="#"><i class="icon-linkedin2"></i></a></li>
                        <li><a href="#"><i class="icon-dribbble2"></i></a></li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <div class="gototop js-top">
        <a href="#" class="js-gotop"><i class="icon-arrow-up22"></i></a>
    </div>
</body>

</html>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="{{ asset('coba/js/jquery.min.js') }}"></script>
<!-- jQuery Easing -->
<script src="{{ asset('coba/js/jquery.easing.1.3.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('coba/js/bootstrap.min.js') }}"></script>
<!-- Waypoints -->
<script src="{{ asset('coba/js/jquery.waypoints.min.js') }}"></script>
<!-- Waypoints -->
<script src="{{ asset('coba/js/jquery.stellar.min.js') }}"></script>
<!-- Flexslider -->
<script src="{{ asset('coba/js/jquery.flexslider-min.js') }}"></script>
<!-- Main -->
<script src="{{ asset('coba/js/main.js') }}"></script>


@stack('script')