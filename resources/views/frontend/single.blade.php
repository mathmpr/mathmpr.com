<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@section('head')
    <link rel="stylesheet" href="css/frontend.css">
    <link rel="stylesheet" href="css/frontend/single.css">
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
    <div class="container">
        <div class="row">
            <article>
                <h1>Putting Mobile Backend as a Service into Practice</h1>
                <div class="after_head">
                    <span>3 Days Ago, By <a href="#">mathmpr</a></span>
                </div>
                <div>
                    <p>We are a digital agency with 20 plus years of collective experience in advert business like this takes a greater effort than doing your own business. What's happened to me? He thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls. A collection of textile samples lay spread out on the table.</p>
                </div>
                <img src="{{ \App\Utils\StaticImage::download('https://picsum.photos/1400/600?lz') }}" alt="picsum" width="1400" height="600">
                <div>
                    <p>We are a digital agency with 20 plus years of collective experience in advert business like this takes a greater effort than doing your own business. What's happened to me? He thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls. A collection of textile samples lay spread out on the table.</p>

                    <p>Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm towards the viewer.</p>

                    <p>Gregor then turned to look out the window at the dull weather. Drops of rain could be heard hitting the pane, which made him feel quite sad. "How about if I sleep a little bit longer and forget all this nonsense", he thought.</p>
                </div>
                <cite>
                    “First, we must ensure the validity of the approach. Second, we must confirm browser makers to add support for web fonts.”
                </cite>
                <div>
                    <p>Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm towards the viewer.</p>

                    <p>Gregor then turned to look out the window at the dull weather. Drops of rain could be heard hitting the pane, which made him feel quite sad. "How about if I sleep a little bit longer and forget all this nonsense", he thought.</p>
                </div>
            </article>
        </div>
    </div>
    @include('frontend.common.footer')
</main>
</body>
</html>
