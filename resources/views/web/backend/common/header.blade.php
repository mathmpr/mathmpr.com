<header>
    <nav>
        <ul>
            <li>
                <a class="for-logo" href="/{{ $lang }}/dashboard/nodes/">
                    <h1 class="logo">
                        <span>π</span>
                    </h1>
                </a>
            </li>
        </ul>
    </nav>
    <nav class="main">
        <ul>
            <li>
                <a class="for-logo" href="/{{ $lang }}/dashboard/nodes">
                    <h1 class="logo">
                        <span>π</span>
                    </h1>
                </a>
            </li>
        </ul>
        <ul>
            @guest
                <li>
                    <a href="/{{ $lang }}/">
                        <i class="fa-solid fa-arrow-left-long"></i>
                        {{ trans('backend.login.back') }}
                    </a>
                </li>
            @endguest
            @auth
                <li>
                    <a href="#">
                        <i class="fa-solid fa-chevron-right"></i>
                        {{ trans('backend.menu.nodes') }}
                    </a>
                    <ul>
                        <li>
                            <a href="/{{ $lang }}/dashboard/nodes/">{{ trans('backend.menu.manage') }}</a>
                        </li>
                        <li>
                            <a href="/{{ $lang }}/dashboard/nodes/create">{{ trans('backend.menu.add') }}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/{{ $lang }}/logout">
                        <i class="fa-solid fa-sign-out"></i>
                        {{ trans('backend.menu.logout') }}
                    </a>
                </li>
            @endauth
        </ul>
    </nav>
    <div class="backend-header-tools">
        <button id="backend-theme-toggle" class="backend-theme-toggle" type="button" aria-label="{{ trans('backend.theme.toggle') }}">
            <i class="fa-solid {{ isset($_COOKIE['skin']) && $_COOKIE['skin'] === 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
        </button>
        <label class="backend-language-switcher" for="backend-language">
            <select id="backend-language" aria-label="{{ trans('backend.editor.language') }}">
                @foreach(config('app.available_locales') as $locale)
                    <option value="{{ $locale }}" @selected($locale === $lang)>{{ strtoupper($locale) }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <span class="toggle"></span>
</header>
