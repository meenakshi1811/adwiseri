<!--- footer --->
@if(!isset($user))
<footer>
  <div class="container-fluid">

    <div class="row foot-row">
      <div class="col-md-4 col-lg-2  mt-4">
        <h4 class="fix">adwiseri</h4>
        <p class="fix">Our Support team is available <br>
        Monday - Friday, <br>
          10am - 6pm GMT</p>
      </div>
      <div class="col-md-4 col-lg-2 mt-0 mt-md-4">
        <ul>
          <li><a href="{{ route('terms_use') }}" style="text-decoration: none;color:white;">Terms of Use</a></li>
          <li><a href="{{ route('privacy_policy') }}" style="text-decoration: none;color:white;">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="col-md-4 col-lg-2 mt-0 mt-md-4">
        <ul>
          <li><a href="{{ route('terms_conditions') }}" style="text-decoration: none;color:white;">GDPR</a></li>
          <li><a href="{{ route('refund_policy') }}" style="text-decoration: none;color:white;">Cookie Notice</a></li>
        </ul>
      </div>
      <div class="col-md-4 col-lg-2 mt-0 mt-lg-4 mt-md-0">
        <ul>
          <li><a href="{{ route('contactus') }}" style="text-decoration: none;color:white;">Contact Us</a></li>
          <li><a href="{{ route('aboutadvisori') }}" style="text-decoration: none;color:white;">About Us</a></li>
        </ul>
      </div>
      <div class="col-md-4 col-lg-2 mt-0 mt-lg-4 mt-md-0">
        <ul>
          <li>Have a Question? Email Us</li>
          <li><i class="fa-regular fa-envelope"></i> &nbsp; care@adwiseri.com</li>
        </ul>
      </div>
      <div class="col-md-4 col-lg-2 mt-0 mt-lg-4 mt-md-0 social-icons">
        <li class="fix">
        {{-- <img src="{{ asset('web_assets/images/call.png') }}" width="25" height="25" alt=""> --}}
        Follow Us
        </li>
        <li class="fix"><img src="{{ asset('web_assets/images/fb.png') }}" width="25" height="25" alt="">&nbsp;
          <img src="{{ asset('web_assets/images/insta.png') }}" width="25" height="25" alt="">&nbsp;
          <img src="{{ asset('web_assets/images/twit.png') }}" width="25" height="25" alt="">&nbsp;
          <img src="{{ asset('web_assets/images/link.png') }}" width="25" height="25" alt="">&nbsp;
          <img src="{{ asset('web_assets/images/you.png') }}" width="25" height="25" alt=""></li>
      </div>
    </div>
    <div class="text-center mt-3">
        <p class="mb-0">&copy; {{ date('Y') }} adwiseri.&nbsp;All rights reserved </p>
      </div>
  </div>
</footer>
@else

<footer class="mt-2 last-footer">
    <p>&copy; {{ date('Y') }}  adwiseri.&nbsp;All rights reserved.</p>
  </footer>
  
@endif


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
      $("#manage_btn").click(function(){
          var managebox = $("#manage_box").css('display');
          if(managebox == 'flex'){
            $("#manage_box").css('display','none');
          }
          else{
            $("#manage_box").css('display','flex');
          }
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
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64b7f33dcc26a871b029690b/1h5n8s9sr';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>


<!-- Include DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--End of Tawk.to Script-->

@stack('other-scripts')


</body>

</html>
