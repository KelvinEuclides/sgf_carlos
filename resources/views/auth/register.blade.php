@extends('layouts.auth')
@section('page-title')
    {{__('Register')}}
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
          <h6 class="h3">{{__('Create account')}}</h6>
        </div>
        <span class="clearfix"></span>

        <form method="POST" action="{{ route('register') }}">
            @csrf
        <div class="form-group">
            <label class="form-control-label">{{ __('Name') }}</label>
            <div class="input-group input-group-merge">


              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          </div>
        <div class="form-group">
            <label class="form-control-label">{{__('Email')}}</label>
            <div class="input-group input-group-merge">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          </div>
          <div class="form-group mb-4">
            <label class="form-control-label">{{ __('Password') }}</label>
            <div class="input-group input-group-merge">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

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
          </div>
          <div class="form-group">
            <label class="form-control-label">{{__('Confirm password')}}</label>
            <div class="input-group input-group-merge">

              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
              <span class="btn-inner--text">{{__('Create my account')}}</span>
            </button>
        </div>
        </form>
      </div>
      <div class="card-footer px-md-5"><small>{{__('Already have an acocunt?')}}</small>
        <a href="{{ route('login') }}" class="small font-weight-bold">{{__('Sign in')}}</a></div>
    </div>

@endsection
