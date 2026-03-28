@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
        <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update Document</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('upload_client_document') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <input type="hidden" name="id" value="{{ $document->id }}" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Client ID<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="client_id" id="client_id" class="form-control form-select @error('client_id') is-invalid @enderror" required>
                                    <option value="" {{ old('client_id') ? '' : 'selected' }}>Select Client ID</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $document->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Application ID<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="application_id" id="application_id" required class="form-control form-select @error('application_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option {{ ($application->id == $document->application_id) ? 'selected' : ''}} value="{{  $application->id }}">{{ $application->application_name }} ({{ $application->id }})</option>
                                </select>
                                @error('application_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Document Type<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                              <select name="doc_type" id="doc_type" class="form-control form-select @error('doc_type') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="">Select Document Type</option>
                                <option {{ ($document->doc_type == "Photo") ? 'selected' : ''}} value="Photo">Photo</option>
                                <option {{ ($document->doc_type == "Passport") ? 'selected' : ''}} value="Passport">Passport</option>
                                <option {{ ($document->doc_type == "Birth Certificate") ? 'selected' : ''}} value="Birth Certificate">Birth Certificate</option>
                                <option {{ ($document->doc_type == "Marriage Certificate") ? 'selected' : ''}} value="Marriage Certificate">Marriage Certificate</option>
                                <option {{ ($document->doc_type == "Degree Certificate") ? 'selected' : ''}} value="Degree Certificate">Degree Certificate</option>
                                <option {{ ($document->doc_type == "Application Form") ? 'selected' : ''}} value="Application Form">Application Form</option>
                                <option {{ ($document->doc_type == "Decision") ? 'selected' : ''}} value="Decision">Decision</option>
                                <option {{ ($document->doc_type == "Appeal") ? 'selected' : ''}} value="Appeal">Appeal</option>
                                <option {{ ($document->doc_type == "Appeal Decision") ? 'selected' : ''}} value="Appeal Decision">Appeal Decision</option>
                                <option {{ ($document->doc_type == "Admin/Judicial Review") ? 'selected' : ''}} value="Admin/Judicial Review">Admin/Judicial Review</option>
                                <option {{ ($document->doc_type == "AR/JR Decision") ? 'selected' : ''}} value="AR/JR Decision">AR/JR Decision</option>
                                <option {{ ($document->doc_type == "Supporting Document 1") ? 'selected' : ''}} value="Supporting Document 1">Supporting Document 1</option>
                                <option {{ ($document->doc_type == "Supporting Document 2") ? 'selected' : ''}} value="Supporting Document 2">Supporting Document 2</option>
                                <option {{ ($document->doc_type == "Supporting Document 3") ? 'selected' : ''}} value="Supporting Document 3">Supporting Document 3</option>
                                <option {{ ($document->doc_type == "Supporting Document 4") ? 'selected' : ''}} value="Supporting Document 4">Supporting Document 4</option>
                                <option {{ ($document->doc_type == "Supporting Document 5") ? 'selected' : ''}} value="Supporting Document 5">Supporting Document 5</option>
                                <option {{ ($document->doc_type == "Supporting Document 6") ? 'selected' : ''}} value="Supporting Document 6">Supporting Document 6</option>
                                <option {{ ($document->doc_type == "Supporting Document 7") ? 'selected' : ''}} value="Supporting Document 7">Supporting Document 7</option>
                                <option {{ ($document->doc_type == "Supporting Document 8") ? 'selected' : ''}} value="Supporting Document 8">Supporting Document 8</option>
                                <option {{ ($document->doc_type == "Supporting Document 9") ? 'selected' : ''}} value="Supporting Document 9">Supporting Document 9</option>
                                <option {{ ($document->doc_type == "Supporting Document 10") ? 'selected' : ''}} value="Supporting Document 10">Supporting Document 10</option>
                                <option {{ ($document->doc_type == "Other") ? 'selected' : ''}} value="Other">Other</option>
                              </select>
                                @error('doc_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Document Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="doc_name" type="text" minlength="3" maxlength="100" class="form-control @error('doc_name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $document->doc_name }}" required placeholder="Document Name" autocomplete="doc_name">
                            @error('doc_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Document File<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="doc_file" type="file" class="form-control @error('doc_file') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" accept=".jpg,.jpeg,.png,.pdf">
                            @error('doc_file')
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
        </div>
    </div>

  </div>
  <script>
    function deletedocument(id){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "delete_document/"+id+"";
        }
      })
    }
      function updatedocument(id){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to update this record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "client_document_update/"+id+"";
          }
        })
      }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
    $(document).ready(() => {

        $("#add_new").click(function(){
            $("#add_new").css('display','none');
            $("#back").css('display','block');
            $("#new_document").css('display','block');
            $("#documents").css('display','none');
        });
        $("#back").click(function(){
            $("#add_new").css('display','block');
            $("#back").css('display','none');
            $("#new_document").css('display','none');
            $("#documents").css('display','block');
        });
        $("#client_id").change(function(){
            var app_id = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: "{{route('get_client')}}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: app_id,
                },
                cache:false,
                success: function(data){
                    console.log(data);
                    $("#application_id").html(data);
                }
            });
        });
        $(document).on('change', 'input[type=file]', function(){
          const file = this.files[0];
          var filepath = $(this).val();
          var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.JPG|\.JPEG|\.PNG|\.PDF)$/i;
          if (!allowedExtensions.exec(filepath)) {
              Swal.fire({
                  title: "Oops..",
                  icon:"info",
                  html: "Please select valid file format <br>( jpg, jpeg, png or pdf )"
              });
              $(this).val("");
              return false;
          }
          const size = (this.files[0].size / 1024 / 1024).toFixed(2);
          if (size > 4) {
              Swal.fire({
                  title: "Oops..",
                  icon:"info",
                  html: "Please select file upto 4MB"
              });
              $(this).val("");
              return false;
          }
        });
    });
  </script>


  @if($errors->has('doc_file'))
    <script>
      Swal.fire({
        title: 'Oops..',
        icon: 'info',
        html: @json($errors->first('doc_file'))
      })
    </script>
  @endif

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Application Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('document_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New Document Added Successfully!'
      })
    </script>

  @endif
  @if(session()->has('document_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Document Updated Successfully!'
      })
    </script>

  @endif

@endsection()
