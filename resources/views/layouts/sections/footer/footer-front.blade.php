<!-- Footer: Start -->
<footer class="landing-footer">
  <div class="footer-top position-relative overflow-hidden">
    <img src="{{asset('assets/img/front-pages/backgrounds/footer-bg.png')}}" alt="footer bg" class="footer-bg banner-bg-img" />
    <div class="container">
      <div class="row gx-0 gy-6 g-lg-10">
        <div class="col-lg-5">
          <a href="{{url('front-pages/landing')}}" class="app-brand-link mb-6">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
          </a>
          <p class="footer-text footer-logo-description mb-6">
            Portal resmi untuk akses dokumen RSUD Kesehatan Kerja. Memudahkan staf dan masyarakat mendapatkan informasi regulasi & dokumen penting secara cepat.
          </p>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4 mb-lg-6">Dokumen</h6>
          <ul class="list-unstyled mb-0">
            <li class="mb-4"><a href="{{ url('peraturan-gubernur') }}" class="footer-link">Peraturan Gubernur</a></li>
            <li class="mb-4"><a href="{{ url('keputusan-gubernur') }}" class="footer-link">Keputusan Gubernur</a></li>
            <li class="mb-4"><a href="{{ url('peraturan-direktur') }}" class="footer-link">Peraturan Direktur</a></li>
            <li class="mb-4"><a href="{{ url('keputusan-direktur') }}" class="footer-link">Keputusan Direktur</a></li>
            <li class="mb-4"><a href="{{ url('perizinan') }}" class="footer-link">Perizinan</a></li>
            <li class="mb-4"><a href="{{ url('sop') }}" class="footer-link">SOP</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4 mb-lg-6">Informasi</h6>
          <ul class="list-unstyled mb-0">
            <li class="mb-4"><a href="https://rsudkk.jabarprov.go.id/sejarah_singkat" class="footer-link">Tentang RSUD</a></li>
            <li class="mb-4"><a href="https://rsudkk.jabarprov.go.id/kontak_kami" class="footer-link">Kontak</a></li>
          </ul>
        </div>
        {{-- <div class="col-lg-3 col-md-4">
          <h6 class="footer-title mb-4 mb-lg-6">Download Aplikasi</h6>
          <a href="javascript:void(0);" class="d-block footer-link mb-4"><img src="{{asset('assets/img/front-pages/landing-page/apple-icon.png')}}" alt="apple icon" /></a>
          <a href="javascript:void(0);" class="d-block footer-link"><img src="{{asset('assets/img/front-pages/landing-page/google-play-icon.png')}}" alt="google play icon" /></a>
        </div> --}}
      </div>
    </div>
  </div>
  <div class="footer-bottom py-5">
    <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
      <div class="mb-2 mb-md-0">
        <span class="footer-bottom-text">
          © <script>document.write(new Date().getFullYear());</script>, RSUD Kesehatan Kerja
        </span>
      </div>
      <div>
        <a href="https://facebook.com/RSUDKK" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-facebook-circle-fill"></i></a>
        <a href="https://api.whatsapp.com/send?phone=628111114092" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-whatsapp-fill"></i></a>
        <a href="https://www.youtube.com/@rskkjabar3910" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-youtube-fill"></i></a>
        <a href="https://x.com/RSKK_JABAR" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-twitter-x-fill"></i></a>
        <a href="https://instagram.com/rskk_jabar" class="footer-link" target="_blank"><i class="icon-base ri ri-instagram-line"></i></a>
      </div>
    </div>
  </div>
</footer>
<!-- Footer: End -->
