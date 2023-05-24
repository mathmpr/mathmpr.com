<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('web.backend.common.head')
<body class="{{ isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '' }} antialiased">
<template id="scripts">
    <link rel="stylesheet"
          href='https://fonts.googleapis.com/css2?family=Lexend+Exa:wght@100;400;500;600;700&display=swap'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
          rel="stylesheet">
    <script src="/js/backend/app.min.js"></script>
    @yield('scripts')
</template>
@yield('modals')
@include('web.backend.common.header')
<main>
    @yield('main')
    @include('web.backend.common.footer')
</main>
<script>window.lang = '{{$lang}}'</script>
<script>window.translations = JSON.parse('@json($translations)');</script>
</body>
</html>
