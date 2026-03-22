<?php
date_default_timezone_set("Asia/Kolkata");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Adwiseri</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('web_assets/css/style.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap"
    rel="stylesheet"> -->
  <!-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" /> --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <script src="https://kit.fontawesome.com/b140011afa.js" crossorigin="anonymous"></script>
  <!-- Owl Carousel CSS -->

  <link rel="stylesheet" href="{{ asset('web_assets/css/owl.carousel.css') }}">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-core.min.js"></script>
      <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-pie.min.js"></script>
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
            body {
                font-family: 'Lato', sans-serif!important;
            }
        </style>
  <style>
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
      body{
          background: #F5F5F5;
          /* font-family: 'Lato'; */
      }
      .star-rating {
    display: flex;
    cursor: pointer;
}

.star {
    font-size: 30px;
    color: gray;
    margin-right: 5px;
}
 /* Reset Bootstrap 3 styles globally */
 /* body:not(.bootstrap-3-container) {
        all: initial;
    } */

    /* Allow Bootstrap styles inside the container */
    /* .bootstrap-3-container * {
        all: unset;
        all: revert;
    } */
     .form-control,
     .form-control:focus,
     select.form-control {
        font-family: 'Lato'  !important;
     }  
  </style>



</head>

<body>

    @if(auth()->user() && auth()->user()->user_type == 'Subscriber')


    <div id="feedbackModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header d-block">
                    <h5 class="modal-title text-center fw-bold">We Value Your Feedback</h5>
                </div>

                <!-- Feedback Form -->
                <form id="feedbackForm">
                    <div class="modal-body">
                        <input type="hidden" name="subscriber_id" id="subscriberId">

                        <!-- Star Rating -->
                        <div class="form-group d-flex align-items-center">
                            <label for="rating" class="mb-0">Rating :</label> &nbsp; &nbsp;
                            <div id="star-rating" class="star-rating d-flex ml-4">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                        </div>

                        <!-- Feedback Textarea -->
                        <div class="form-group mt-4">
                            <label style="margin-bottom:5px;" for="feedback">Feedback</label>
                            <textarea
    name="feedback"
    id="feedback"
    class="form-control"
    placeholder="Enter your feedback (max 400 characters)"
    maxlength="400"
    rows="4"
    required></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
