@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Account settings - Security')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-security.js', 'resources/assets/js/modal-enable-otp.js'])
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('pages/account-settings-account') }}"><i
                class="icon-base ri ri-group-line icon-sm me-2"></i> Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="icon-base ri ri-lock-line icon-sm me-2"></i>
              Security</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-billing') }}"><i
                class="icon-base ri ri-bookmark-line icon-sm me-2"></i> Billing & Plans</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-notifications') }}"><i
                class="icon-base ri ri-notification-4-line icon-sm me-2"></i> Notifications</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="{{ url('pages/account-settings-connections') }}"><i
                class="icon-base ri ri-link-m icon-sm me-2"></i> Connections</a>
          </li>
        </ul>
      </div>
      <!-- Change Password -->
      <div class="card mb-6">
        <h5 class="card-header">Change Password</h5>
        <div class="card-body pt-1">
          @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
          <form id="formAccountSettings" method="POST" action="{{ route('password.updatesss') }}">
          @csrf
            <div class="row">
              <div class="mb-5 col-md-6 form-password-toggle form-control-validation">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="password" name="currentPassword" id="currentPassword"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    <label for="currentPassword">Current Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i
                      class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                </div>
              </div>
            </div>
            <div class="row g-5 mb-6">
              <div class="col-md-6 form-password-toggle form-control-validation">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="password" id="newPassword" name="newPassword"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    <label for="newPassword">New Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i
                      class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                </div>
              </div>
              <div class="col-md-6 form-password-toggle form-control-validation">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    <label for="confirmPassword">Confirm New Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i
                      class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                </div>
              </div>
            </div>
            <h6 class="text-body">Password Requirements:</h6>
            <ul class="ps-4 mb-0">
              <li class="mb-4">Minimum 8 characters long - the more, the better</li>
              <li class="mb-4">At least one lowercase character</li>
              <li>At least one number, symbol, or whitespace character</li>
            </ul>
            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Save changes</button>
              <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
          </form>
        </div>
      </div>
      <!--/ Change Password -->
      <!-- Recent Devices -->
      <div class="card">
        <h6 class="card-header">Recent Devices</h6>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="text-truncate">Browser</th>
                <th class="text-truncate">Device</th>
                <th class="text-truncate">Location</th>
                <th class="text-truncate">Recent Activities</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-macbook-line icon-20px text-warning me-3"></i>Chrome on Windows</td>
                <td class="text-truncate">HP Spectre 360</td>
                <td class="text-truncate">Switzerland</td>
                <td class="text-truncate">10, July 2021 20:07</td>
              </tr>
              <tr>
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-android-line icon-20px text-success me-3"></i>Chrome on iPhone</td>
                <td class="text-truncate">iPhone 12x</td>
                <td class="text-truncate">Australia</td>
                <td class="text-truncate">13, July 2021 10:10</td>
              </tr>
              <tr>
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-smartphone-line icon-20px text-danger me-3"></i>Chrome on Android</td>
                <td class="text-truncate">Oneplus 9 Pro</td>
                <td class="text-truncate">Dubai</td>
                <td class="text-truncate">14, July 2021 15:15</td>
              </tr>
              <tr>
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-mac-line icon-20px text-info me-3"></i>Chrome on MacOS</td>
                <td class="text-truncate">Apple iMac</td>
                <td class="text-truncate">India</td>
                <td class="text-truncate">16, July 2021 16:17</td>
              </tr>
              <tr>
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-macbook-line icon-20px text-warning me-3"></i>Chrome on Windows</td>
                <td class="text-truncate">HP Spectre 360</td>
                <td class="text-truncate">Switzerland</td>
                <td class="text-truncate">20, July 2021 21:01</td>
              </tr>
              <tr class="border-transparent">
                <td class="text-truncate text-heading"><i
                    class="icon-base ri ri-android-line icon-20px text-success me-3"></i>Chrome on Android</td>
                <td class="text-truncate">Oneplus 9 Pro</td>
                <td class="text-truncate">Dubai</td>
                <td class="text-truncate">21, July 2021 12:22</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!--/ Recent Devices -->
    </div>
  </div>
@endsection
