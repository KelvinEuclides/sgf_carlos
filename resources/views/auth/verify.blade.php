@extends('layouts.auth')

@section('content')
    <div class="col-md-12 text-center mb-3">
        <a class="navbar-brand" href="#">
            <img src="{{asset(Storage::url('uploads/logo/')).'/logo.png'}}" class="navbar-brand-img big-logo"  alt="logo">
        </a>
    </div>
<div class="card shadow zindex-100 mb-0">
  <div class="card-body px-md-5 py-5">

    <div class="mb-5">
      <h6 class="h3">{{ __('Verify Your Email Address') }}</h6>
    </div>
    <span class="clearfix"></span>

    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},

    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
            <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
          <span class="btn-inner--text">{{ __('click here to request another') }}</span>
          <span class="btn-inner--icon"><i class="fas fa-long-arrow-alt-right"></i></span>
        </button>
    </form>
  </div>

@endsection
