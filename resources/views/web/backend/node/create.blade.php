@extends('web.backend.dom')

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/dashboard.css">
    <style>
        .form {
            width: 100%;
            height: calc(100vh - 60px);
            border-radius: 5px;
            padding: 15px 20px;
            background: #fff;
            margin-top: 30px;
            overflow: auto;
        }
    </style>
@endsection

@section('scripts')

    <script class="on-ready">
        //window.setModuleOptions('#mathmpr-editor', 'media', {
        //    max: 2
        //});
    </script>

@endsection

@section('modals')
    @include('web/backend/common/modals/media-library')
    @include('web/backend/common/modals/cropper')
@endsection

@section('main')

    @include('web/backend/common/templates')

    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="form">
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input class="form-control" id="title" name="title" placeholder="Title">
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div id="mathmpr-editor" data-id="{{$id}}" data-url="{{ route('api.nodes.index', ['lang' => $lang])  }}">
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
        </div>
    </div>

@endsection

