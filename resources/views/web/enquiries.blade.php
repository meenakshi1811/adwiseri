@extends('web.layout.main')

@section('main-section')

<div class="col-lg-10 column-client">
<div class="client-dashboard">

<div class="col-12 d-flex justify-content-between align-items-center mb-3">
<h3 class="text-primary text-center flex-grow-1 m-0">Enquiries</h3>
<div class="d-flex gap-2">
@php
$encryptedId = encrypt($user->id);
@endphp
<a href="{{ route('createLead', $encryptedId) }}" class="btn btn-info btn-sm">Add Enquiry</a>
</div>
</div>

<div class="row m-0 pb-2">
<div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client') }}';">
Clients
</div>
<div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('subscriber_dependents') }}';">
Dependants
</div>
<div class="col-4 border p-1 text-center bg-info text-white">
Enquries
</div>

</div>

@if(count($enquiries) != 0)

<div class="table-wrapper">

<table class="fl-table table table-hover table-responsive p-0 m-0" id="clientTable">

<thead>

<tr>

<th class="text-center">EnquiryID</th>
<th class="text-center">Client Name</th>
<th class="text-center">COP</th>
<th class="text-center">PVC</th>
<th class="text-center">Contact No</th>
<th class="text-center">Email</th>
<th class="text-center">NOA</th>
<th class="text-center">Created Date</th>
<th class="text-center">Convert to Client</th>
<th class="text-center">Status</th>
<th class="text-center">Action</th>

</tr>

</thead>

<tbody>

@foreach($enquiries as $enquiry)

<tr>

<td class="text-center">{{ $enquiry->id }}</td>

<td class="text-center">
{{ $enquiry->full_name }}
</td>

<td class="text-center">
{{ $enquiry->country_pref_1 }}
@if($enquiry->country_pref_2) , {{ $enquiry->country_pref_2 }} @endif
@if($enquiry->country_pref_3) , {{ $enquiry->country_pref_3 }} @endif
</td>

<td class="text-center">
{{ $enquiry->visa_category }}
</td>

<td class="text-center">
{{ $enquiry->contact_no }}
</td>

<td class="text-center">
{{ $enquiry->email }}
</td>

<td class="text-center">

@php
$children = \App\Models\EnquiryChild::where('enquiry_id',$enquiry->id)->count();
@endphp

@if($children > 0)
Main + {{ $children }}
@else
Single
@endif

</td>

<td class="text-center">
{{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }}
</td>

<td class="text-center">

@if($enquiry->status == 1)

<span class="badge bg-success">Converted</span>

@else

<button class="btn btn-sm btn-success convertClient" data-id="{{ $enquiry->id }}">
Yes
</button>

@endif

</td>

<td class="text-center">

@if($enquiry->status == 1)
<span class="badge bg-success">Client</span>
@else
<span class="badge bg-warning text-dark">Enquiry</span>
@endif

</td>

<td class="pb-5 text-center action-icon" style="display:flex;justify-content:center;align-items:center">

<a href="{{ url('visa-enquiries/view/'.$enquiry->id) }}" title="View">
<i class="fa-solid fa-eye text-info btn p-1 " style="font-size:14px"></i>
</a>

<a href="{{ url('edit-enquiry/'.$enquiry->id) }}" title="Edit">
<i class="fa-solid fa-pen-to-square text-primary btn p-1" style="font-size:14px"></i>
</a>

<a href="javascript:void(0)" onclick="deleteEnquiry({{ $enquiry->id }})" title="Delete">
<i class="fa-solid fa-trash text-danger btn p-1" style="font-size:14px"></i>
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@else

<p class="text-secondary">No enquiries found.</p>

@endif

</div>
</div>

@endsection

<script>

$(document).on('click','.convertClient',function(){

    var enquiryId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to convert this enquiry into client?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Convert',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if(result.isConfirmed){

            $.ajax({

                url:"{{ url('convert-enquiry-client') }}",
                type:"POST",
                data:{
                    _token:"{{ csrf_token() }}",
                    enquiry_id:enquiryId
                },

                success:function(response){

                    Swal.fire({
                        icon:'success',
                        title:'Success',
                        text:'Enquiry converted to client successfully!'
                    });

                    setTimeout(function(){
                        location.reload();
                    },1500);

                },

                error:function(){

                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:'Something went wrong!'
                    });

                }

            });

        }

    });

});

function deleteEnquiry(id){

    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this enquiry!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes delete it"
    }).then((result) => {

        if(result.isConfirmed){

        $.ajax({
            url:'/visa-enquiries/delete/'+id,
            type:'DELETE',
            data:{
            _token:'{{ csrf_token() }}'
        },
            success:function(res){

            Swal.fire("Deleted!", res.message, "success")
            .then(()=>{
            location.reload();
            });

            }

        });

    }

});

}
</script>