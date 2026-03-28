@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            @if(isset($faq))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update FAQ</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_faq') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $faq->id }}" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Question<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="question" minlength="3" maxlength="255" required type="text" class="form-control @error('question') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $faq->question }}" placeholder="Question" autocomplete="question">
                                @error('question')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Answer<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <textarea name="answer" minlength="3" maxlength="1000" class="form-control" required rows="5" placeholder="Answer">{{ $faq->answer }}</textarea>
                                @error('answer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col text-start p-1">
                                <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
            @else
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add New FAQ</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_faq') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Question<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="question" required type="text" minlength="5" maxlength="200" class="form-control @error('question') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('question') }}" placeholder="Question" autocomplete="question">
                                @error('question')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Answer<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <textarea name="answer" class="form-control" minlength="5" maxlength="2000" required rows="5" placeholder="Answer"></textarea>
                                @error('answer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col text-start p-1">
                                <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
            @endif
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
      $(document).ready(() => {
        
      });
  </script>
  
  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'User Deleted Successfully!'
      })
    </script>
  
  @endif

@endsection()
            