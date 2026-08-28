@extends('web.frontend.dom')

@section('title') {{ trans('frontend.errors.unauthorized_title') }} | Mathmpr @endsection

@section('head')
    <link rel="stylesheet" href="/css/frontend.css">
    <link rel="stylesheet" href="/css/frontend/home.css">
@endsection

@section('main')
    <div class="container 404">
        <h1>{{ trans('frontend.errors.unauthorized_title') }}</h1>
    </div>
@overwrite
