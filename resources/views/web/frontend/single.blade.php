@extends('web.frontend.dom')

@section('title') Single @endsection

@section('head')
    <link rel="stylesheet" href="/css/frontend.css">
    <link rel="stylesheet" href="/css/frontend/single.css">
@endsection

@section('scripts')
    <script class="on-ready">
        $('.owl-carousel').owlCarousel({
            items: 2,
            margin: 20,
            loop: true
        });
    </script>
@endsection

@section('main')
    <div class="container">
        <article>
            <h1>Putting Mobile Backend as a Service into Practice</h1>
            <div class="after_head">
                <span>3 Days Ago, By <a href="#">mathmpr</a></span>
            </div>
            <div>
                <p>We are a digital agency with 20 plus years of collective experience in advert business like this
                    takes a greater effort than doing your own business. What's happened to me? He thought. It wasn't a
                    dream. His room, a proper human room although a little too small, lay peacefully between its four
                    familiar walls. A collection of textile samples lay spread out on the table.</p>
            </div>
            <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/1400/600?lz') }}" alt="picsum"
                 width="1400" height="600">
            <div>
                <p>We are a digital agency with 20 plus years of collective experience in advert business like this
                    takes a greater effort than doing your own business. What's happened to me? He thought. It wasn't a
                    dream. His room, a proper human room although a little too small, lay peacefully between its four
                    familiar walls. A collection of textile samples lay spread out on the table.</p>

                <p>Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of
                    an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur
                    hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm
                    towards the viewer.</p>

                <p>Gregor then turned to look out the window at the dull weather. Drops of rain could be heard hitting
                    the pane, which made him feel quite sad. "How about if I sleep a little bit longer and forget all
                    this nonsense", he thought.</p>
            </div>
            <cite>
                “First, we must ensure the validity of the approach. Second, we must confirm browser makers to add
                support for web fonts.”
            </cite>
            <div>
                <p>Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of
                    an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur
                    hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm
                    towards the viewer.</p>

                <p>Gregor then turned to look out the window at the dull weather. Drops of rain could be heard hitting
                    the pane, which made him feel quite sad. "How about if I sleep a little bit longer and forget all
                    this nonsense", he thought.</p>
            </div>
        </article>
        <div class="article-end">
            <div class="similar">
                <h3>Similar Nodes</h3>
                <div class="owl-carousel">
                    <div class="item">
                        <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?c') }}"
                             alt="picsum" width="800" height="800">
                    </div>
                    <div class="item">
                        <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?a') }}"
                             alt="picsum" width="800" height="800">
                    </div>
                    <div class="item">
                        <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?d') }}"
                             alt="picsum" width="800" height="800">
                    </div>
                </div>
            </div>
            <div class="author">
                <div class="image-wrapper">
                    <img src="/images/mathmpr.jpg" alt="Matheus Prado"/>
                </div>
                <div class="row about">
                    <div class="col-lg-6">
                        <h3>Matheus Prado Rodrigues</h3>
                        <p>
                            Matheus is an avid thinker, blogger, creative learner, frequent traveller and coffee hater.
                            She blogs, when not designing, at nicole.me
                        </p>
                        <ul>
                            <li>
                                <a href="https://www.linkedin.com/in/mathmpr/" target="_blank">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://github.com/mathmpr" target="_blank">
                                    <i class="fa-brands fa-github-alt"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://fb.com/mathmpr" target="_blank">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://instagram.com/mathmpr/" target="_blank">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h3>Outros nós do autor</h3>
                        <div class="row">
                            <div class="col-lg-4">
                                <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?e') }}"
                                     alt="picsum" width="800" height="800">
                            </div>
                            <div class="col-lg-4">
                                <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?f') }}"
                                     alt="picsum" width="800" height="800">
                            </div>
                            <div class="col-lg-4">
                                <img src="{{ \App\Utils\StaticImage::download('http://picsum.photos/800/800?j') }}"
                                     alt="picsum" width="800" height="800">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
