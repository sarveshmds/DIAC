<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<div class="row">
							<div class="col-md-2 col-sm-5 col-12">
								<div class="intern_profile_col">
									<label for="">Profile Photo</label>
									<img src="<?= base_url() . INTERN_PROFILE_PICTURE . $intern_data['profile_photo'] ?>" alt="" class="intern_profile_photo">
								</div>
								<div class="intern_signature_col">
									<label for="">Signature Photo</label>
									<img src="<?= base_url() . INTERN_SIGNATURE_PICTURE . $intern_data['signature'] ?>" alt="" class="intern_signature_photo">
								</div>
							</div>
							<div class="col-md-9 col-sm-7 col-12">
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Name of Applicant: </b>
										<p><?= $intern_data['name_of_applicant'] ?></p>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Date of Birth: </b>
										<p><?= $intern_data['date_of_birth'] ?></p>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Name of the Institute/College: </b>
										<p><?= $intern_data['name_of_college_institute'] ?></p>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Programme Duration: </b>
										<p><?= $intern_data['programme_duration'] ?></p>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Semester Pursuing: </b>
										<p><?= $intern_data['semester_pursuing'] ?></p>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 form-group">
										<b>Preferred Period of Internship: </b>
										<p class="mb-0"><b>From:</b> <?= $intern_data['pref_period_internship_from'] ?></p>
										<p class="mb-0"><b>To: </b> <?= $intern_data['pref_period_internship_to'] ?></p>
									</div>

									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Father's Name:</b>
											<p><?= $intern_data['father_name'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Mother's Name:</b>
											<p><?= $intern_data['mother_name'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Email:</b>
											<p><?= $intern_data['email'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Mobile Number:</b>
											<p><?= $intern_data['mobile'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Gender:</b>
											<p><?= $intern_data['gender'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Permanent Address:</b>
											<p><?= $intern_data['permanent_address'] ?></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-6 col-xs-12 ">
										<div class="mb-3">
											<b>Address During Internship:</b>
											<p><?= $intern_data['address_during_internship'] ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<fieldset class="fieldset">
								<legend class="legend">
									Details of Institution
								</legend>
								<div class="fieldset-content-box">
									<div class="row">
										<div class="col-md-4 col-sm-6 col-12 form-group">
											<div class="view-col">
												<label class="form-label mb-0">Address:</label>
												<p class="mb-0"><?= $intern_data['institute_address'] ?></p>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-12 form-group">
											<div class="view-col">
												<label class="form-label mb-0">Contact Person of Institution:</label>
												<p class="mb-0"><?= $intern_data['contact_person_of_institute'] ?></p>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-12 form-group">
											<div class="view-col">
												<label class="form-label mb-0">Contact Person Number of Institution:</label>
												<p class="mb-0"><?= $intern_data['contact_person_no_of_institute'] ?></p>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="row">
							<fieldset class="fieldset">
								<legend class="legend">
									Documents of Applicant
								</legend>
								<div class="fieldset-content-box">
									<div class="row">
										<div class="col-md-4 col-sm-6 col-12">
											<div class="view-col">
												<label class="form-label">CV:</label>
												<p>
													<a href="<?= base_url() . INTERN_CV . $intern_data['cv'] ?>" class="btn btn-danger btn-sm" target="_BLANK">View File</a>
												</p>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-12 ">
											<div class="view-col">
												<label class="form-label">Cover Letter/Statement of Interest:</label>
												<p>
													<a href="<?= base_url() . INTERN_COVER_LETTER . $intern_data['cover_letter'] ?>" class="btn btn-danger btn-sm" target="_BLANK">View File</a>
												</p>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="view-col">
												<label class="form-label">Letter of Reference:</label>
												<p>
													<a href="<?= base_url() . INTERN_REFERENCE_LETTER . $intern_data['reference_letter'] ?>" class="btn btn-danger btn-sm" target="_BLANK">View File</a>
												</p>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>