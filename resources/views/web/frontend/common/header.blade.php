@php
    $availableLocales = config('app.available_locales');
    $segments = request()->segments();
    $hasLocaleSegment = isset($segments[0]) && in_array($segments[0], $availableLocales, true);
    $languageUrls = [];

    foreach ($availableLocales as $locale) {
        $localizedSegments = $segments;

        if ($hasLocaleSegment) {
            $localizedSegments[0] = $locale;
        } else {
            array_unshift($localizedSegments, $locale);
        }

        if (isset($currentNode) && $currentNode instanceof \App\Models\Node) {
            $translatedSlug = $currentNode->getLangValue('slug', $locale);

            if ($translatedSlug) {
                $localizedSegments[1] = $translatedSlug;
            }
        }

        $languageUrls[$locale] = '/' . implode('/', $localizedSegments);

        if (request()->getQueryString()) {
            $languageUrls[$locale] .= '?' . request()->getQueryString();
        }
    }
@endphp

<div id="preload">
    <div></div>
</div>
<div id="header">
    <header class="container">
        <div class="row">
            <div class="d-inline-block">
                <a href="/{{ $lang }}">
                    <h1 class="logo">
                        <span>π</span>
                        <span>MATH<br>MPR</span>
                    </h1>
                </a>
            </div>
            <div class="col">
                <nav>
                    <ul>
                        <li><a href="/{{ $lang }}">{{ trans('frontend.nav.home') }}</a></li>
                        <li><a href="/{{ $lang }}/deep">{{ trans('frontend.nav.about') }}</a></li>
                        <li>
                            <i id="readable"
                               class="fa-solid {{ isset($_COOKIE['skin']) && $_COOKIE['skin'] == 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                            <select class="frontend-language-switcher" aria-label="{{ trans('frontend.nav.language') }}">
                                @foreach($availableLocales as $locale)
                                    <option value="{{ $languageUrls[$locale] }}" @selected($locale === $lang)>
                                        {{ strtoupper($locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </li>
                    </ul>
                </nav>
                <span></span>
            </div>
        </div>
    </header>
</div>
