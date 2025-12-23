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
                            <!-- Submit -->
                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>
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
        </div>
    </div>
@endsection