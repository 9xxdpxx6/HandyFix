@extends('layouts.app')

@section('title', 'Вход')

@section('style')
    <style>
        .card-header {
            background-color: #f8f9fa; /* Replace with the desired background color */
        }

        .card-header img {
            display: block;
        }
    </style>

@endsection

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center h4 py-3">
                    <span>Золото Юга</span>
                </div>


                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="text-center mb-3">Авторизация</div>

                        <div class="form-group mb-3">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Логин" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Пароль">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Запомнить меня') }}
                            </label>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Войти') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
