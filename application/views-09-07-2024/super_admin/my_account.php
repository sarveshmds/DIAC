<style>
	.small-box>.small-box-footer {
		position: relative;
		text-align: center;
		padding: 5px 0;
		color: #fff;
		color: Black;
		display: block;
		z-index: 10;
		background: #266ed4;
		text-decoration: none;
	}

	.small-box span {
		margin-left: 20px;
	}

	.small-box label {
		font-weight: bold;
	}

	.noHover {
		pointer-events: none;
	}

	.small-box .icon {
		-webkit-transition: all .3s linear;
		-o-transition: all .3s linear;
		transition: all .3s linear;
		position: absolute;
		top: 10px;
		right: 35px;
		z-index: 0;
		font-size: 100px;
		color: rgba(0, 0, 0, 0.15);
	}

	.page_background {
		margin: 0 auto;
		font-family: 'Roboto', sans-serif;
		height: 100%;
		background: #EEEEF4;
		font-weight: 100;
		user-select: none;
	}

	main {
		height: 100%;
		display: flex;
		margin: 0 20px;
		text-align: center;
		flex-direction: column;
		align-items: center;
		justify-content: center;


	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Profile
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Profile</li>
		</ol>
	</section>
	<section class="content">
		<div class="card card-body">
			<div class="row">
				<div class="col-md-4 col-xs-12">

					<!-- Profile Image -->
					<div class="box box-warning" style="border-left: 1px solid lightgrey !important; border-right: 1px solid lightgrey !important; border-bottom: 1px solid lightgrey !important;">
						<?php
						if (!empty($get_dept_details)) :
							foreach ($get_dept_details as $dept_details) :
						?>
								<div class="box-body box-profile">
									<img class="profile-user-img img-responsive img-circle" src="<?php echo base_url(); ?><?php echo $dept_details['user_logo']; ?>" alt="User profile picture">

									<h3 class="profile-username text-center"><?php echo $dept_details['user_display_name']; ?></h3>

									<p class="text-muted text-center"><?php echo $dept_details['primary_role'] ?></p>

									<p>
										<b>Email ID</b> <a class="pull-right"><?php echo $dept_details['email'] ?></a>
									</p>
									<p>
										<b>Phone No.</b> <a class="pull-right"><?php echo $dept_details['phone_number'] ?></a>
									</p>
									<p>
										<b>Address</b> <a class="pull-right"><?php echo $dept_details['address'] ?></a>
									</p>

									<button type="button" class="btn btn-primary btn-block" id="btn_dept_edit" data-user_code="<?php echo $dept_details['user_code']; ?>" data-user_name="<?php echo $dept_details['user_name']; ?>">Edit Details</button>
									<button type="button" class="btn btn-custom btn-block" id="btn_change_password" data-user_code="<?php echo $dept_details['user_code']; ?>" data-user_name="<?php echo $dept_details['user_name']; ?>">Change Password</button>
								</div>
								<!-- /.box-body -->
						<?php
							endforeach;
						endif;
						?>
					</div>
					<!-- /.box -->

				</div>

				<div class="col-md-8 col-xs-12">
					<div class="">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Information</a></li>
						</ul>
						<div class="tab-content" style="border: 1px solid lightgrey;">
							<div class="tab-pane active" id="settings" style="padding: 20px;">
								<?php
								if (!empty($get_dept_details)) :
									foreach ($get_dept_details as $dept_details) :
								?>
										<div class="">
											<div>
												<div><label>1.</label>&nbsp;<span>Password Should be
														between 8 to 25 characters</span></div>
												<div><label>2.</label>&nbsp;<span>It must contain atleast
														one uppercase letters(A-Z)</span></div>
												<div><label>3.</label>&nbsp;<span>one lower case
														letter(a-z)</span></div>
												<div><label>4.</label>&nbsp;<span>one numeric value(0-9)
														and one special character(Eg:-@,#,!)</span></div>
											</div>
										</div>
								<?php
									endforeach;
								endif;
								?>
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>
					<!-- /.nav-tabs-custom -->
				</div>

				<!--Department Modification Modal-->
				<div class="modal fade" id="dept_modification_modal" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
								<h4 class='modal-title' style='text-align: center;'>Profile Details</h4>
							</div>
							<div class='modal-body'>
								<section class="">
									<?php echo form_open(null, array('id' => 'frm_profile_modification', 'enctype' => "multipart/form-data")); ?>
									<div class='row'>
										<div id="errorlog_dept" style="display: none;color: red; font-size: 10px; text-align: center;font-weight: bold;">
										</div>
										<div class='demo timeline-block'>
											<input type="hidden" id="hid_user_code" name="hid_user_code" />
											<input type="hidden" name="csrf_profile_token" value="<?php echo generateToken('frm_profile_modification'); ?>">
											<div class="col-md-12">
												<div class="form-group col-md-6">
													<label>Profile Description :</label>
													<input style="width: 100%;" id="dept_desc" name="dept_desc" class="form-control" autocomplete="off"></input>
												</div>
												<div class="form-group col-md-6">
													<label for="ac_contact">Contact</label>
													<input type="text" id="txt_contact" name="txt_contact" class="form-control" placeholder="Enter 10 digit mobile number" autocomplete="off">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group col-md-6">
													<label for="ac_email">Email</label>
													<input type="text" id="txt_email" name="txt_email" class="form-control" placeholder="Eg:xxx@gmail.com" autocomplete="off">
												</div>
												<div class="form-group col-md-6">
													<label>Address :</label>
													<input type="text" id="txt_dept_address" name="txt_dept_address" class="form-control" autocomplete="off" />
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group col-md-12">
													<label for="ac_logo">Logo</label>
													<input type="file" class="form-control filestyle" name="txt_Logo" id="txt_Logo" value="" autocomplete="off">
												</div>
											</div>
											<div class="col-md-12" align="center">
												<input type="submit" class="btn btn-custom" style="width: 30%;margin-top: 10px" value="Submit">
											</div>
										</div>
									</div>
									<?php echo form_close(); ?>
								</section>
							</div>
						</div>
					</div>
				</div>
				<!--Change password Modal-->
				<div class="modal fade" id="change_password_modal" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
								<h4 class='modal-title' style='text-align: center;'>Change Password</h4>
							</div>
							<?php echo form_open(null, array('id' => 'frm_change_password', 'enctype' => "multipart/form-data")); ?>
							<div class='modal-body'>
								<div id="errorlog_cp" style="display: none;color: red; font-size: 14px; text-align: center;font-weight: bold;">
								</div>
								<div class=''>
									<input type="hidden" id="hid_user_code_cp" name="hid_user_code_cp" />
									<input type="hidden" id="hid_user_name" name="hid_user_name" />
									<input type="hidden" id="hid_password" name="hid_password" />
									<input type="hidden" id="hid_old_password" name="hid_old_password" />
									<input type="hidden" name="csrf_cp_token" value="<?php echo generateToken('frm_change_password'); ?>">
									<div class="form-group">
										<label for="old_password">Old Password</label>
										<input type="password" id="txt_old_password" name="txt_old_password" class="form-control" placeholder="enter your old password" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="new_password">New Password</label>
										<input type="password" id="txt_new_password" name="txt_new_password" class="form-control" placeholder="enter your new password" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="confrim_password">Confirm Password</label>
										<input type="password" id="txt_confrim_password" name="txt_confrim_password" class="form-control" placeholder="confirm password" autocomplete="off">
									</div>
									<div class="form-group" align="center">
										<input type="submit" class="btn btn-custom" style="width: 30%;margin-top: 10px" value="Submit">
									</div>
								</div>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>';
</script>
<script>
	role_code = "<?php echo $this->session->userdata('role') ?>";
</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/my_account.js"></script>