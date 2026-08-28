@extends('web.frontend.dom')

@section('title') {{ $node->title }} | {{ trans('frontend.site.title') }} @endsection

@section('head')
    @php
        $siteUrl = request()->getSchemeAndHttpHost();

        if (request()->getHost() === 'nginx') {
            $siteUrl = rtrim(config('app.url'), '/');
        }

        $canonicalUrl = $siteUrl . '/' . $lang . '/' . $node->slug;
        $description = $node->description ?: \Illuminate\Support\Str::limit(trim(strip_tags($contentHtml)), 160, '');
        $ogLocale = ['pt' => 'pt_BR', 'en' => 'en_US', 'es' => 'es_ES'][$lang] ?? $lang;
        $image = $node->cover_image ?: '/images/mathmpr.jpg';
        $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : $siteUrl . $image;
        $availableLocales = config('app.available_locales');
        $alternateUrls = [];

        foreach ($availableLocales as $locale) {
            $translatedSlug = $node->getLangValue('slug', $locale);

            if ($translatedSlug) {
                $alternateUrls[$locale] = $siteUrl . '/' . $locale . '/' . $translatedSlug;
            }
        }

        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonicalUrl,
            ],
            'headline' => $node->title,
            'description' => $description,
            'image' => [$imageUrl],
            'datePublished' => optional($node->created_at)->toIso8601String(),
            'dateModified' => optional($node->updated_at)->toIso8601String(),
            'inLanguage' => $lang,
            'author' => [
                '@type' => 'Person',
                'name' => trans('frontend.single.author_name'),
                'url' => $siteUrl,
            ],
            'publisher' => [
                '@type' => 'Person',
                'name' => trans('frontend.single.author_name'),
            ],
        ];
    @endphp
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ trans('frontend.single.author_name') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="preload" as="image" href="{{ $imageUrl }}" fetchpriority="high">
    @foreach($alternateUrls as $locale => $url)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
    @endforeach
    @if(isset($alternateUrls['pt']))
        <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls['pt'] }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ trans('frontend.site.title') }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:title" content="{{ $node->title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:image:alt" content="{{ $node->title }}">
    <meta property="article:published_time" content="{{ optional($node->created_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ optional($node->updated_at)->toIso8601String() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $node->title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
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
            <h1>{{ $node->title }}</h1>
            <div class="after_head">
                <span>{!! trans('frontend.single.meta', ['date' => optional($node->created_at)->format('d/m/Y'), 'author' => '<a href="#">mathmpr</a>']) !!}</span>
            </div>

            @if($node->cover_image)
                <img src="{{ $node->cover_image }}" alt="{{ $node->title }}" width="1400" height="600" decoding="async" loading="eager" fetchpriority="high">
            @endif

            <div class="post-content">
                {!! $contentHtml !!}
            </div>
        </article>
        <div class="article-end">
            @if($similar->isNotEmpty())
                <div class="similar">
                    <h3>{{ trans('frontend.single.similar_nodes') }}</h3>
                    <div class="owl-carousel">
                        @foreach($similar as $similarNode)
                            <div class="item">
                                <a href="/{{ $lang }}/{{ $similarNode->slug }}">
                                    <img src="{{ $similarNode->cover_image }}" alt="{{ $similarNode->title }}" width="800" height="800" decoding="async" loading="lazy">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="author">
                <div class="image-wrapper">
                    <img src="/images/mathmpr.jpg" alt="Matheus Prado" decoding="async" loading="lazy"/>
                </div>
                <div class="row about">
                    <div class="col-lg-6">
                        <h3>{{ trans('frontend.single.author_name') }}</h3>
                        <p>
                            {{ trans('frontend.single.author_bio') }}
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
                                <a href="https://instagram.com/mathmpr/" target="_blank">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @if($similar->isNotEmpty())
                        <div class="col-lg-6">
                            <h3>{{ trans('frontend.single.author_nodes') }}</h3>
                            <div class="row">
                                @foreach($similar->take(3) as $similarNode)
                                    <div class="col-lg-4">
                                        <a href="/{{ $lang }}/{{ $similarNode->slug }}">
                                            <img src="{{ $similarNode->cover_image }}" alt="{{ $similarNode->title }}" width="800" height="800" decoding="async" loading="lazy">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
