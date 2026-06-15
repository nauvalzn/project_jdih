@php
    $configData = Helper::appClasses();
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Daftar Akun - Halaman')

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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<<<<<<< HEAD
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
    <div class="authentication-inner py-6">
      <!-- Kartu Daftar -->
      <div class="card p-md-7 p-1">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            <span class="app-brand-text demo text-heading fw-semibold">{{config('variables.templateName')}}</span>
          </a>
        </div>
        <!-- /Logo -->
        <div class="card-body mt-1">
          <h4 class="mb-1">Buat Akun</h4>
          <p class="mb-5">Buat akun dan nikmati kemudahan dalam mengelola aplikasi

</p>

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
                <span class="input-group-text cursor-pointer"><i
                    class="icon-base ri ri-eye-off-line icon-20px"></i></span>
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
            <button class="btn btn-primary d-grid w-100 mb-5">Daftar</button>
          </form>

          <p class="text-center mb-5">
            <span>Sudah punya akun?</span>
            <a href="{{url('auth/login-basic')}}">
              <span>Masuk di sini</span>
            </a>
          </p>

          <div class="divider my-5">
            <div class="divider-text">atau</div>
=======
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-6">
                <!-- Register Card -->
                <div class="card p-md-7 p-1">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                                {{--<span> class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span> --}}
                        </a>
                    </div>
                    <!-- /Logo -->
                    <div class="card-body mt-1">
                        <h4 class="mb-1">Buat akun baru anda 🚀</h4>
                        <p class="mb-5"></p>

                        <form id="formAuthentication" class="mb-5" action="{{ route('auth.register-basic') }}"
                            method="POST">
                            @csrf

                            <!-- Username -->
                            <div class="form-floating form-floating-outline mb-3 form-control-validation">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="username" name="name" value="{{ old('name') }}"
                                    placeholder="Masukkan username Anda" autofocus />
                                <label for="username">Username</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="form-floating form-floating-outline mb-3 form-control-validation">
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                                    name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP Anda" />
                                <label for="nip">NIP</label>
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Email -->
                            <div class="form-floating form-floating-outline mb-3 form-control-validation">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email Anda" />
                                <label for="email">Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3 form-password-toggle form-control-validation">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline flex-grow-1">
                                        <input type="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="• • • • • • • • • • •" aria-describedby="password" />
                                        <label for="password">Password</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3 form-password-toggle form-control-validation">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline flex-grow-1">
                                        <input type="password" id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" placeholder="• • • • • • • • • • • •" />
                                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="mb-3 form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                    id="terms-conditions" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="terms-conditions">
                                    Saya setuju dengan <a href="javascript:void(0);">kebijakan & ketentuan privasi</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="mb-3 form-floating form-floating-outline">
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role">
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <label for="role">Role</label>
                                @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CAPTCHA ketik -->
                            <div class="mb-5">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="text" class="form-control" id="captcha" name="captcha"
                                        placeholder="Ketik captcha" />
                                    <label for="captcha">Captcha</label>
                                </div>
                                <img src="{{ captcha_src() }}" alt="captcha" class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    id="refresh-captcha">Refresh</button>
                                @error('captcha')
                                    <div style="color:red">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-5">Daftar</button>
                        </form>

                        <p class="text-center mb-5">
                            <span>Sudah punya akun?</span>
                            <a href="{{ url('auth/login-basic') }}"><span>Masuk</span></a>
                        </p>

                        {{-- <div class="divider my-5">
            <div class="divider-text">or</div>
>>>>>>> 9da10fc43eb40aca2e0bfe464962ea65581f6528
          </div>

          <div class="d-flex justify-content-center gap-2">
            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook">
              <i class="icon-base ri ri-facebook-fill icon-18px"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter">
              <i class="icon-base ri ri-twitter-fill icon-18px"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github">
              <i class="icon-base ri ri-github-fill icon-18px"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
              <i class="icon-base ri ri-google-fill icon-18px"></i>
            </a>
          </div> --}}
                    </div>
                </div>
                <!-- Register Card -->
                <img alt="mask"
                    src="{{ asset('assets/img/illustrations/auth-basic-register-mask-' . $configData['theme'] . '.png') }}"
                    class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-register-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-register-mask-dark.png" />
            </div>
        </div>
<<<<<<< HEAD
      </div>
      <!-- Kartu Daftar -->
      <img alt="mask" src="{{asset('assets/img/illustrations/auth-basic-register-mask-'.$configData['theme'].'.png')}}"
        class="authentication-image d-none d-lg-block"
        data-app-light-img="illustrations/auth-basic-register-mask-light.png"
        data-app-dark-img="illustrations/auth-basic-register-mask-dark.png" />
=======
>>>>>>> 9da10fc43eb40aca2e0bfe464962ea65581f6528
    </div>
@endsection
