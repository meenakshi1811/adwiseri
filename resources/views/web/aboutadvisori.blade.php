@extends('web.layout.main')

@section('main-section')
<style>
        body{
            background-color: #F5F5F5;
        }
    </style>
  <div class="aboutus-page mb-5">
        <img src="{{ asset('admin_assets/about_advisori/banner/'.$about_adwiseri->banner) }}" width="100%" height="auto" alt="">
        <h1>About adwiseri</h1>
       
    </div>


    <div class="container-fluid pb-5 mb-5">
        <div class="row about-advi offset-1">
            <div class="col-lg-7">
                <img src="{{ asset('admin_assets/about_advisori/image/'.$about_adwiseri->image) }}" width="690" height="430" alt="">
            </div>
            <div class="col-lg-5">
                <h4>{{ $about_adwiseri->heading}}</h4>
                <p class="mt-5">{{ $about_adwiseri->content }}</p>
            </div>
        </div>
    </div>

@endsection()
