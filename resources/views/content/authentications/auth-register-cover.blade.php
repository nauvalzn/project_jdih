@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Daftar Cover - Halaman')

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
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{url('/')}}" class="auth-cover-brand d-flex align-items-center gap-2">
    <span class="app-brand-logo demo">@include('_partials.macros')</span>
    <span class="app-brand-text demo text-heading fw-semibold">{{config('variables.templateName')}}</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-12 pb-2">
      <img src="{{asset('assets/img/illustrations/auth-register-illustration-'.$configData['theme'].'.png')}}"
        class="auth-cover-illustration w-100" alt="auth-illustration"
        data-app-light-img="illustrations/auth-register-illustration-light.png"
        data-app-dark-img="illustrations/auth-register-illustration-dark.png" />
      <img src="{{asset('assets/img/illustrations/auth-cover-register-mask-'.$configData['theme'].'.png')}}"
        class="authentication-image" alt="mask" data-app-light-img="illustrations/auth-cover-register-mask-light.png"
        data-app-dark-img="illustrations/auth-cover-register-mask-dark.png" />
    </div>
    <!-- /Left Text -->

    <!-- Register -->
    <div
      class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-12 px-12 py-6">
      <div class="w-px-400 mx-auto pt-12 pt-lg-0">
        <h4 class="mb-1">Buat Akun</h4>
        <p class="mb-5">Buat akun dan nikmati kemudahan dalam mengelola aplikasi</p>

        <form id="formAuthentication" class="mb-5" action="{{url('/')}}" method="GET">
          <div class="form-floating form-floating-outline mb-5 form-control-validation">
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan nama pengguna"
              autofocus />
            <label for="username">Nama Pengguna</label>
          </div>
          <div class="form-floating form-floating-outline mb-5 form-control-validation">
            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" />
            <label for="email">Email</label>
          </div>
          <div class="mb-5 form-password-toggle form-control-validation">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="password" id="password" class="form-control" name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <label for="password">Kata Sandi</label>
              </div>
              <span class="input-group-text cursor-pointer"><i class="icon-base ri ri-eye-off-line"></i></span>
            </div>
          </div>
          <div class="mb-5 form-control-validation">
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
              <label class="form-check-label" for="terms-conditions">
                Saya setuju dengan
                <a href="javascript:void(0);">kebijakan privasi & syarat</a>
              </label>
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100">Daftar</button>
        </form>

        <p class="text-center mb-5">
          <span>Sudah punya akun?</span>
          <a href="{{url('auth/login-cover')}}">
            <span>Masuk di sini</span>
          </a>
        </p>

     

    
    <!-- /Register -->
  </div>
</div>
@endsection
