@extends('web.layout.main')

@section('main-section')
<style>
        body{
            background-color: #F5F5F5;
        }
    </style>
    <style>
    .banner {
    width: 100%;
    height: 450px;
    overflow: hidden;
    }

    .banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;     /* Fill area without distortion */
    object-position: center; /* Center the image */
    }

</style>
  <div class="featurebanner mb-5 banner">
    <img src="{{ asset('web_assets/images/services-banner-1.jpg') }}" width="100%" height="auto" alt="">
  </div>

  <div class="container page-feat mb-5">
    <h1 class="text-center">FAQ's</h1>
    <div class="col">
            <p class="px-2 m-0"><strong>FAQs</strong></p>
            @foreach($faqs as $faq)
            <div class="col p-1">
                <button class="btn btn-outline-dark text-start" style="width: 100%;" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample{{ $faq->id }}" aria-expanded="false" aria-controls="collapseExample{{ $faq->id }}">
                    {{ $faq->question }}
                </button>
                <div class="collapse" id="collapseExample{{ $faq->id }}">
                    <div class="card card-body border-dark bg-light text-dark p-2 m-0">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
  </div>

@endsection()
