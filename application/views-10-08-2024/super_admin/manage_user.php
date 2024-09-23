<div class="content-wrapper" id="root">
	<section class="content-header">
		<h1>Manage User</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
			<li class="active"><a href="#"><i class="fa fa-server"></i>Manage User</a></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="table-responsive">
							<table id="user_table" class="table table-condensed table-striped table-bordered  display nowrap">
								<input type="hidden" id="csrf_user_token" name="csrf_user_token" value="<?php echo generateToken('user_table'); ?>">
								<thead>
									<tr>
										<th> # </th>
										<th hidden>User Code</th>
										<th style="width: 17%;">Action</th>
										<th>Record Status</th>
										<th>User Name</th>
										<th>DIAC Serial No.</th>
										<th>Display Name</th>
										<th>Role</th>
										<th>Case Ref. No.</th>
										<th>Email Id</th>
										<th>Contact No</th>
										<th>Job Title</th>
										<th>State</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="manage_user_modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
							<h4 class='modal-title' style='text-align: center;'><span id="spanuser">Add User</span></h4>
						</div>
						<?php echo form_open(null, array('id' => 'frm_user', 'enctype' => "multipart/form-data")); ?>
						<div class='modal-body' style="height: auto">
							<div class="box-body">
								<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
								<input type="hidden" id="op_type" name="op_type" value="add_user">
								<input type="hidden" id="hiduser_code" name="hiduser_code">
								<input type="hidden" name="csrf_add_user_token" value="<?php echo generateToken('frm_user'); ?>">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group col-md-6">
											<label>User Name : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="txtUserName" id="txtUserName" placeholder="User Name" value="" required="" autocomplete="off">
											<input type="hidden" id="hiduser_name" name="hiduser_name" value="">
										</div>
										<div class="form-group col-md-6">
											<label>Display Name : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="txtDisplayName" id="txtDisplayName" placeholder="Display Name" value="" required="" autocomplete="off">
										</div>

										<div class="form-group col-md-6">
											<label>DIAC Serial No. : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="diac_serial_no" id="diac_serial_no" placeholder="Job Title" value="" autocomplete="off">
											<small class="text-muted">
												Ex: CM01, DC01, AC01 etc.
											</small>
										</div>

										<div class="form-group col-md-6">
											<label>Designation : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="txtJobTitle" id="txtJobTitle" placeholder="Job Title" value="" autocomplete="off">
										</div>

										<div class="form-group col-md-6">
											<label>State: <span class="text-danger">*</span></label>
											<select class="form-control" id="cmb_state_code" name="cmb_state_code">
												<option value="">select</option>
												<?php foreach ($all_state_list as $key) : ?>
													<option value="<?php echo $key['state_code']; ?>"><?php echo $key['state_name']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>

										<div class="form-group col-md-6">
											<label>Email Id : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="txtEmailId" id="txtEmailId" placeholder="Email Id" value="" required="" autocomplete="off">
										</div>

										<div class="form-group col-md-6">
											<label>Contact Number : <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="txtPhoneNumber" id="txtPhoneNumber" placeholder="Mobile Number" value="" required="" autocomplete="off">
										</div>

										<div class="form-group col-md-6">
											<label>User Role : <span class="text-danger">*</span></label>
											<select class="form-control" id="cmb_user_role" name="cmb_user_role">
												<option value="">Select Role</option>
												<?php foreach ($all_role_data as $role) : ?>
													<option value="<?php echo $role['role_code']; ?>"><?php echo $role['role_name']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>

										<div class="form-group col-md-6" id="dc_cm_case_ref_no" style="display: none;">
											<label>Case Reference No.: <span class="text-danger">*</span></label>
											<select class="form-control" id="case_ref_no" name="case_ref_no">
												<option value="">Select</option>
											</select>
										</div>

										<div class="form-group col-md-6">
											<label>Status : <span class="text-danger">*</span></label>
											<select class="form-control" id="user_status" name="user_status">
												<option value="">select</option>
												<?php foreach ($this->config->item('status') as $key => $status) : ?>
													<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="box-footer with-border">
										<div class="box-tools pull-right">
											<button type="submit" class="btn btn-orange btn-flat" id="btn_submit"><i class='fa fa-paper-plane'></i> Add</button>
											<button type="button" class="btn btn-default" id="button_reset" onclick="form_reset();"><i class="fa fa-refresh"></i> Reset</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	base_url = "<?php echo base_url(); ?>";
</script>

<script>
	function get_case_ref_no(role, selected_value = '') {
		$.ajax({
			url: base_url + 'case-ref-no-setup/get-list',
			type: 'POST',
			data: {
				role: role
			},
			success: function(response) {
				var data = JSON.parse(response);
				var option = '<option>Select </option>';
				var selected = '';

				$.each(data, function(index, row) {
					selected = (row.code == selected_value) ? 'selected' : ''
					option += '<option value="' + row.code + '" ' + selected + '>' + row.start_index + '-' + row.end_index + '</option>'
				})

				$('#case_ref_no').html(option)
			},
			error: function(error) {
				toastr.error(error);
			}
		})
	}
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/manage_user.js"></script>

<script>
	$(document).on('change', '#cmb_user_role', function() {
		var role = $(this).val();
		$('#dc_cm_case_ref_no').hide();
		if (role == 'DEPUTY_COUNSEL' || role == 'CASE_MANAGER' || role == 'COORDINATOR') {
			$('#dc_cm_case_ref_no').show();
			get_case_ref_no(role, '')
		}
	})
</script>