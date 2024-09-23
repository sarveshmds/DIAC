 <div class="container-xl">
     <!-- Page title -->
     <div class="page-header d-print-none">
         <div class="row g-2 align-items-center">
             <div class="col d-flex align-items-center justify-content-between">
                 <h2 class="page-title">
                     <?= $page_title ?>
                 </h2>
             </div>
         </div>
     </div>
 </div>
 <div class="page-body">
     <div class="container-xl">
         <!-- Content here -->

         <form action="" id="file_new_case_verify_form" method="post">
             <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('file_new_case_verify_form'); ?>">
             <input type="hidden" name="hidden_code" value="<?= $new_reference['nr_code'] ?>">

             <?php require_once(APPPATH . 'views/templates/components/new_reference_view_details.php') ?>

             <div class="card mt-3">
                 <div class="card-body">
                     <div class="col-12 text-center mb-3">
                         <button type="submit" class="btn btn-success">
                             <i class="fa fa-save"></i> Proceed To Pay
                         </button>
                     </div>
                 </div>
             </div>
         </form>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         // $('#case_no_dropdown').trigger("change");

         $('#file_new_case_verify_form').validate({
             errorClass: 'is-invalid text-danger',
             rules: {},
             submitHandler: function(form, event) {
                 event.preventDefault();
                 var formData = new FormData(document.getElementById('file_new_case_verify_form'));
                 $.ajax({
                     url: base_url + 'efiling/new-reference/proceed-payment',
                     type: 'POST',
                     data: formData,
                     contentType: false,
                     processData: false,
                     cache: false,
                     success: function(response) {
                         var response = JSON.parse(response);
                         if (response.status) {
                             window.location.href = response.redirect_link;

                         } else if (response.status == 'validation_error') {
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Validation Error',
                                 text: response.msg
                             })
                         } else if (response.status == false) {
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Error',
                                 text: response.msg
                             })
                         } else {
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Error',
                                 text: 'Server Error'
                             })
                         }
                     },
                     error: function(response) {
                         Swal.fire({
                             icon: 'error',
                             title: 'Error',
                             text: 'Server Error, please try again'
                         })
                     }
                 });
             }
         })
     })
 </script>