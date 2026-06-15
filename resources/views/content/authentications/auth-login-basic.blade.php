@php
    $configData = Helper::appClasses();
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Login Basic - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/pages-auth.js'])
    <script>
        document.getElementById('refresh-captcha').addEventListener('click', function(){
        fetch("{{ route('captcha') }}")
        .then(response => response.text())
        .then(data => {
            document.querySelector('img[alt="captcha"]').src = data;
        });
    });
    </script>
@endsection

@section('content')
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-6">
                <!-- Login -->
                <div class="card p-md-7 p-1">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                                {{-- class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span> --}}
                        </a>
                    </div>
                    <!-- /Logo -->

<<<<<<< HEAD
        <div class="card-body mt-1">
          <h4 class="mb-1">Selamat Datang {{config('variables.templateName')}}</h4>
          <p class="mb-5">Silahkan masuk ke akun anda</p>

          <form id="formAuthentication" class="mb-5" action="{{url('/')}}" method="GET">
            <div class="form-floating form-floating-outline mb-5 form-control-validation">
              <input type="text" class="form-control" id="email" name="email-username"
                placeholder="Masukkan email atau nama pengguna Anda" autofocus />
              <label for="email">Email atau Nama Pengguna</label>
            </div>
            <div class="mb-5">
              <div class="form-password-toggle form-control-validation">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" />
                    <label for="password">Kata Sandi</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i
                      class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-5 d-flex justify-content-between mt-5">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remember-me" />
                <label class="form-check-label" for="remember-me">Ingat Saya</label>
              </div>
              <a href="{{url('auth/forgot-password-basic')}}" class="float-end mb-1 mt-2">
                <span>Lupa Kata Sandi</span>
=======
                    <div class="card-body mt-1">
                        <h4 class="mb-1">Selamat Datang ! 👋</h4>
                        <p class="mb-5">Silakan masuk ke akun Anda</p>

                        <form id="formAuthentication" class="mb-5" action="{{ route('auth.login') }}" method="POST">
                            @csrf

                            <!-- NIP -->
                            <div class="form-floating form-floating-outline mb-5 form-control-validation">
                                <input type="text" class="form-control" id="nip" name="nip"
                                    placeholder="Masukkan NIP Anda" autofocus />
                                <label for="nip">NIP</label>
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <div class="form-password-toggle form-control-validation">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                                    </div>
                                </div>
                            </div>

                            <!-- CAPTCHA ketik -->
                            <div class="mb-5">
                                <img src="{{ captcha_src() }}" alt="captcha" class="mb-3">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="text" class="form-control" id="captcha" name="captcha"
                                        placeholder="Ketik captcha" />
                                    <label for="captcha">Captcha</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary"
                                    id="refresh-captcha">Refresh</button>
                                @error('captcha')
                                    <div style="color:red">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            {{-- <div class="mb-5 d-flex justify-content-between mt-5">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remember" id="remember-me" />
                <label class="form-check-label" for="remember-me"> Ingat Saya </label>
              </div>
              <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1 mt-2">
                <span>Lupa Password?</span>
>>>>>>> 9da10fc43eb40aca2e0bfe464962ea65581f6528
              </a>
            </div> --}}

                            <!-- Submit -->
                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>

                        <!-- Register -->
                        {{-- <p class="text-center mb-5">
            <span>Baru di aplikasi kami?</span>
            <a href="{{ url('auth/register-basic') }}">
              <span>Buat akun baru mu!</span>
            </a>
          </p> --}}


                    </div>
                </div>
                <!-- /Login -->
                <!-- Background Mask -->
                <img alt="mask"
                    src="{{ asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['theme'] . '.png') }}"
                    class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
            </div>
<<<<<<< HEAD
            <div class="mb-5">
              <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
            </div>
          </form>

          <p class="text-center mb-5">
            <span>Baru di platform kami?</span>
            <a href="{{url('auth/register-basic')}}">
              <span>Buat akun</span>
            </a>
          </p>

         
      <!-- /Login -->
      <img alt="mask" src="{{asset('assets/img/illustrations/auth-basic-login-mask-'.$configData['theme'].'.png') }}"
        class="authentication-image d-none d-lg-block"
        data-app-light-img="illustrations/auth-basic-login-mask-light.png"
        data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
=======
        </div>
>>>>>>> 9da10fc43eb40aca2e0bfe464962ea65581f6528
    </div>



@endsection
