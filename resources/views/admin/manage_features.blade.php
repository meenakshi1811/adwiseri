@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Manage Features</h3>
                        <div class="d-flex ">
                            <a href="{{ route('add_feature') }}">Add New +</a>
                            {{-- <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"> --}}
                        </div>
                      </form>
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover fl-table" id="userTable">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Icon</th>
                            <th>Feature</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($features as $key => $feature)
                        <tr>
                            <td class="text-center">{{ $key+1 }}.</td>
                            <td><img src="{{ asset('admin_assets/features/icon/'.$feature->icon) }}" style="width:50px;heigth:50px;"></td>
                            <td>{{ $feature->name }}</td>
                            <td>{{ substr($feature->content, 0, 80).'...' }}</td>
                            <td><a class="p-1 text-dark" href="{{ route('view_feature', $feature->id)}}"><i class="fa-solid fa-eye"></i></a></td>
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
            