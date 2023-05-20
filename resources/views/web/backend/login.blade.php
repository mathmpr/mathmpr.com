@extends('web.backend.dom')

@section('title')
    Login
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
            <form action="/{{ $lang }}/login/" method="post">
                @csrf
                <h3>Sign in</h3>
                <div class="form-group">
                    <label for="username"></label>
                    <input class="form-control" id="username" name="username" placeholder="Username" type="text"
                           value="{{ $username ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="password"></label>
                    <input class="form-control" id="password" name="password" placeholder="Password" type="password"
                           value="{{ $password ?? '' }}">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>

@endsection
