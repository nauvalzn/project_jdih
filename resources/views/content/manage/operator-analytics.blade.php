@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.layoutMaster')

@section('title', 'Dashboard Analisis Dokumen')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/swiper/swiper.scss'
])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/cards-statistics.scss'])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/swiper/swiper.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/dashboards-analytics.js'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Simpan URL awal saat user pertama kali masuk
    if (!sessionStorage.getItem('startUrl')) {
        sessionStorage.setItem('startUrl', window.location.href);
    }
</script>
@endsection

@section('content')
<div class="row g-6">
  <!-- Gamification Card -->
  <div class="col-12">
    <div class="card w-100">
      <div class="d-flex align-items-end row">
        <div class="col-md-6 order-2 order-md-1">
          <div class="card-body">
            <h4 class="card-title mb-4">
              Haii <span class="fw-bold">{{ auth()->user()->name }} </span> 
              <span style="font-family:Roboto; font:lighter;">[{{ Auth::user()->role }}]</span>🎉
            </h4>
            <p class="mb-2">Selamat datang kembali di dashboard <i>Management!</i></p>
            <a href="{{ route('pages-account-settings-account') }}" class="btn btn-primary">Lihat Profil</a>
          </div>
        </div>
        <div class="col-md-6 text-center text-md-end order-1 order-md-2">
          <div class="card-body pb-0 px-0 pt-2">
            <img src="{{ asset('assets/img/illustrations/illustration-john-'.$configData['theme'].'.png') }}"
                 height="186"
                 class="scaleX-n1-rtl"
                 alt="View Profile"
                 data-app-light-img="illustrations/John-Gaya-removebg-preview.png"
                 data-app-dark-img="illustrations/John-Programmer-removebg-preview.png" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Gamification Card -->
</div>
@endsection
