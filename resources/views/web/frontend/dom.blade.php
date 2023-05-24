<!DOCTYPE html>
<html lang="{{ $lang }}">
@include('web.frontend.common.head')
<body class="{{ isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '' }} antialiased">
<template id="scripts">
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Lexend+Exa:wght@100;400;500;600;700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap'>
    <script src="/js/frontend/app.min.js"></script>
    <script src="/js/frontend/common/video.min.js"></script>
    <link rel="stylesheet" href="/css/frontend/common/video-js.min.css">
    @yield('scripts')
</template>
@yield('modals')
@include('web.frontend.common.header')
<main>
    @yield('main')
    @include('web.frontend.common.footer')
</main>
<script>window.lang = '{{$lang}}'</script>
<script>window.translations = JSON.parse('@json($translations)');</script>
</body>
</html>
