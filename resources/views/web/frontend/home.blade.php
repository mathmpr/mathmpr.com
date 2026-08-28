@extends('web.frontend.dom')

@section('title') {{ $pageTitle ?? trans('frontend.site.title') }} @endsection

@section('head')
    @php
        $siteUrl = request()->getSchemeAndHttpHost();

        if (request()->getHost() === 'nginx') {
            $siteUrl = rtrim(config('app.url'), '/');
        }

        $isDeepPage = request()->segment(2) === 'deep';
        $pagePath = $isDeepPage ? '/deep' : '';
        $canonicalUrl = $siteUrl . '/' . $lang . $pagePath;
        $description = $isDeepPage ? trans('frontend.site.description') : trans('frontend.home.intro_description');
        $title = $pageTitle ?? trans('frontend.site.title');
        $ogLocale = ['pt' => 'pt_BR', 'en' => 'en_US', 'es' => 'es_ES'][$lang] ?? $lang;
        $mainImage = optional($posts->first())->cover_image ?: '/images/mathmpr.jpg';
        $defaultImage = \Illuminate\Support\Str::startsWith($mainImage, ['http://', 'https://'])
            ? $mainImage
            : $siteUrl . $mainImage;
        $availableLocales = config('app.available_locales');
        $structuredPosts = $posts->map(function ($post, $index) use ($siteUrl, $lang) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => $siteUrl . '/' . $lang . '/' . $post->slug,
                'name' => $post->title,
            ];
        })->values();
        $structuredData = $isDeepPage ? [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => $siteUrl . '/#website',
                    'url' => $siteUrl,
                    'name' => trans('frontend.site.title'),
                    'description' => $description,
                    'inLanguage' => $lang,
                ],
                [
                    '@type' => 'Blog',
                    '@id' => $canonicalUrl . '#blog',
                    'url' => $canonicalUrl,
                    'name' => trans('frontend.site.title'),
                    'description' => $description,
                    'inLanguage' => $lang,
                    'isPartOf' => ['@id' => $siteUrl . '/#website'],
                    'blogPost' => $structuredPosts,
                ],
            ],
        ] : [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'url' => $canonicalUrl,
            'name' => $title,
            'description' => $description,
            'inLanguage' => $lang,
            'mainEntity' => [
                '@type' => 'Person',
                'name' => trans('frontend.single.author_name'),
                'email' => 'mailto:matheusprador@gmail.com',
                'jobTitle' => trans('frontend.home.job_title'),
                'url' => $siteUrl . '/' . $lang,
            ],
        ];
    @endphp
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ trans('frontend.single.author_name') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="preload" as="image" href="{{ $defaultImage }}" fetchpriority="high">
    @foreach($availableLocales as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $siteUrl }}/{{ $locale }}{{ $pagePath }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $siteUrl }}/pt{{ $pagePath }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ trans('frontend.site.title') }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $defaultImage }}">
    <meta property="og:image:alt" content="{{ trans('frontend.site.title') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $defaultImage }}">
    <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <link rel="stylesheet" href="/css/frontend.css">
    <link rel="stylesheet" href="/css/frontend/home.css">
@endsection

@section('main')
    @if($posts->isNotEmpty())
        @php
            $post = fn (int $index) => $posts->get($index);
        @endphp

        <div class="container home">
            <div class="row">
                @if($post(0))
                    @include('web.frontend.partials.home-node-card', ['post' => $post(0), 'class' => 'col-lg-6 prefer', 'eager' => true])
                @endif

                @if($posts->slice(1, 4)->isNotEmpty())
                    <div class="col-lg-6">
                        <div class="row">
                            @foreach($posts->slice(1, 4) as $node)
                                @include('web.frontend.partials.home-node-card', ['post' => $node, 'class' => 'col-lg-6'])
                            @endforeach
                        </div>
                    </div>
                @endif

                @foreach($posts->slice(5, 4) as $node)
                    @include('web.frontend.partials.home-node-card', ['post' => $node, 'class' => 'col-lg-3'])
                @endforeach

                @if($posts->slice(9, 4)->isNotEmpty())
                    <div class="col-lg-6 squares">
                        <div class="row">
                            @foreach($posts->slice(9, 4) as $node)
                                @include('web.frontend.partials.home-node-card', ['post' => $node, 'class' => 'col-lg-6'])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($post(13))
                    @include('web.frontend.partials.home-node-card', ['post' => $post(13), 'class' => 'col-lg-6 prefer'])
                @endif
            </div>
        </div>
    @else
        <div class="container home-intro">
            <div class="home-intro__content">
                <p class="home-intro__eyebrow">{{ trans('frontend.home.eyebrow') }}</p>
                <h1>{{ trans('frontend.home.intro_title') }}</h1>
                <p>{{ trans('frontend.home.intro_software') }}</p>
                <p>{{ trans('frontend.home.intro_experience') }}</p>
                <p>{{ trans('frontend.home.intro_interest') }}</p>
                <p>{{ trans('frontend.home.intro_education') }}</p>
                <p class="home-intro__contact">
                    <a href="mailto:matheusprador@gmail.com">{{ trans('frontend.home.contact') }}</a>
                </p>
            </div>
        </div>
    @endif
@overwrite
