<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ URL::to('/') }}/images/favicon.png">
    <title>@yield('title')</title>
    <style>
        #preload {
            position: fixed;
            width: 100%;
            height: 100%;
            display: flex;
            vertical-align: middle;
            justify-items: center;
            align-items: center;
            text-align: center;
            flex-direction: row;
            background-color: #fff;
            z-index: 20;
            transition: 0.4s opacity ease-out, 0.4s visibility ease-out;
            opacity: 1;
        }

        .dark #preload {
            background-color: #333;
        }

        #preload.bye {
            opacity: 0;
            visibility: hidden;
        }

        #preload > div {
            width: 100%;
            animation-iteration-count: infinite;
            animation: preload 1s infinite;
            position: relative;
            background-position: center;
            min-height: 65px;
            background-repeat: no-repeat;
            background-image: url("{{ URL::to('/') }}/images/preload.png");
        }

        .dark #preload > div {
            background-image: url("{{ URL::to('/') }}/images/dark-preload.png") !important;
        }

        @keyframes preload {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
    @yield('head')
</head>
