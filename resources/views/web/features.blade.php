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
    <h1 class="text-center">Features</h1>
    <div class="row mt-5 top-feat">
      @foreach($features as $feature)
      <div class="col-lg-3 p-1" style="display: flex;flex-direction:column;flex:0 1 auto;">
        <div class="col d-flex features-card align-items-center">
            <img src="{{ asset('admin_assets/features/icon/'.$feature->icon) }}" width="60" height="63" alt="">
            <h4 class="px-3">{{ $feature->name }}</h4>
            <p>{{ $feature->content }}</p>
        </div>
      </div>
      @endforeach
        {{-- <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/casem.png') }}" width="58" height="63" alt="">
            <h4>Case management</h4>
            <p>Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div>
        <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/docs.png') }}" width="64" height="63" alt="">
            <h4>Document storage</h4>
            <p>Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div>
        <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/entergrad.png') }}" width="54" height="63" alt="">
            <h4>Enterprise-grade Security</h4>
            <p>Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div>
        <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/invoice.png') }}" width="69" height="63" alt="">
            <h4>Analytics & Reports <br> Invoices & Payments
            </h4>
            <p>Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div>
        <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/multiu.png') }}" width="70" height="63" alt="">
            <h4>Multi-user access
            </h4>
            <p class="mt-4 pt-3">Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div>
        <div class="col-lg-3 features-card">
            <img src="{{ asset('web_assets/images/fixpr.png') }}" width="63" height="63" alt="">
            <h4>Fixed pricing model</h4>
            <p class="mt-4 pt-3">Contrary to popular belief, Lorem Ipsum is not simply <br>
                 random text. It has roots in a piece of classical Latin<br>
                  literature from 45 BC, making it over 2000 years old.<br>
                   Richard McClintock, a Latin professor at Hampden-<br>
                   Sydney College in Virginia, looked up one of the more<br>
                    obscure Latin words, consectetur, from a Lorem Ipsum<br>
                     passage, and going through the cites of the word in <br>
                     classical literature, discovered the undoubtable source.  </p>
        </div> --}}
    </div>
  </div>

@endsection()
