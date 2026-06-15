@php
    use Illuminate\Support\Facades\Auth;

    $layout = Auth::check() ? 'layouts.layoutMaster' : 'layouts.blankLayout';
@endphp

@extends($layout)

@section('content')
    @if(Auth::check())
        @yield('page-content')
    @else
        <div class="py-4 mt-12">
            @yield('page-content')
        </div>
    @endif
@endsection
