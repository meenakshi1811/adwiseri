@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Create New Invoice</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('admin_new_invoice_post') }}" onsubmit="document.getElementById('invoice_submit').setAttribute('disabled','true');">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Subscriber<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="subscriber" id="subscriber" required class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Subscriber</option>
                                    @foreach($subscribers as $subs)
                                    <option value="{{ $subs->id }}">{{ $subs->name."(".$subs->id.")" }}</option>
                                    @endforeach
                                </select>
                                @error('subscriber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Service Description<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="detail" minlength="3" maxlength="150" type="text" class="form-control @error('detail') is-invalid @enderror" id="exampleInputage1" aria-describedby="ageHelp" value="{{ old('detail') }}" required placeholder="Service Description" autocomplete="detail">
                                @error('detail')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Amount<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="amount" min="0" required type="number" class="form-control @error('amount') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('amount') }}" placeholder="Amount" autocomplete="amount">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4 p-1">
                                <label>Discount</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="discount" type="number" class="form-control @error('discount') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('discount') }}" required placeholder="Discount" autocomplete="discount">
                                @error('discount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Tax (%)</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="tax" type="number" min=0 max="100" class="form-control @error('tax') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('tax') }}" required placeholder="tax percent(%)" autocomplete="tax">
                                @error('tax')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                            <div class="col-md-4 p-1">
                                <label>Invoice Status<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="status" id="status" class="form-control form-select @error('status') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Status</option>
                                    <option {{ (old('status') == "PartiallyPaid") ? 'selected' : ''}} value="PartiallyPaid">PartiallyPaid</option>
                                    <option {{ (old('status') == "UnPaid") ? 'selected' : ''}} value="UnPaid">UnPaid</option>
                                    <option {{ (old('status') == "Paid") ? 'selected' : ''}} value="Paid">Paid</option>
                                    <option {{(old('status') == "Cancelled") ? 'selected':''}} value="Cancelled">Cancelled</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Payment Due Date<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="due_date" required type="text" class="form-control datepicker @error('due_date') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('due_date', date('d-m-Y')) }}"
                                placeholder="dd-mm-yyyy" autocomplete="due_date" pattern="\d{2}-\d{2}-\d{4}">
                            @error('due_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="col-md-4 p-1">
                            </div>
                            <div class="col-md-8 text-left p-1">
                                <button type="submit" id="invoice_submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

  </div>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
      $(document).ready(() => {
          flatpickr('.datepicker', {
            dateFormat: "d-m-Y",
            defaultDate: $('input[name="due_date"]').val() || "today",
            allowInput: true,
            clickOpens: true
          });

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
          $("#subscriber").change(function(){
            var id = $(this).val();
            var name = 'subscriber';
            // console.log(counrty);
            $.ajax({
                url: 'get_job_role',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    name: name,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#job_role").html(data);
                }
            });
          });
      });
  </script>
  <script>
      function deleteuser(id){
          var conf = confirm('Delete User');
          if(conf == true){
              window.location.href = "delete_user/"+id+"";
          }
      }
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
            
