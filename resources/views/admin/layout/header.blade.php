<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Adwiseri</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('admin_assets/css/select2.min.css') }}"> --}}

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap"
    rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css"> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

  <link rel="stylesheet" href="{{ asset('web_assets/css/owl.carousel.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
            body {
                font-family: 'Lato', sans-serif!important;
            }
        </style>

<!-- Select2 -->



<!-- Dependencies for Buttons -->






<!-- Daterangepicker JS -->

<style>
        
      body{
          background: #F5F5F5;
          /* font-family: 'Lato', sans-serif; */
      }
      /* .dt-buttons {
        position: absolute !important;
        right: 300px !important;
        top: 11px !important;
        float: none;
    } */
    .tab-anchor {
    cursor: pointer; /* Show hand cursor when hovering */
}

/* Optional: change background color or text color on hover */
    .tab-anchor:hover {
        background-color: #17CFCF; /* Example: Change background color on hover */
        color: white; /* Change text color if needed */
    }
    /* .form-control,
     .form-control:focus,
     select.form-control {
        font-family: 'Lato'  !important;
     }   */
     /* * {
    font-family: 'Lato', sans-serif !important;
} */

/* Ensure inputs, selects, and buttons also use Lato */
body, input, button, select, textarea {
    font-family: 'Lato', sans-serif !important;
}

/* Override Bootstrap (if applicable) */
.form-control,
.form-control:focus,
select.form-control {
    font-family: 'Lato', sans-serif !important;
}

/* Ensures Lato applies even inside Bootstrap components */
.bootstrap-3-container, 
.bootstrap-3-container * {
    font-family: 'Lato', sans-serif !important;
}
  </style>

</head>

<body>
  @if($user->user_type != "admin")
  <script>
    window.location.href = "{{route('home')}}";
  </script>
  @endif
