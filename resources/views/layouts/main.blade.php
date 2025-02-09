<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HandyFix</title>
    <link rel="icon" href="{{ asset('adminpanel/dist/img/logo.ico') }}" type="image/x-icon">
    @vitereactrefresh
    @vite('resources/js/app.js')
{{--    @vite(['resources/css/app.scss', 'resources/js/app.js'])--}}

</head>
<body>
<div id="root">
    @yield('content')
</div>
</body>
</html>
