@extends('layouts/layoutMaster')

@section('title', 'Account settings - Account')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i
                class="icon-base ri ri-group-line icon-sm me-2"></i>Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-security') }}"><i
                class="icon-base ri ri-lock-line icon-sm me-2"></i>Security</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-billing') }}"><i
                class="icon-base ri ri-bookmark-line icon-sm me-2"></i>Billing & Plans</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-notifications') }}"><i
                class="icon-base ri ri-notification-4-line icon-sm me-2"></i>Notifications</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-connections') }}"><i
                class="icon-base ri ri-link-m icon-sm me-2"></i>Connections</a>
          </li>
        </ul>
      </div>
      <div class="card mb-6">
        <!-- Account -->
        <div class="card-body">
    <div class="d-flex align-items-start align-items-sm-center gap-6">
        {{-- Avatar --}}
        <img src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : asset('assets/img/avatars/1.png') }}" 
             alt="user-avatar" class="d-block w-px-100 h-px-100 rounded-4" id="uploadedAvatar" />
        
        {{-- Upload Button --}}
        <div class="button-wrapper">
            <form action="{{ route('account.uploadAvatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload new photo</span>
                    <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                    <input type="file" id="upload" name="avatar" class="account-file-input" hidden accept="image/png, image/jpeg" onchange="this.form.submit()"/>
                </label>
            </form>
            <button type="button" class="btn btn-outline-danger account-image-reset mb-4" onclick="resetAvatar()">
                <i class="icon-base ri ri-refresh-line d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
            </button>
            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
        </div>
    </div>
</div>

<div class="card-body pt-0">
    <form id="formAccountSettings" action="{{ route('account.update') }}" method="POST">
        @csrf
        <div class="row mt-1 g-5">
            {{-- Nama --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" autofocus />
                    <label for="name">Nama</label>
                </div>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" />
                    <label for="email">E-mail</label>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="col-md-6">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="text" id="kontak" name="kontak" class="form-control" value="{{ auth()->user()->kontak ?? '' }}" />
                        <label for="kontak">Nomor Telepon</label>
                    </div>
                    <span class="input-group-text">+62</span>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ auth()->user()->alamat ?? '' }}" />
                    <label for="alamat">Alamat</label>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Save changes</button>
            <button type="reset" class="btn btn-outline-secondary">Reset</button>
        </div>
    </form>
</div>

<script>
    function resetAvatar() {
        // bisa pakai Ajax atau form untuk reset avatar ke default
        document.getElementById('uploadedAvatar').src = "{{ asset('assets/img/avatars/1.png') }}";
        // bisa tambahin request ke route reset avatar kalau mau
    }
</script>
        <!-- /Account -->
      </div>
    </div>
  </div>
@endsection
