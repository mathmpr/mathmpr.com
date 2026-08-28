@extends('web.backend.dom')

@section('title') {{ trans('backend.dashboard.title') }} @endsection

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/dashboard.css">
@endsection

@section('main')

    <div class="container">
        <div class="dashboard-placeholder"></div>
    </div>

@endsection
