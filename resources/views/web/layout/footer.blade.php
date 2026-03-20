<!--- footer --->
@guest
<footer class="text-light py-4">
    <div class="container">
      <div class="row align-items-start">
        <!-- Logo and Address -->
        <div class="col-md-4 mb-4">
          <!-- <h4 class="fw-bold">adwiseri</h4> -->
          <h4 class="fw-bold"><img src="{{ asset('web_assets/images/style2.png') }}" width="40" alt=""></h4>

          <p>Our Support team is available:</p>
          <p>Monday - Friday,<br> 10am - 6pm GMT</p>
          <p>
            <strong>Email Us:</strong><br>
            {{-- <i class="fas fa-envelope"></i>  --}}
              {{ $contact->email }}
          </p>
          {{-- <p>
            <strong>Contact No:</strong><br>
            <i class="fas fa-phone"></i>   {{ $contact->contact_no }}
          </p> --}}
        </div>

        <!-- Quick Links -->
        <div class="col-md-2 mb-4">
          <ul class="list-unstyled">
            <li><a href="{{ route('/') }}" class="text-light text-decoration-none">Home</a></li>
            <li><a href="{{ route('aboutadvisori') }}" class="text-light text-decoration-none">About Us</a></li>
            <li><a href="{{ route('contactus') }}" class="text-light text-decoration-none">Contact Us</a></li>

            <li><a href="{{ route('features') }}" class="text-light text-decoration-none">Features</a></li>
            <li><a href="{{ route('membership') }}" class="text-light text-decoration-none">Pricing</a></li>

          </ul>
        </div>

        <!-- About Us Links -->
        <div class="col-md-2 mb-4">
          <ul class="list-unstyled">
            <li >
                <a  class="text-light text-decoration-none" href="{{ route('affiliate.createLogin')}}">Affiliates</a>
              </li>
            <li><a href="{{ route('terms_use') }}" class="text-light text-decoration-none">Terms of Use</a></li>
            <li><a href="{{ route('privacy_policy') }}" class="text-light text-decoration-none">Privacy Policy</a></li>
            <li><a href="{{ route('terms_conditions') }}" class="text-light text-decoration-none">GDPR</a></li>
            <li><a href="{{ route('refund_policy') }}" class="text-light text-decoration-none">Cookie Notice</a></li>
        </ul>
        </div>

        <!-- Social Links -->
        <div class="col-md-4 mb-4 text-center socail-media">
          <h5>Follow Us</h5>
          <div class="d-flex justify-content-center gap-3">
            <a href="#" class="text-light"><img src="{{ asset('web_assets/images/fb.png') }}" width="30" height="30" alt="Facebook"></a>
            <a href="#" class="text-light"><img src="{{ asset('web_assets/images/insta.png') }}" width="30" height="30" alt="Instagram"></a>
            <a href="#" class="text-light"><img src="{{ asset('web_assets/images/twit.png') }}" width="30" height="30" alt="Twitter"></a>
            <a href="#" class="text-light"><img src="{{ asset('web_assets/images/link.png') }}" width="30" height="30" alt="LinkedIn"></a>
            <a href="#" class="text-light"><img src="{{ asset('web_assets/images/you.png') }}" width="30" height="30" alt="YouTube"></a>
          </div>
          <div class="address mt-8">

            {{-- <p class="mb-1">Cityville, State, 12345</p> --}}
            {{-- <p>Country</p> --}}
        </div>
        </div>
      </div>

      <!-- Bottom Footer -->
      <div class="text-center mt-3">
        <p class="mb-0">&copy; {{ date('Y') }} adwiseri.&nbsp;All rights reserved.</p>
      </div>
    </div>
  </footer>
@endguest

@auth

<footer class="mt-2 last-footer">
    <p>&copy; {{ date('Y') }}  adwiseri.&nbsp;All rights reserved.</p>
  </footer>
  
@endauth


{{-- <footer class="mt-2 last-footer mb-0">
  <p>&copy; {{ date('Y') }} |  adwiseri &nbsp;&nbsp;|&nbsp;&nbsp;   </p>
</footer> --}}

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('web_assets/js/owl.carousel.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
  var local_time = new Date();
  console.log(local_time.toString());
  $(document).ready(() => {
      $('#subscriberTable').DataTable({"aaSorting": []});
      $('#clientTable').DataTable({"aaSorting": []});
      $('#userTable').DataTable({"aaSorting": []});
      $('#usersTable').DataTable({"aaSorting": []});
      // $('.dataTables_length').css('text-align','left');
      $(".localtime").attr("value",local_time.toString());
      $("#subscription-plan").owlCarousel({
    items: 4,
    margin: 15,
    loop: true,
    nav: true,
    dots: true,
    autoplay: true,
    autoplayTimeout: 3000,
    // smartSpeed: 5000, // Smooth sliding
    responsive: {
        0: { items: 1 },
        576: { items: 2 },
        768: { items: 3 },
        992: { items: 4 }
    }
    });
    $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                @php
                    $prevslide = asset('web_assets/images/navbtn.png');
                    $nextslide = asset('web_assets/images/nextnav.png');
                @endphp
                navText: [
                    "<div class='nav-btn prev-slide' style='background: url({{ $prevslide }}) no-repeat;'></div>",
                    "<div class='nav-btn next-slide' style='background: url({{ $nextslide }}) no-repeat;'></div>"
                ],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 3
                    }
                }
            })


    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dateInputs = document.querySelectorAll('input.date, input.datepicker, input[type="date"]');
    dateInputs.forEach((input) => {
      if (input.dataset.calendarInit === '1') return;
      const minDate = input.getAttribute('min') || null;
      const maxDate = input.getAttribute('max') || null;
      const currentValue = input.value || null;

      if (input.type === 'date') {
        input.type = 'text';
      }

      flatpickr(input, {
        dateFormat: "d-m-Y",
        defaultDate: currentValue,
        allowInput: true,
        clickOpens: true,
        disableMobile: true,
        minDate: minDate,
        maxDate: maxDate
      });

      input.dataset.calendarInit = '1';
    });
  });
</script>
<style>
  @media screen and (max-width: 767px){
    .dataTables_filter{text-align: left;}
    .client-row{padding:5px;}
  }
</style>

<script>

  // var timezone_offset_minutes = new Date().getTimezoneOffset();
  // timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

  // Timezone difference in minutes such as 330 or -360 or 0
  // console.log(timezone_offset_minutes);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
crossorigin="anonymous"></script>


<!--Start of Tawk.to Script-->

<script type="text/javascript">
// var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
// (function(){
// var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
// s1.async=true;
// s1.src='https://embed.tawk.to/64b7f33dcc26a871b029690b/1h5n8s9sr';
// s1.charset='UTF-8';
// s1.setAttribute('crossorigin','*');
// s0.parentNode.insertBefore(s1,s0);
// })();





// </script>
 <script>
    $(document).ready(function () {
         // Initialize the rating value
    let ratingValue = 0;

// Handle star click event
$('#star-rating .star').on('click', function() {
    // Get the value of the clicked star
    ratingValue = $(this).data('value');

    // Set the rating in the hidden input field
    $('#rating').val(ratingValue);

    // Update star styles (to highlight selected stars)
    $('#star-rating .star').each(function() {
        if ($(this).data('value') <= ratingValue) {
            $(this).css('color', 'gold'); // Highlight stars up to the selected one
        } else {
            $(this).css('color', 'gray'); // Reset remaining stars
        }
    });
});
        $.ajax({
        url: '/get-feedback-popup',
        method: 'GET',
        success: function (subscriber) {
            console.log(subscriber.show_popup);
            if (subscriber.show_popup == true) {
                $('#subscriberId').val(subscriber.id);
                $('#feedbackModal').modal('show');
            }
        },
        error: function () {
            console.error('Error fetching feedback popup data.');
        }
    });


    $('#feedbackForm').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: '/store-feedback',
            method: 'POST',
            data: $(this).serialize(),
            beforeSend: function (xhr) {
                // Add CSRF token to the request headers
                var token = $('meta[name="csrf-token"]').attr('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            },
            success: function (response) {
                $('#feedbackModal').modal('hide');
                Swal.fire('Thank You!', response.message, 'success');
            },
            error: function (errors) {
                Swal.fire('Oops!', errors.responseJSON.errors.rating[0], 'error');
            }
        });
    });
});

  </script>


<!--End of Tawk.to Script-->

</body>

</html>
