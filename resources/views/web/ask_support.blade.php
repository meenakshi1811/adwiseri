@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <h3 class="text-primary px-2">Raise Support Ticket</h3>
            <div class="col">
                <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('ask_new_question') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <label>Subscriber<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <input type="text" class="form-control bg-white" readonly name="subscriber" value="{{ $subscriber->name.'('.$subscriber->id.')' }}" />
                        </div>
                        {{-- <div class="col-md-4 p-1">
                            <label>Client<span class="text-danger" style="font-size: 18px;"></span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="client" class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        <div class="col-md-4 p-1">
                            <label>Department<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="support" class="form-control form-select @error('support') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="">Select Department</option>
                                <option value="Sales">Sales</option>
                                <option value="Billing">Billing</option>
                                <option value="Support">Support</option>
                            </select>
                            @error('support')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Query/Question<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <textarea name="question" minlength="3" maxlength="500" required rows="3" class="form-control @error('question') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('question') }}" placeholder="Query/Question" autocomplete="question"></textarea>
                            @error('question')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Attach File</label>
                        </div>
                        <div class="col-md-8 p-1">
                            <input type="file" class="form-control" name="attachment" id="attachment" accept=".jpg,.jpeg,.png" />
                            <label style="font-size:12px;">Select jpg, jpeg, png formats up to 4MB.</label>
                            {{-- <textarea name="question" minlength="3" maxlength="1000" required rows="3" class="form-control @error('question') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('question') }}" placeholder="Query/Question" autocomplete="question"></textarea> --}}
                            @error('attachment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <!-- <label>Attach File</label> -->
                        </div>
                        <div class="col-md-8 text-left p-1">
                            <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col p-3">
                <h5 class="px-2 m-0 mb-3">Tickets</h5>
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                            <th class="text-center">Sr.No.</th>
                            <th class="text-center">Ticket ID</th>
                            <th class="text-center">Issue</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $key => $tic)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $tic->ticket_no }}</td>
                            <td class="text-wrap text-center">{{ $tic->issue }}</td>
                            <td class="text-center">{{ $tic->support }}</td>
                            <td class="text-center">{{ $tic->status }}</td>
                            <td class="text-center">{{ date("d-m-Y", strtotime($tic->created_at)) }}</td>
                            <td class="text-center">
                                <a style="background:none; border:none;" onclick="window.location.href = '{{ route('my_query', $tic->id) }}';" class="m-0 p-0"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a>
                            </td>
                        </tr>
                        @endforeach

                        <tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
    $("#attachment").change(function() {
        const file = this.files[0];
        var filepath = $(this).val();
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;
        if (!allowedExtensions.exec(filepath)) {
            Swal.fire({
                title: "Oops..",
                icon: "info",
                html: "Please select valid file format <br>( jpg, jpeg, png )"
            });
            $(this).val("");
            return false;
        }
        const size = (this.files[0].size / 1024 / 1024).toFixed(2);
        if (size > 4) {
            Swal.fire({
                title: "Oops..",
                icon: "info",
                html: "Please select file upto 4MB"
            });
            $(this).val("");
            return false;
        }
        // if (file) {
        //     let reader = new FileReader();
        //     reader.onload = function(event) {
        //         $("#profile_pic_preview").attr("src", event.target.result);
        //     };
        //     reader.readAsDataURL(file);
        // }
    });
      $(document).ready(() => {

          $("#country").change(function(){
            var country = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_states',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    country: country,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#state").html(data);
                }
            });
          });
      });
  </script>

@if($errors->has('attachment'))
<script>
    Swal.fire({
        title: 'Oops..',
        icon: 'info',
        html: @json($errors->first('attachment'))
    });
</script>
@endif

@if(session()->has('success'))
  <script>
    Swal.fire({
        icon: 'success',
        title: 'Your support request received.',
        text: 'someone from relevant team will be in touch soon.',
    })
  </script>

@endif

@endsection()
