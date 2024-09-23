<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<section class="content-header">

		<h1 style="display: inline-block;"><?php echo (isset($edit_form) && $edit_form == true) ? '<span class="fa fa-edit"></span> ' : '<span class="fa fa-plus"></span> '; ?><?= $page_title ?></h1>

		<a href="Javascript:void(0)" onclick="history.back()" class="btn btn-default pull-right"><span class="fa fa-backward"></span> Go Back</a>

	</section>
	<section class="content">
		<div class="box wrapper-box">
			<div class="box-body">
				<?= form_open_multipart(null, array('class' => 'wfst', 'id' => 'case_det_form')) ?>

				<input type="hidden" id="op_type" name="op_type" value="ADD_CASE_DETAILS">
				<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('case_det_form'); ?>">

				<div class="row">

					<div class="form-group col-md-3 col-xs-12 required">
						<label class="control-label">Case No. Prefix:</label>
						<input type="text" class="form-control uppercase" id="cd_case_no_prefix" name="cd_case_no_prefix" autocomplete="off" value="DIAC" />
					</div>

					<div class="form-group col-md-3 col-xs-12 required">
						<label class="control-label">Case No.:</label>
						<input type="text" class="form-control uppercase" id="cd_case_no" name="cd_case_no" autocomplete="off" value="" />
					</div>

					<div class="form-group col-md-3 col-xs-12 required">
						<label class="control-label">Case No. Year:</label>
						<input type="text" class="form-control uppercase" id="cd_case_no_year" name="cd_case_no_year" autocomplete="off" value="" />
					</div>

					<div class="form-group col-md-12 col-xs-12 required">
						<label class="control-label">Case Title:</label>
						<input type="text" class="form-control uppercase" id="cd_case_title" name="cd_case_title" autocomplete="off" value="" />
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
						<label for="" class="control-label">Mode of reference:</label>
						<select name="cd_reffered_by" id="cd_reffered_by" class="form-control">

							<option value="">Select Option</option>
							<?php foreach ($reffered_by as $rb) : ?>
								<option value="<?= $rb['gen_code'] ?>"><?= $rb['description'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12">
						<label class="control-label">Date of reference:</label>
						<div class="input-group date">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control custom-all-previous-dates" id="cd_reffered_on" name="cd_reffered_on" autocomplete="off" readonly="true" value="<?= date('d-m-Y') ?>" />
						</div>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" style="display: none;">
						<label for="" class="control-label">Court:</label>

						<select name="cd_reffered_by_court" id="cd_reffered_by_court" class="form-control">
							<option value="">Select Option</option>
							<?php foreach ($courts_list as $court) : ?>
								<option value="<?= $court['code'] ?>"><?= $court['court_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" style="display: none;">
						<label for="" class="control-label">Name of Judges:</label>
						<input type="text" name="cd_reffered_by_judge" id="cd_reffered_by_judge" class="form-control uppercase" value="">
						<small class="text-danger">
							Enter comma (,) separated names
						</small>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" style="display: none;">
						<label for="" class="control-label">Name of Authority:</label>
						<input type="text" name="cd_name_of_court" id="cd_name_of_court" class="form-control uppercase" value="" maxlength="250">
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" style="display: none;">
						<label for="" class="control-label">Case Type:</label>

						<select name="cd_reffered_by_court_case_type" id="cd_reffered_by_court_case_type" class="form-control">
							<option value="">Select Option</option>
							<?php foreach ($case_types as $ct) : ?>
								<option value="<?= $ct['type_code'] ?>"><?= $ct['type_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12">
						<label class="control-label">Reference No.:</label>
						<input type="text" class="form-control" id="cd_case_arb_pet" name="cd_case_arb_pet" autocomplete="off" maxlength="100" value="" />
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" style="display: none;">
						<label for="" class="control-label">Year:</label>

						<?php
						$year = date('Y');
						$endYear = 1920;
						?>
						<select name="cd_reffered_by_court_case_year" id="cd_reffered_by_court_case_year" class="form-control">
							<option value="">Select Option</option>
							<?php while ($year >= $endYear) : ?>
								<option value="<?= $year ?>"><?= $year-- ?></option>
							<?php endwhile; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12">
						<label class="control-label">Ref. Received On:</label>
						<div class="input-group date">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control custom-all-date" id="cd_recieved_on" name="cd_recieved_on" autocomplete="off" readonly="true" value="<?= date('d-m-Y') ?>" />
						</div>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12">
						<label class="control-label">Date of registration:</label>
						<div class="input-group date">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control custom-all-previous-dates" id="cd_registered_on" name="cd_registered_on" autocomplete="off" readonly="true" value="<?= date('d-m-Y') ?>" />
						</div>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
						<label for="" class="control-label">Nature of arbitration:</label>
						<select name="cd_toa" id="cd_toa" class="form-control">
							<option value="">Select Option</option>
							<?php foreach ($type_of_arbitration as $toa) : ?>
								<option value="<?= $toa['gen_code'] ?>"><?= $toa['description'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
						<label for="" class="control-label">Type of Arbitration:</label>
						<select name="cd_di_toa" id="cd_di_toa" class="form-control">
							<option value="">Select Option</option>
							<?php foreach ($di_type_of_arbitration as $toa) : ?>
								<option value="<?= $toa['gen_code'] ?>"><?= $toa['description'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
						<label for="" class="control-label">Arbitrator Status:</label>
						<select name="cd_arbitrator_status" id="cd_arbitrator_status" class="form-control">
							<option value="">Select Option</option>
							<?php foreach ($arbitrator_status as $toa) : ?>
								<option value="<?= $toa['gen_code'] ?>"><?= $toa['description'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group col-md-3 col-sm-6 col-xs-12 arbitral_tribunal_stength_col required" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == 1) ? 'selected' : 'style="display: none;"' ?>>
						<label for="" class="control-label">Arbitral Tribunal Strength:</label>
						<select name="cd_arbitral_tribunal_strength" id="cd_arbitral_tribunal_strength" class="form-control">
							<option value="">Select Option</option>
							<option value="1">Sole</option>
							<option value="3">3</option>
							<option value="5">5</option>
						</select>
					</div>


					<div class="col-xs-12"></div>

					<div class="form-group col-md-6 col-xs-12">
						<label class="control-label">Supporting Documents:</label>
						<input type="file" class="form-control documents-input" id="refferal_req_documents" name="refferal_req_documents[]" autocomplete="off" multiple accept=".pdf, .docx, .doc" value="" multiple />
					</div>

					<div class="form-group col-md-6 col-xs-12 ">
						<label class="control-label">Remarks (Optional):</label>
						<textarea class="form-control" id="refferal_req_message" name="refferal_req_message" autocomplete="off" rows="4"></textarea>
					</div>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="submit" class="btn btn-custom" id="btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<!-- Modals Start -->



<!-- Modals End -->
<!-- ================================================================================ -->

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script>
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";

	// Timepicker initialization
	$('.timepicker').timepicker({
		Inputs: false
	});
</script>

<script>
	/**
	 * =======================================================
	 */

	// On changing the reffered by jugde
	$("#cd_reffered_by").on("change", function() {
		var value = $(this).val();
		$(".reffered_by_court_details").hide();
		$(".reffered-other-name-col").hide();
		console.log(value);
		if (value == "COURT") {
			$(".reffered_by_court_details").show();
		}
		if (value == "OTHER") {
			$(".reffered-other-name-col").show();
		}
	});

	// On changing the type of arbitration
	$("#cd_toa").on("change", function() {
		var value = $(this).val();
		if (value) {
			$(".di-type-of-arb-col").show();
		} else {
			$(".di-type-of-arb-col").hide();
		}
	});

	$("#cd_arbitrator_status").on("change", function() {
		let cd_arbitrator_status = $(this).val();
		$(".arbitral_tribunal_stength_col").hide();
		$("#btn_arbitrator_nav_tab").hide();
		$("#tab_4").hide();

		if (cd_arbitrator_status == 1) {
			$(".arbitral_tribunal_stength_col").show();
			$("#btn_arbitrator_nav_tab").show();
			$("#tab_4").show();
		}
	});

	$("#cd_arbitrator_is_empanelled").on("change", function() {
		let cd_arbitrator_is_empanelled = $(this).val();
		$(".emp_arbitrator_list_col").hide();
		$(".not_empanelled_arb_wrapper").hide();

		if (cd_arbitrator_is_empanelled == 1) {
			$(".emp_arbitrator_list_col").show();
		}
		if (cd_arbitrator_is_empanelled == 2) {
			$(".not_empanelled_arb_wrapper").show();
		}
	});

	// ==========================================================================================
	// FOrm Submission
	// ==========================================================================================
	$("#case_det_form").bootstrapValidator({
		message: "This value is not valid",
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) {
			$("#btn_submit").attr("disabled", "disabled");
			var formData = new FormData(document.getElementById("case_det_form"));

			$.ajax({
				url: base_url + '/store-case-details',
				method: "POST",
				data: formData,
				contentType: false,
				processData: false,
				cache: false,
				success: function(response) {
					// Enable the submit button
					enable_submit_btn("btn_submit");
					try {
						var obj = JSON.parse(response);
						if (obj.status == true) {
							swal({
									title: "Success",
									text: obj.msg,
									type: "success",
									html: true,
								},
								function() {
									window.location.reload();
								}
							);
						} else if (obj.status === "validationerror") {
							swal({
								title: "Validation Error",
								text: obj.msg,
								type: "error",
								html: true,
							});
						} else {
							swal({
								title: "Error",
								text: obj.msg,
								type: "error",
								html: true,
							});
						}
					} catch (e) {
						sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
					}
				},
				error: function(err) {
					// Enable the submit button
					enable_submit_btn("btn_submit");
					toastr.error("unable to save");
				},
			});
		},
		fields: {},
	});
</script>