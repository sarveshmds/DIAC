<div class="container-xl">
	<!-- Page title -->
	<div class="page-header d-print-none">
		<div class="row g-2 align-items-center">
			<div class="col d-flex align-items-center justify-content-between">
				<h2 class="page-title">
					<?= $page_title ?>
				</h2>
				<a href="<?= base_url('efiling/application') ?>" class="btn btn-secondary btn-sm">
					<i class="fa fa-arrow-left"></i> Go Back
				</a>
			</div>
		</div>
	</div>
</div>
<div class="page-body">
	<div class="container-xl">
		<!-- Content here -->

		<div class="card">
			<div class="card-body">
				<form action="" id="frm" class="frm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm'); ?>">
					<input type="hidden" name="hidden_id" value="<?= (isset($application['id']) ? $application['id'] : '') ?>">
					<input type="hidden" name="op_type" id="op_type" value="<?= (isset($application['id']) ? 'EDIT' : 'ADD') ?>">
					<div class="row">

						<div class="col-md-4 col-sm-6 col-12 mb-3">
							<label class="form-label">Case No.</label>
							<select name="case_no" id="case_no" class="form-control">
								<option value="">Select</option>
								<?php foreach ($cases as $case) : ?>
									<option value="<?php echo $case['slug'] ?>" <?= (isset($application['case_no']) && $application['case_no'] == $case['slug']) ? 'selected' : '' ?>><?php echo $case['case_no_desc'] . ' (' . $case['case_title'] . ')' ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-md-4 col-sm-6 col-12 mb-3">
							<label class="form-label">Filing Type</label>
							<select name="filing_type" id="filing_type" class="form-control">
								<option value="">Select</option>
								<?php foreach ($filing_types as $key => $ft) : ?>
									<option value="<?= $ft['gen_code'] ?>" <?php echo (isset($application['filing_type']) && $application['filing_type'] == $ft['gen_code']) ? 'selected' : '' ?>><?= $ft['description'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-md-4 col-sm-6 col-12 mb-3">
							<label class="form-label">Application Type</label>
							<select name="app_type" id="app_type" class="form-control">
								<option value="">Select</option>
								<?php foreach ($application_types as $key => $at) : ?>
									<option value="<?= $at['gen_code'] ?>" <?= (isset($application['app_type']) && $application['app_type'] == $at['gen_code']) ? 'selected' : '' ?>><?= $at['description'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-12 mb-3">
							<fieldset class="fieldset">
								<legend class="legend">On Behalf Of (Parties)</legend>
								<div class="fieldset-content-col">
									<div class="row">
										<div class="col-md-4 col-sm-6 col-12 mb-3">
											<label class="form-label">On Behalf of</label>
											<select name="behalf" id="behalf" class="form-control">
												<option value="">Select</option>
												<?php foreach ($behalf_of as $behalf) : ?>
													<option value="<?= $behalf['gen_code'] ?>" <?= (isset($application['behalf']) && $application['behalf'] == $behalf['gen_code']) ? 'selected' : ''; ?>><?= $behalf['description'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-md-4 col-sm-6 col-12 mb-3 claimant_col" <?= (isset($application['behalf']) && $application['behalf'] == 'CLAIMANT') ? '' : 'style="display: none;"'; ?>>
											<label class="form-label">Claimant Name</label>
											<select name="claimant" id="claimant" class="form-control">
												<option value="">Select</option>
												<?php if (isset($application['behalf'])) : ?>
													<?php foreach ($claimant_respondents as $claimant) : ?>
														<?php if ($claimant['type'] == 'claimant') : ?>
															<option value="<?= $claimant['id'] ?>" <?= (isset($application['claimant_respondent_id']) && $application['claimant_respondent_id'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
										<div class="col-md-4 col-sm-6 col-12 mb-3 respondent_col" <?= (isset($application['behalf']) && $application['behalf'] == 'RESPONDENT') ? '' : 'style="display: none;"'; ?>>
											<label class="form-label">Respondant Name</label>
											<select name="respondent" id="respondant" class="form-control">
												<option value="">Select</option>
												<?php if (isset($application['behalf'])) : ?>
													<?php foreach ($claimant_respondents as $claimant) : ?>
														<?php if ($claimant['type'] == 'respondant') : ?>
															<option value="<?= $claimant['id'] ?>" <?= (isset($application['claimant_respondent_id']) && $application['claimant_respondent_id'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="col-md-4 col-sm-6 col-12 mb-3">
							<label class="form-label">Upload Document</label>
							<input type="file" name="document" id="document" class="form-control" accept=".pdf" />
							<small class="text-muted text-xs">
								Only .pdf files are allowed. Max 25 MB
							</small>
						</div>

						<div class="col-md-4 col-sm-6 col-12 mb-3">
							<label class="form-label">Remarks</label>
							<textarea name="remarks" id="remarks" class="form-control"><?= (isset($application['remarks']) ? $application['remarks'] : '') ?></textarea>
						</div>

						<div class="col-12 text-center mb-3">
							<button type="submit" class="btn btn-custom">
								<i class="fa fa-save"></i> <?= (isset($application['id']) ? 'Update Details' : 'Submit Details') ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('#behalf').on('change', function() {
		$('.claimant_col').hide();
		$('.respondent_col').hide();

		if ($(this).val() == 'CLAIMANT') {
			$('.claimant_col').show();
		}
		if ($(this).val() == 'RESPONDENT') {
			$('.respondent_col').show();
		}
	})

	$('#case_no').on('change', function() {
		var case_no = $(this).val();
		if (case_no) {
			$.ajax({
				url: base_url + 'get-claimant-respondent-separately',
				data: {
					case_no: $(this).val()
				},
				type: 'post',
				success: function(response) {
					console.log(response)
					var response = JSON.parse(response);
					var claimant = response.claimants;
					var respodent = response.respondents;

					var claimantOption = '<option value="">Select</option>';
					$.each(claimant, function(index, cl) {
						claimantOption += '<option value="' + cl.id + '">' + cl.name + '</option>'
					})
					var respodentOption = "<option value=''>Select</option>";
					$.each(respodent, function(index, rs) {
						respodentOption += '<option value="' + rs.id + '">' + rs.name + '</option>'
					})

					$('#claimant').html(claimantOption);
					$('#respondant').html(respodentOption);

					// On changing case no, check behalf is already selected or not
					if ($('#behalf').val() == 'claimant') {
						$('.claimant_col').show();
					}
					if ($('#behalf').val() == 'respondent') {
						$('.respondent_col').show();
					}

				}

			})
		}
	})


	$(document).ready(function() {
		$('#frm').validate({
			errorClass: 'is-invalid text-danger',
			rules: {
				case_no: {
					required: true
				},
				behalf: {
					required: true
				},
				filing_type: {
					required: true
				},
				app_type: {
					required: true
				},
				messages: {
					case_no: 'Please select case no.',
					behalf: 'Please select on behalf of.',
					claimant: 'Please select claimant.',
					respondent: 'Please select respondent.',
					filing_type: 'Please select filing type.',
					app_type: 'Please select application type.'
				}

			},
			submitHandler: function(form, event) {
				event.preventDefault();
				var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/application/update' : base_url + 'efiling/application/store ';
				$.ajax({
					url: url,
					data: new FormData(document.getElementById('frm')),
					type: 'POST',
					contentType: false,
					processData: false,
					cache: false,
					success: function(response) {
						var response = JSON.parse(response);
						if (response.status == true) {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								text: response.msg
							}).then((result) => {
								if (result.value) {
									window.location.href = base_url + 'efiling/application';
								}
							})

						} else if (response.status == 'validation_error') {
							Swal.fire({
								icon: 'error',
								title: 'Validation Error',
								html: response.msg
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