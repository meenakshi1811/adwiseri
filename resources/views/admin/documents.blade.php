@extends('admin.layout.main')

@section('main-section')
    <div class="col-lg-10 column-client">
        <div class="client-dashboard">
            <div class="client-btn d-flex mb-2 ">
                {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                {{-- <h3 class="text-primary">Documents</h3> --}}
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Documents</h3>
                <a href="{{ route('new_document') }}" class="m-0">Add New</a>
                {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                {{-- </form> --}}
                {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
            </div>
            <div class="row m-0 p-2">
                <div class="col-3 border p-1 text-center tab-anchor"
                    onclick="window.location.href = '{{ route('manage_applications') }}';">
                    Applications
                </div>
                <div class="col-3 border p-1 text-center bg-info text-white tab-anchor">
                    Documents
                </div>
                <div class="col-3 border p-1 text-center  tab-anchor"
                    onclick="window.location.href = '{{ route('application_management') }}';">
                    Application Management
                </div>
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('application_tracking') }}';">
                  Application Tracking
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table table-hover table-bordered fl-table" id="clientTable">
                    <thead>
                        <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">DocumentID</th>
                            <th class="text-center">Sub_ID</th>
                            <th class="text-center">Cilent(ID)</th>
                            <th class="text-center">Application (ID)</th>
                            <th class="text-center">Doc_Type</th>
                            <th class="text-center">Doc_Name</th>
                            <th class="text-center">File</th>
                            <th class="text-center">File_Size</th>
                            <th class="text-center">Uploaded Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($client_docs as $key => $doc)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $doc->id }}</td>
                                <td class="text-center">{{ $doc->client ? $doc->client->subscriber_id : '' }}</td>
                                <td class="text-center">
                                    {{ $doc->client ? $doc->client->name . '(' . $doc->client->id . ')' : '' }}</td>
                                @php $apps = \App\Models\Applications::where('application_id', $doc->application_id)->first() @endphp
                                <td class="text-center">{{ $apps ? $apps->application_name . '(' . $apps->id . ')' : '' }}</td>
                                <td class="text-center">{{ $doc->doc_type }}</td>
                                <td class="text-center">{{ $doc->doc_name }}</td>
                                <td class="text-center">
                                    @php
                                        // Define the path to the file
                                        $filePath = public_path(
                                            'web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file,
                                        );
                                    @endphp

                                    @if (file_exists($filePath) && $doc->doc_file)
                                        <!-- File exists, display the download link and file name -->
                                        <a href="{{ asset('web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file) }}"
                                            download="{{ $doc->doc_file }}" class="p-0 m-0"
                                            style="text-decoration: none;border:none;background:none;">
                                            <i class="fa-solid fa-download btn p-1 text-primary"
                                                style="font-size:14px;"></i>
                                        </a>
                                        {{ $doc->doc_file }}
                                    @else
                                        <!-- No file found -->
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $filePath = public_path(
                                            'web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file,
                                        );
                                        $fileSize = file_exists($filePath)
                                            ? number_format(filesize($filePath) / 1024, 2) . ' KB'
                                            : '';
                                    @endphp
                                    {{ $fileSize }}
                                </td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($doc->created_at)) }}</td>
                                <td class="text-center">
                                    {{-- <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('application_view', $doc->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a> --}}
                                    <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;"
                                        onclick="updatedocument({{ $doc->id }})"></i>
                                    <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"
                                        onclick="deletedocument({{ $doc->id }})"></i>
                                </td>
                                {{-- <td>
                                <a class="p-1 text-dark" href=""><i class="fa-solid fa-eye"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteapplication({{ $app->id }})"></i>
                            </td> --}}
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
    <script>
        function deletedocument(id) {
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
                    window.location.href = "delete_document/" + id + "";
                }
            })
        }

        function updatedocument(id) {
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
                    window.location.href = "document_update/" + id + "";
                }
            })
        }
    </script>

    @if (session()->has('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Application Deleted Successfully!'
            })
        </script>
    @endif
    @if (session()->has('document_added'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'New Document Added Successfully!'
            })
        </script>
    @endif
    @if (session()->has('document_updated'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Document Updated Successfully!'
            })
        </script>
    @endif
@endsection()
