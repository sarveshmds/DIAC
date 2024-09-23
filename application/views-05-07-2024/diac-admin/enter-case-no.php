<div class="content-wrapper">

	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>

	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h4 class="box-title">
							Select DIAC Registration No. to proceed <?= ($page_title) ? ' to ' . $page_title : '' ?>:
						</h4>
					</div>
					<div class="box-body">
						<div class="col-md-6 col-xs-12 mt-10">
							<?= form_open(null, array('class' => 'wfst', 'id' => 'ecn_form')) ?>
							<input type="hidden" name="csrf_case_form_token" id="ecn_case_form" value="<?php echo generateToken('case_ecn_form'); ?>">

							<input type="hidden" name="ecn_type" id="ecn_type" value="<?= $type ?>">

							<input type="hidden" name="op_type" id="ecn_op_type" value="CHECK_CASE_NUMBER">

							<div class="form-group">
								<select name="ecn_case_no" id="ecn_case_no" class="form-control select2">
									<option value="">DIAC Reg. No./Case Title</option>
									<?php foreach ($case_lists as $case) : ?>
										<option value="<?= $case['slug'] ?>"><?= '* ' . $case['case_no_prefix'] . '/' . $case['case_no'] . '/' . $case['case_no_year'] . ' (' . $case['case_title'] . ')' ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group text-center">
								<button type="submit" id="ecn_btn_submit" class="btn btn-custom"><span class="fa fa-paper-plane-o"></span> Proceed</button>
							</div>
							<?= form_close() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script type="text/javascript">
	// Function to enable the submit button
	function enable_submit_btn(button) {
		$('#' + button).attr('disabled', false);
	}

	// Add case number form
	$('#ecn_form').bootstrapValidator({
		message: 'This value is not valid',
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) {
			$('#award_term_btn_submit').attr('disabled', 'disabled');
			var formData = new FormData(document.getElementById('ecn_form'));
			var urls = base_url + "service/check_case_number";

			$.ajax({
				url: urls,
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				complete: function() {
					enable_submit_btn('ecn_btn_submit');
				},
				success: function(response) {
					try {
						var obj = JSON.parse(response);
						if (obj.status == false) {
							swal({
								title: 'Error',
								text: obj.msg,
								type: 'error',
								html: true
							});
						} else if (obj.status === 'validationerror') {
							swal({
								title: 'Validation Error',
								text: obj.msg,
								type: 'error',
								html: true
							});
						} else {
							window.location.href = obj.redirect_url;
						}
					} catch (e) {
						sweetAlert("Sorry", 'Unable to Save.Please Try Again !', "error");
					}
				},
				error: function(err) {
					toastr.error("Something went wrong. Please try again.");
				}
			});
		}
	})
</script>