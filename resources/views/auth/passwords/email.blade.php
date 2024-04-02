@extends('layouts.auth')
@section('page-title')
    {{__('Reset Password')}}
@endsection
@section('content')
    <div class="col-md-12 text-center mb-3">
        <a class="navbar-brand" href="#">
            <img src="{{asset(Storage::url('uploads/logo/')).'/logo.png'}}" class="navbar-brand-img big-logo"  alt="logo">
        </a>
    </div>
<div class="card shadow zindex-100 mb-0">
  <div class="card-body px-md-5 py-5">

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="mb-5">
      <h6 class="h3">{{ __('Reset Password') }}</h6>
    </div>
    <span class="clearfix"></span>

    <form method="POST" action="{{ route('password.email') }}">
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
      <div class="mt-4">
        <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
          <span class="btn-inner--text">{{ __('Send Password Reset Link') }}</span>
        </button>
    </div>
    </form>
  </div>
  <div class="card-footer px-md-5"><small>{{__('Back to ')}}</small>
    <a href="{{ route('login') }}" class="small font-weight-bold">{{__('Login')}}</a></div>
</div>

@endsection
