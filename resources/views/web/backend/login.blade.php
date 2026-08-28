@extends('web.backend.dom')

@section('title')
    {{ trans('backend.login.title') }}
@endsection

@section('head')
    <link rel="stylesheet" href="/css/backend.css">
    <link rel="stylesheet" href="/css/backend/login.css">
@endsection

@section('main')

    <div id="login">
        <div class="_container">
            @if(session()->has('wrong-credentials'))
                <div class="wrong">
                    {{ trans('backend.login.wrong-credentials') }}
                </div>
            @endif
            @if(session()->has('login-locked'))
                <div class="wrong">
                    {{ session('login-locked') }}
                </div>
            @endif
            <form action="/{{ $lang }}/login/" method="post">
                @csrf
                <h3>{{ trans('backend.login.sign_in') }}</h3>
                <div class="form-group">
                    <label for="username"></label>
                    <input class="form-control" id="username" name="username" placeholder="{{ trans('backend.login.email') }}" type="email"
                           value="{{ $username ?? '' }}" autocomplete="on">
                </div>
                <div class="form-group">
                    <label for="password"></label>
                    <input class="form-control" id="password" name="password" placeholder="{{ trans('backend.login.password') }}" type="password"
                           value="{{ $password ?? '' }}">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">{{ trans('backend.login.submit') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
