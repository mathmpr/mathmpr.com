<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@section('head')
    <link rel="stylesheet" href="css/frontend.css">
    <link rel="stylesheet" href="css/frontend/home.css">
@endsection
@include('frontend.common.head')
<body class="{{ isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '' }} antialiased">
<template id="scripts">
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Lexend+Exa:wght@100;400;500;600;700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap'>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap'>
    <script src="js/app.min.js"></script>
    <script src="js/frontend/common/video.min.js"></script>
    <link rel="stylesheet" href="css/frontend/common/video-js.min.css">
</template>
@include('frontend.common.header')
<main>
    <div class="container home">
        <div class="row">
            <div data-src="files/videos/let-it-happen.mp4" class="col-lg-6 prefer">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?ad') }}" alt="picsum" width="800" height="800">
                <a href="any">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                    <ul>
                        <li>php</li>
                        <li>laravel</li>
                        <li>orm</li>
                    </ul>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?a') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?b') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?c') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?d') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?e') }}" alt="picsum" width="800" height="800">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?f') }}" alt="picsum" width="800" height="800">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?g') }}" alt="picsum" width="800" height="800">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?h') }}" alt="picsum" width="800" height="800">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>


            <div class="col-lg-6 squares">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?i') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?j') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?k') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?l') }}" alt="picsum" width="800" height="800">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 prefer">
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/800/800?m') }}" alt="picsum" width="800" height="800">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>

        </div>
    </div>
    @include('frontend.common.footer')
</main>
</body>
</html>
