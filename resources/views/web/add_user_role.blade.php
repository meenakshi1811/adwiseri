@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                @if(isset($update))
                <div id="new_assignment" class="col">
                  <h5>Update User Access</h5>
                  <form id="user_role_form" class="register-box login-box" method="POST" action="{{ route('user_role_post') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <label>User/Advisor<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="user_id" id="user_id" class="form-control form-select @error('user_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="{{$staff->id}}">{{$staff->name}}({{ $staff->id }})</option>
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 p-1">
                            <label>Access Rights<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-12 p-1">
                          <div class="row m-0">
                            <div class="col-md-6 py-1">
                              <input type="radio" name="access_type" onclick="change_access(this);" value="full_access" /> Full Access
                            </div>
                            <div class="col-md-6 py-1">
                              <input type="radio" name="access_type" onclick="change_access(this);" checked value="limited_access" /> Limited Access
                            </div>
                        </div>
                        <div class="col-md-12 p-1" id="access_table" style="display: block;">
                          <div class="table-wrapper m-0">
                            <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                              <tr>
                                <th class="text-center">Module</th>
                                <th class="text-center">Read</th>
                                <th class="text-center">Insert</th>
                                <th class="text-center">Update</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center">Read/Write</th>
                                {{-- <th class="text-center">All</th> --}}
                              </tr>
                              @foreach($roles as $role)
                              <tr>
                                <td>{{$role->module}}</td>
                                <td class="text-center"><input type="checkbox" class="client" {{($role->read_only == 1) ? 'checked':''}} name="{{strtolower($role->module)}}_read_only" value="1" /></td>
                                <td class="text-center"><input type="checkbox" class="client" {{($role->write_only == 1) ? 'checked':''}} name="{{strtolower($role->module)}}_write_only" value="1" /></td>
                                <td class="text-center"><input type="checkbox" class="client" {{($role->update_only == 1) ? 'checked':''}} name="{{strtolower($role->module)}}_update_only" value="1" /></td>
                                <td class="text-center"><input type="checkbox" class="client" {{($role->delete_only == 1) ? 'checked':''}} name="{{strtolower($role->module)}}_delete_only" value="1" /></td>
                                <td class="text-center"><input type="checkbox" class="client" {{($role->read_write_only == 1) ? 'checked':''}} name="{{strtolower($role->module)}}_read_write_only" value="1" /></td>
                                {{-- <td class="text-center"><input type="checkbox" id="clientall" name="client_all" value="1" /></td> --}}
                              </tr>
                              @endforeach
                            </table>
                          </div>
                            {{-- <div class="row m-0">
                              <div class="col-md-2 px-0">
                                Clients
                              </div>
                              <div class="col-md-2 px-0">
                                  <input type="checkbox" name="read_only" value="1" />&nbsp;&nbsp;Read
                              </div>
                              <div class="col-md-2 px-0">
                                  <input type="checkbox" name="write_only" value="1" />&nbsp;&nbsp;Insert
                              </div>
                              <div class="col-md-2 px-0">
                                  <input type="checkbox" name="update_only" value="1" />&nbsp;&nbsp;Update
                              </div>
                              <div class="col-md-2 px-0">
                                  <input type="checkbox" name="delete_only" value="1" />&nbsp;&nbsp;Delete
                              </div>
                              <div class="col-md-2 px-0">
                                  <input type="checkbox" name="read_write_only" value="1" />&nbsp;&nbsp;Read & Write
                              </div>
                            </div> --}}
                            @error('user_id')
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
                    {{-- <form id="user_role_form" class="register-box login-box" method="POST" action="{{ route('user_role_post') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>User/Advisor<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="user_id" id="user_id" class="form-control form-select @error('user_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="{{$role->user_id}}">{{$role->name}}({{$role->user_id}})</option>
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Roles<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <div class="row m-0">
                                    <div class="col-md-6">
                                        <input {{($role->read_only != 0) ? 'checked':''}} type="checkbox" name="read_only" value="1" />&nbsp;&nbsp;Read
                                    </div>
                                    <div class="col-md-6">
                                        <input {{($role->write_only != 0) ? 'checked':''}} type="checkbox" name="write_only" value="1" />&nbsp;&nbsp;Insert
                                    </div>
                                    <div class="col-md-6">
                                        <input {{($role->update_only != 0) ? 'checked':''}} type="checkbox" name="update_only" value="1" />&nbsp;&nbsp;Update
                                    </div>
                                    <div class="col-md-6">
                                        <input {{($role->delete_only != 0) ? 'checked':''}} type="checkbox" name="delete_only" value="1" />&nbsp;&nbsp;Delete
                                    </div>
                                    <div class="col-md-6">
                                        <input {{($role->read_write_only != 0) ? 'checked':''}} type="checkbox" name="read_write_only" value="1" />&nbsp;&nbsp;Read & Write
                                    </div>
                                </div>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col text-start p-1">
                                <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form> --}}
                </div>
                @else
                <div class="col">
                  <h5>New User Access</h5>
                    <form id="user_role_form" class="register-box login-box" method="POST" action="{{ route('user_role_post') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>User/Advisor<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="user_id" id="user_id" class="form-control form-select @error('user_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select User/Advisor</option>
                                    @foreach($siteusers as $u)
                                    @if(in_array($u->id, $existing))
                                    @php continue; @endphp
                                    @else
                                    <option value="{{ $u->id }}">{{ $u->name }}({{ $u->id }})</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12 p-1">
                                <label>Access Rights<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-12 p-1">
                              <div class="row m-0">
                                <div class="col-md-6 py-1">
                                  <input type="radio" name="access_type" onclick="change_access(this);" checked value="full_access" /> Full Access
                                </div>
                                <div class="col-md-6 py-1">
                                  <input type="radio" name="access_type" onclick="change_access(this);" value="limited_access" /> limited Access
                                </div>
                            </div>
                            <div class="col-md-12 p-1" id="access_table" style="display: none;">
                              <div class="table-wrapper m-0">
                                <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                                  <tr>
                                    <th class="text-center">Module</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Insert</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                    <th class="text-center">Read/Write</th>
                                    {{-- <th class="text-center">All</th> --}}
                                  </tr>
                                  <tr>
                                    <td>Clients</td>
                                    <td class="text-center"><input type="checkbox" class="client" checked name="clients_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="client" checked name="clients_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="client" checked name="clients_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="client" checked name="clients_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="client" checked name="clients_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="clientall" name="client_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Applications</td>
                                    <td class="text-center"><input type="checkbox" class="application" checked name="applications_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="application" checked name="applications_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="application" checked name="applications_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="application" checked name="applications_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="application" checked name="applications_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="applicationall" name="application_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Communication</td>
                                    <td class="text-center"><input type="checkbox" class="communication" checked name="communication_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="communication" checked name="communication_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="communication" checked name="communication_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="communication" checked name="communication_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="communication" checked name="communication_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="communicationall" name="communication_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Invoices</td>
                                    <td class="text-center"><input type="checkbox" class="invoices" checked name="invoices_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="invoices" checked name="invoices_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="invoices" checked name="invoices_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="invoices" checked name="invoices_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="invoices" checked name="invoices_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="invoicesall" name="invoice_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Payments</td>
                                    <td class="text-center"><input type="checkbox" class="payment" checked name="payments_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="payment" checked name="payments_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="payment" checked name="payments_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="payment" checked name="payments_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="payment" checked name="payments_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="paymentall" name="payment_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Reports</td>
                                    <td class="text-center"><input type="checkbox" class="report" name="reports_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="report" name="reports_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="report" name="reports_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="report" name="reports_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="report" name="reports_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="reportall" name="report_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Subscription</td>
                                    <td class="text-center"><input type="checkbox" class="subscription" name="subscription_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="subscription" name="subscription_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="subscription" name="subscription_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="subscription" name="subscription_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="subscription" name="subscription_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="subscriptionall" name="subscription_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Settings</td>
                                    <td class="text-center"><input type="checkbox" class="setting" name="settings_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="setting" name="settings_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="setting" name="settings_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="setting" name="settings_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="setting" name="settings_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="settingall" name="setting_all" value="1" /></td> --}}
                                  </tr>
                                  <tr>
                                    <td>Support</td>
                                    <td class="text-center"><input type="checkbox" class="support" checked name="support_read_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="support" checked name="support_write_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="support" checked name="support_update_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="support" checked name="support_delete_only" value="1" /></td>
                                    <td class="text-center"><input type="checkbox" class="support" checked name="support_read_write_only" value="1" /></td>
                                    {{-- <td class="text-center"><input type="checkbox" id="supportall" name="support_all" value="1" /></td> --}}
                                  </tr>
                                </table>
                              </div>
                                {{-- <div class="row m-0">
                                  <div class="col-md-2 px-0">
                                    Clients
                                  </div>
                                  <div class="col-md-2 px-0">
                                      <input type="checkbox" name="read_only" value="1" />&nbsp;&nbsp;Read
                                  </div>
                                  <div class="col-md-2 px-0">
                                      <input type="checkbox" name="write_only" value="1" />&nbsp;&nbsp;Insert
                                  </div>
                                  <div class="col-md-2 px-0">
                                      <input type="checkbox" name="update_only" value="1" />&nbsp;&nbsp;Update
                                  </div>
                                  <div class="col-md-2 px-0">
                                      <input type="checkbox" name="delete_only" value="1" />&nbsp;&nbsp;Delete
                                  </div>
                                  <div class="col-md-2 px-0">
                                      <input type="checkbox" name="read_write_only" value="1" />&nbsp;&nbsp;Read & Write
                                  </div>
                                </div> --}}
                                @error('user_id')
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
                @endif
            </div>
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
    function change_access(elem){
      var elem = elem;
      var access = elem.value;
      if(access == "full_access"){
        $("#access_table").css('display','none');
      }
      else{
        $("#access_table").css('display','block');
      }
    }
  </script>
  <script>
      $(document).ready(() => {

        $("#clientall").on('click', function(){
          if($(this).is(":checked")){
            $(".client").attr('checked',true);
          }
          else{
            $(".client").attr('checked',false);
          }
        });
        $(".client").on('click', function(){
          if($("#clientall").is(":checked")){
            $("#clientall").attr('checked',false);
          }
        });

      });
  </script>

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Application Assignment Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('assignment_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New Assignment Added Successfully!'
      })
    </script>

  @endif
  @if(session()->has('assignment_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Assignment Updated Successfully!'
      })
    </script>

  @endif

@endsection()
