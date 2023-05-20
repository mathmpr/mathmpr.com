@extends('web.backend.dom')

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/dashboard.css">
@endsection

@section('scripts')

    <script class="on-ready">
        let c = new Cropper({
            target: '#to-crop',
            image: '{{ asset("storage/captura-de-tela-de-2023-05-08-17-07-41.webp") }}',
            frame: '300x900',
            maxFrameWidth: 450
        });
    </script>

@endsection

@section('modals')
    @include('web/backend/common/modals/media-library')
@endsection

@section('main')

    @include('web/backend/common/templates')

    <div class="container">
        <div id="mathmpr-editor">
            <ul class="module-selector">
                <li data-module="code">
                    <i class="fa-solid fa-code"></i>
                </li>
                <li data-module="media">
                    <i class="fa-solid fa-photo-film"></i>
                </li>
                <li data-module="text">
                    <i class="fa-solid fa-font"></i>
                </li>
            </ul>
            <div class="overflow"></div>
        </div>
    </div>

@endsection

