@include('web.layout.header')
@if(isset($user))
@include('web.layout.auth_nav')
@else
@include('web.layout.nav')
@endif
@stack('css')
  @yield('main-section')

@include('web.layout.footer')
