<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<div class="container-fluid vh-100 bg-white">
    <div class="row h-100 justify-content-center">
        <div class="col-md-4 h-100 bg-primary d-none d-md-block"></div>
        <div class="col-md-8 h-100 d-flex align-items-center position-relative">
            <span class="register-link">Vous n'avez pas de compte ? <a href="{{route('register')}}">Enregistrez-vous</a></span>
            <div class="offset-md-2 col-md-8">
                <form method="POST" action="{{ route('login') }}" class="login-form text-center">
                    @csrf

                    <h3>{{ __('Sign in') }}</h3>

                    <div class="form-group row justify-content-center my-4">
                        <div class="col-md-8 col-sm-8">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   placeholder="{{__('E-mail Address')}}"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row justify-content-center my-4">
                        <div class="col-md-8 col-sm-8">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   placeholder="{{__('Password')}}"
                                   autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row justify-content-center my-4">
                        <div class="col-md-8 col-sm-8 row justify-content-between">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
