@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Reset Password Basic - Pages')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/pages-auth.js'
])
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
    <div class="authentication-inner py-6">
      <div class="card p-md-7 p-1">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5 mb-1">
          <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            {{-- <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span> --}}
          </a>
        </div>
        <!-- /Logo -->

        <!-- Reset Password -->
        <div class="card-body">
          <h4 class="mb-1">Reset Password 🔒</h4>
          <p class="mb-5">Your new password must be different from previously used passwords</p>

          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form id="formAuthentication" class="mb-5" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="form-floating form-floating-outline mb-5 form-control-validation">
              <input type="email" id="email" class="form-control" name="email"
                placeholder="Enter your email" value="{{ old('email') }}" required autofocus />
              <label for="email">Email</label>
            </div>

            <!-- Password -->
            <div class="mb-5 form-password-toggle form-control-validation">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="password" id="password" class="form-control" name="password"
                    placeholder="Enter new password" required aria-describedby="password" />
                  <label for="password">New Password</label>
                </div>
                <span class="input-group-text cursor-pointer"><i
                    class="icon-base ri ri-eye-off-line icon-20px"></i></span>
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-5 form-password-toggle form-control-validation">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                    placeholder="Confirm password" required aria-describedby="password_confirmation" />
                  <label for="password_confirmation">Confirm Password</label>
                </div>
                <span class="input-group-text cursor-pointer"><i
                    class="icon-base ri ri-eye-off-line icon-20px"></i></span>
              </div>
            </div>

            <button class="btn btn-primary d-grid w-100 mb-5">Set new password</button>

            <div class="text-center">
              <a href="{{ url('auth/login-basic') }}" class="d-flex align-items-center justify-content-center">
                <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-20px me-1_5"></i>
                Back to login
              </a>
            </div>
          </form>
        </div>
      </div>
      <!-- /Reset Password -->

      <img alt="mask"
        src="{{ asset('assets/img/illustrations/auth-basic-reset-password-mask-'.$configData['theme'].'.png') }}"
        class="authentication-image d-none d-lg-block"
        data-app-light-img="illustrations/auth-basic-reset-password-mask-light.png"
        data-app-dark-img="illustrations/auth-basic-reset-password-mask-dark.png" />
    </div>
  </div>
</div>
@endsection
