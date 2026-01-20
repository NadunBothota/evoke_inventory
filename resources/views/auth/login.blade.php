
@extends('layouts.app')

@section('content')
<div class="container login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Welcome Back!</h2>
            <p>Please sign in to continue.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ __('Log in') }}
                </button>
            </div>

            <div class="text-center">
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <p class="mt-3">
                    Don't have an account? <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
