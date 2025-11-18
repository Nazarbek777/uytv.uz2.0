<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('template/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/colors.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="blue-skin">
    <div id="app"></div>

    <script src="{{ asset('template/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/rangeslider.js') }}"></script>
    <script src="{{ asset('template/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/slick.js') }}"></script>
    <script src="{{ asset('template/assets/js/slider-bg.js') }}"></script>
    <script src="{{ asset('template/assets/js/lightbox.js') }}"></script>
    <script src="{{ asset('template/assets/js/imagesloaded.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom.js') }}"></script>
</body>
</html>
