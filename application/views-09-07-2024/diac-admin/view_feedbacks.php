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
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">Name of Arbitrator:</label>
								<p><?= $feedback_data['name_of_arbitrator'] ?></p></div>
							</div>
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">DIAC Case Number:</label>
								<p><?= $feedback_data['diac_case_number'] ?></p></div>
							</div>
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">Name of Advocate:</label>
								<p><?= $feedback_data['name_of_advocate'] ?></p></div>
							</div>
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">Enrollment Number of Advocate:</label>
								<p><?= $feedback_data['enroll_number_of_advocate'] ?></p></div>
							</div>
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">Mobile Number:</label>
								<p><?= $feedback_data['mobile'] ?></p></div>
							</div>
							<div class="col-md-4 col-sm-6 col-12 form-group">
								<div class="view-col">
								<label class="form-label">Email:</label>
								<p><?= $feedback_data['email'] ?></p></div>
							</div>
						</div>
						<fieldset class="fieldset">
							<legend class="legend">
								Feedbacks on Arbitrator's
							</legend>
							<div class="fieldset-content-box">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 form-group">
										<div class="view-col">
											<label class="form-label mb-0">Understanding of the dispute:</label>
											<p class="mb-0"><span class="badge"><?= $feedback_data['understanding_dispute']?></span></p>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 form-group">
										<div class="view-col">
											<label class="form-label mb-0">Management of the arbitration process:</label>
											<p class="mb-0"><span class="badge"><?= $feedback_data['manage_arb_process'] ?></span></p>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 form-group">
										<div class="view-col">
											<label class="form-label mb-0">Expertise in procedural and substantive law:</label>
											<p class="mb-0"><span class="badge"><?= $feedback_data['substantive_law'] ?></span></p>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 form-group">
										<div class="view-col">
											<label class="form-label mb-0">Equal Treatment of the parties:</label>
											<p class="mb-0"><span class="badge"><?= $feedback_data['treatment_of_party'] ?></span></p>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset class="fieldset">
							<legend class="legend">
								Feedback on DIAC
							</legend>
							<div class="fieldset-content-box">
								<div class="row" >
									<div class="col-md-6 form-group col-sm-6 col-12">
										<div class="view-col">
										<label class="form-label  mb-0">Ambience, cleanliness and overall upkeep of the Centre:</label>
										<p class=" mb-0"><span class="badge"><?= !$feedback_data['diac_ambience_clean'] == 0? $feedback_data['diac_ambience_clean']:'Did not Use' ?></span></p>
									</div>
									</div>
									<div class="col-md-6 form-group col-sm-6 col-12">
										<div class="view-col">
										<label class="form-label  mb-0">Assistance rendered by Deputy Counsels/Case Managers in case management:</label>
										<p class=" mb-0"><span class="badge"><?= !$feedback_data['diac_assist_dc_cm'] == 0? $feedback_data['diac_assist_dc_cm']:'Did not Use' ?></span></p>
									</div>
									</div>
									<div class="col-md-6 form-group col-sm-6 col-12">
										<div class="view-col">
										<label class="form-label mb-0">Assistance rendered by stenographers and other DIAC staff:</label>
										<p class=" mb-0"><span class="badge"><?= !$feedback_data['diac_assist_st_other'] == 0?$feedback_data['diac_assist_st_other']:'Did not Use' ?></span></p>
										</div>
									</div>
									<div class="col-md-6 form-group col-sm-6 col-12">
										<div class="view-col">
										<label class="form-label mb-0">Functioning of Information Technology infrastructure at the Centre:</label>
										<p class=" mb-0"><span class="badge"><?= !$feedback_data['diac_it_infra'] == 0?$feedback_data['diac_it_infra']:'Did not Use' ?></span></p>
									</div>
								</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>