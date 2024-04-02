@extends('layouts.auth')
@section('page-title')
    {{__('Reset Password')}}
@endsection
@section('content')

<div class="card shadow zindex-100 mb-0">
  <div class="card-body px-md-5 py-5">

    <div class="mb-5">
      <h6 class="h3">{{ __('Confirm Password') }}</h6>

      <span>{{ __('Please confirm your password before continuing.') }}</span>

    </div>
    <span class="clearfix"></span>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group mb-4">
            <label class="form-control-label">{{ __('Password') }}</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
              </div>
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
          </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
          <span class="btn-inner--text">{{ __('Confirm Password') }}</span>
          <span class="btn-inner--icon"><i class="fas fa-long-arrow-alt-right"></i></span>
        </button>
    </div>
    </form>
  </div>
  <div class="card-footer px-md-5"><small>Not registered?</small>
    @if (Route::has('password.request'))
        <a class="small font-weight-bold" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
</div>

@endsection
