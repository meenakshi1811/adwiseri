@extends('admin.layout.main')

@section('main-section')
<div class="col-lg-10 column-client">
    <h3 class="text-primary px-2">Add Subscription</h3>
    <div class="col">
        <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('post_membership') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 p-1">
                    <label>Plan Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="plan_name" minlength="3" maxlength="50" required type="text" class="form-control @error('plan_name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('plan_name') }}" placeholder="Plan Name" autocomplete="plan_name">
                    @error('plan_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Data Limit<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="data_limit" type="text" minlength="1" maxlength="100" class="form-control @error('data_limit') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('data_limit') }}" required placeholder="Data Limit" autocomplete="data_limit">
                    @error('data_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Client Limit<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="client_limit" type="text" minlength="1" maxlength="100" class="form-control @error('client_limit') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('client_limit') }}" required placeholder="Client Limit" autocomplete="client_limit">
                    @error('client_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Reports<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="reports" type="text" minlength="1" maxlength="100" class="form-control @error('reports') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('reports') }}" required placeholder="Reports" autocomplete="reports">
                    @error('reports')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Analytics<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <select name="analytics" class="form-control form-select @error('analytics') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <option value="">Select</option>
                        <option {{ (old('analytics') == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                        <option {{ (old('analytics') == "No") ? 'selected' : '' }} value="No">No</option>
                    </select>
                    @error('analytics')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>User License<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="no_of_users" minlength="0" maxlength="99999999" type="number" class="form-control @error('no_of_users') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('no_of_users') }}" required placeholder="User License" autocomplete="no_of_users">
                    @error('no_of_users')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Number of Branches<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="no_of_branches"min="1" max="99999999" type="number" class="form-control @error('no_of_branches') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('no_of_branches') }}" required placeholder="Number of Branches" autocomplete="no_of_branches">
                    @error('no_of_branches')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Price<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="price_per_year" min="0" max="99999999" type="number" class="form-control @error('price_per_year') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('price_per_year') }}" required placeholder="Price" autocomplete="price_per_year">
                    @error('price_per_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Validity<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="validity" type="number" max="365" min="90" class="form-control @error('validity') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('validity') }}" required placeholder="Validity in days" autocomplete="validity">
                    @error('validity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Messages<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="messaging" minlength="2" maxlength="150" type="text" class="form-control @error('messaging') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('messaging') }}" required placeholder="Messages" autocomplete="messaging">
                    @error('messaging')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Invoicing<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <select name="invoicing" class="form-control form-select @error('invoicing') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <option value="">Select Invoicing</option>
                        <option {{ (old('invoicing') == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                        <option {{ (old('invoicing') == "No") ? 'selected' : '' }} value="No">No</option>
                    </select>
                    @error('invoicing')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Multi Device Support<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <select name="multi_device_support" class="form-control form-select @error('multi_device_support') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <option value="">Select Multi Device Support</option>
                        <option {{ (old('multi_device_support') == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                        <option {{ (old('multi_device_support') == "No") ? 'selected' : '' }} value="No">No</option>
                    </select>
                    @error('multi_device_support')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Secure Environment<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <select name="secure_environment" class="form-control form-select @error('secure_environment') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <option value="">Select Secure Environment</option>
                        <option {{ (old('secure_environment') == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                        <option {{ (old('secure_environment') == "No") ? 'selected' : '' }} value="No">No</option>
                    </select>
                    @error('secure_environment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Multi Currency Support<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <select name="multi_currency_support" class="form-control form-select @error('multi_currency_support') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <option value="">Select Multi Currency Support</option>
                        <option {{ (old('multi_currency_support') == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                        <option {{ (old('multi_currency_support') == "No") ? 'selected' : '' }} value="No">No</option>
                    </select>
                    @error('multi_currency_support')
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
@if(session()->has('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'Subscription Added Successfully.'
    })
  </script>

@endif
@endsection()
            