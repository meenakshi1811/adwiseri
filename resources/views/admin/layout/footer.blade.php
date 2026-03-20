

  <footer class="mt-2 last-footer">
    <p>&copy; {{ date('Y') }}  adwiseri.&nbsp;All rights reserved.</p>
  </footer>
  {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script> --}}

  {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script> --}}
 <!-- Select2 JS -->
 {{-- {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <script src="https://kit.fontawesome.com/b140011afa.js" crossorigin="anonymous"></script>

  {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-core.min.js"></script>

 <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-pie.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>




  <script>
    $(document).ready(() => {
        $('#subscriberTable').DataTable({"aaSorting": []});
        $('#clientTable').DataTable({"aaSorting": []});
        $('#userTable').DataTable({"aaSorting": []});
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


@stack('scripts')



</body>

</html>
