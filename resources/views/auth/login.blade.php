@extends('layouts.auth')
@section('page-title')
    {{__('Login')}}
@endsection

@section('content')

    <div class="col-md-12 text-center mb-3">
        <a class="navbar-brand" href="#">
            <img src="{{asset(Storage::url('uploads/logo/')).'/logo.png'}}" class="navbar-brand-img big-logo"  alt="logo">
        </a>
    </div>

    <div class="card shadow zindex-100 mb-0">

        <div class="card-body px-md-5 py-5">
            <div class="mb-5">
                <h6 class="h3">{{ __('Login') }}</h6>
            </div>
            <span class="clearfix"></span>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-control-label">{{ __('Email') }}</label>
                    <div class="input-group input-group-merge">


                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-control-label">{{ __('Password') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <div class="mb-2">
                                <a href="{{ route('password.request') }}" class="small text-muted text-underline--dashed border-primary">{{ __('Forgot Password?') }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="input-group input-group-merge">

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                        <div class="input-group-append">
            <span class="input-group-text">
              <a href="#" data-toggle="password-text" data-target="#password">
                <i class="fas fa-eye"></i>
              </a>
            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                        <span class="btn-inner--text">{{__('Sign in')}}</span>

                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer px-md-5"><small>{{__('Not registered?')}}</small>
            <a href="{{ route('register') }}" class="small font-weight-bold">{{__('Create account')}}</a></div>
    </div>

@endsection
