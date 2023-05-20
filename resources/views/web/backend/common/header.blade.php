<header>
    <nav>
        <ul>
            <li>
                <a class="for-logo" href="/{{ $lang }}/dashboard/">
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
                <a class="for-logo" href="/{{ $lang }}/dashboard">
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
                        Posts
                    </a>
                    <ul>
                        <li>
                            <a href="/{{ $lang }}/dashboard/posts/">{{ trans('backend.menu.manage') }}</a>
                        </li>
                        <li>
                            <a href="/{{ $lang }}/dashboard/posts/create">{{ trans('backend.menu.add') }}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/{{ $lang }}/logout">
                        <i class="fa-solid fa-sign-out"></i>
                        Logout
                    </a>
                </li>
            @endauth
        </ul>
    </nav>
    <span class="toggle"></span>
</header>
