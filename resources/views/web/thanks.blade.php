@extends('web.layout.main')

@section('main-section')
  <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form class="thanks-box login-box">
                    <h1 class="text-center mb-5">Thanks your account <br> has been created. <br>
                        Click here to login</h1>
                    <a href="{{ route('login') }}" class="btn btn-primary mb-5">Login</a>

                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

@endsection()
