@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Manage Subscriptions</h3>
                        <div class="d-flex ">
                            <a href="{{ route('add_membership') }}">Add New +</a>
                            {{-- <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"> --}}
                        </div>
                      </form>
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover fl-table" id="userTable">
                        <thead>
                        <tr>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Data Limit</th>
                            <th class="text-center">User License</th>
                            <th class="text-center">Client Limit</th>
                            <th class="text-center">Validity</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($memberships as $membership)
                        <tr>
                            <td class="text-center">{{ $membership->plan_name }}</td>
                            <td class="text-center">{{ $membership->data_limit }}</td>
                            <td class="text-center">{{ $membership->no_of_users }}</td>
                            <td class="text-center">{{ $membership->client_limit }}</td>
                            <td class="text-center">US ${{ $membership->price_per_year }} / {{$membership->validity}} Days</td>
                            <td class="text-center"><a class="p-1 text-dark" href="{{ route('membership_plan', $membership->id)}}"><i class="fa-solid fa-eye"></i></a></td>
                        </tr>
                        @endforeach

                        <tbody>
                    </table>
                </div>
                {{-- <div class="table-btn">
                    <button>Previous</button>
                    <button>1</button>
                    <button>Next</button>
                </div> --}}
            </div>
        </div>
    </div>

  </div>

@endsection()
