@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
@endphp

@isset($configData['layout'])
  @include(
      $configData['layout'] === 'horizontal'
          ? 'layouts.horizontalLayout'
          : ($configData['layout'] === 'blank'
              ? 'layouts.blankLayout'
              : ($configData['layout'] === 'front'
                  ? 'layouts.layoutFront'
                  : 'layouts.contentNavbarLayout')))
@endisset

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@if(session('error'))
<style>
  .swal2-container {
    z-index: 999999 !important; /* lebih tinggi dari sidebar/nav */
  }
</style>
<script>
    Swal.fire({
        icon: 'error',
  title: 'Waduh 😅...',
        text: '{{ session("error") }}',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
  backdrop: `
    rgba(0,0,0,0.7)
    url("https://sweetalert2.github.io/images/nyan-cat.gif")
    center top
    no-repeat
  `
})
</script>
@endif
