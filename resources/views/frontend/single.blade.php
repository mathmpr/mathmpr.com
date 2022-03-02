<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@section('head')
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/frontend.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/frontend/single.css">
@endsection

@include('frontend.common.head')

<template id="scripts">
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Lexend+Exa:wght@100;400;500;600;700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap'>
    <script src="{{  URL::to('/') }}/js/app.min.js"></script>
    <script src="{{  URL::to('/') }}/js/frontend/common/video.min.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/frontend/common/video-js.min.css">
</template>
<body class="{{ isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '' }} antialiased">
@include('frontend.common.header')
<main>
    <div class="container">
        <div class="row">
            <h1>Putting Mobile Backend as a Service into Practice</h1>
            <div>
                <span>3 Days Ago, By <a href="#">mathmpr</a></span>
            </div>
        </div>
    </div>
    @include('frontend.common.footer')
</main>
</body>
</html>
