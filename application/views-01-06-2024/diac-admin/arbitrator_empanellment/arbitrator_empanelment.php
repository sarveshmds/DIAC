<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Submitted Applications</a></li>
						<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Initiated Applications</a></li>
						<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Approved Applications</a></li>
						<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Reject Applications</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="box wrapper-box">
								<div class="box-body">
									<div class="row">
										<div class="col-md-3 col-sm-6 col-12">
											<div class="form-group">
												<label for="Name">Name:</label>
												<input type="text" name="arb_emp_name" id="subm_arb_emp_name" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6 col-12">
											<div class="form-group">
												<label for="phone">Phone Number:</label>
												<input type="text" name="arb_emp_phone" id="subm_arb_emp_phone" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6 col-12">
											<div class="form-group">
												<label for="phone">Email Id:</label>
												<input type="text" name="arb_emp_email" id="subm_arb_emp_email" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6 col-12">
											<div class="form-group">
												<label for="category">Category:</label>
												<select name="arb_emp_category" id="subm_arb_emp_category" class="form-control">
													<option value="">Select</option>
													<?php foreach ($panel_category as $pc) : ?>
														<option value="<?= $pc['category_code'] ?>" <?= isset($arb_personal_info['empanellment_category']) && $pc['category_code'] == $arb_personal_info['empanellment_category'] ? 'selected' : '' ?>><?= $pc['category_name'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="col-xs-12">
											<div class="form-group text-center">
												<button class="btn btn-custom" id="btnSubmittedFilter"><span class="fa fa-filter"> </span> Filter</button>
												<button class="btn btn-reset" id="btnResetSubmitted"><span class="fa fa-refresh"></span> Reset</button>
											</div>
										</div>
									</div>

									<table id="arb_emp_tbl" class="table table-condensed table-striped table-bordered dt-responsive w-100" data-page-size="10">
										<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('arb_emp_token'); ?>">
										<thead>
											<tr>
												<th style="width:8%;">S. No.</th>
												<th>Diary No.</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone </th>
												<th>Category</th>
												<th>Application Status</th>
												<th>Created On</th>
												<th style="width:15%;">Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_2">
							<div class="box wrapper-box">
								<div class="box-body">
									<div class="callout callout-warning">
										<h4>Important Note:</h4>
										<p>These are the initiated applications which user is trying to submit.</p>
									</div>
									<table id="arb_emp_initiated_tbl" class="table table-condensed table-striped table-bordered dt-responsive w-100" data-page-size="10">
										<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('arb_emp_token'); ?>">
										<thead>
											<tr>
												<th style="width:8%;">S. No.</th>
												<th>Diary No.</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone </th>
												<th>Category</th>
												<th>Application Status</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>


						<div class="tab-pane" id="tab_3">
							<div class="box wrapper-box">
								<div class="box-body">
									<table id="arb_emp_approved_tbl" class="table table-condensed table-striped table-bordered dt-responsive w-100" data-page-size="10">
										<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('arb_emp_token'); ?>">
										<thead>
											<tr>
												<th style="width:8%;">S. No.</th>
												<th>Diary No.</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone </th>
												<th>Category</th>
												<th>Application Status</th>
												<th style="width:15%;">Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_4">
							<div class="box wrapper-box">
								<div class="box-body">
									<table id="arb_emp_rejected_tbl" class="table table-condensed table-striped table-bordered dt-responsive w-100" data-page-size="10">
										<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('arb_emp_token'); ?>">
										<thead>
											<tr>
												<th style="width:8%;">S. No.</th>
												<th>Diary No.</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone </th>
												<th>Category</th>
												<th>Application Status</th>
												<th style="width:15%;">Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>



					</div>

				</div>

			</div>
		</div>
	</section>
</div>

<script>
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";

	/**
	 * Datatable for approved arbitrator
	 */
	var arbitrator_setup_datatable = $('#arb_emp_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "arbitrator-empanelment/get-datatable-data",
			type: 'POST',
			data: function(d) {
				d.csrf_trans_token = $('#arb_emp_token').val();
				d.arb_emp_name = $('#subm_arb_emp_name').val();
				d.arb_emp_phone = $('#subm_arb_emp_phone').val();
				d.arb_emp_email = $('#subm_arb_emp_email').val();
				d.arb_emp_category = $('#subm_arb_emp_category').val();
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'diary_number'
			},
			{
				data: 'full_name'
			},
			{
				data: 'email_id'
			},
			{
				data: 'phone_number'
			},
			{
				data: 'category'
			},
			{
				data: 'approved',
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return (data == 0) ? '<span class="badge bg-blue">Pending</span>' : ''
				}
			},
			{
				data: 'created_at'
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return '<a href="' + base_url + 'arbitrator-empanelment/view?id=' + data.id + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a> <a href="' + base_url + 'arbitrator-empanelment/export/pdf?id=' + data.id + '" class="btn btn-info btn-sm" data-tooltip="tooltip" title="View Details" target="_BLANK"><span class="fa fa-file"></span></a>'
				}
			}

		],
		"columnDefs": [{
			"targets": [0, 2],
			"orderable": false,
			"sorting": false
		}],
		dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [{
			text: '<span class="fa fa-plus"></span> Add',
			className: 'btn btn-custom',
			init: function(api, node, config) {
				$(node).removeClass('dt-button');
			},
			action: function(e, dt, node, config) {
				add_panel_category_list_modal_open();
			}
		}],
		drawCallback: function() {
			$('body').tooltip({
				selector: '[data-tooltip="tooltip"]'
			});
		}
	});

	/**
	 * Datatable for initiated arbitrator
	 */
	var arbitrator_inititated_datatable = $('#arb_emp_initiated_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "arbitrator-empanelment/initiated/get-datatable-data",
			type: 'POST',
			data: {
				csrf_trans_token: $('#arb_emp_token').val()
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'diary_number'
			},
			{
				data: 'full_name'
			},
			{
				data: 'email_id'
			},
			{
				data: 'phone_number'
			},
			{
				data: 'category'
			},
			{
				data: 'approved',
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return ''
				}
			}

		],
		"columnDefs": [{
			"targets": [0, 2],
			"orderable": false,
			"sorting": false
		}],
		dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [{
			text: '<span class="fa fa-plus"></span> Add',
			className: 'btn btn-custom',
			init: function(api, node, config) {
				$(node).removeClass('dt-button');
			},
			action: function(e, dt, node, config) {
				add_panel_category_list_modal_open();
			}
		}],
		drawCallback: function() {
			$('body').tooltip({
				selector: '[data-tooltip="tooltip"]'
			});
		}
	});

	/* ------- Datatable for Approved Application  ------- */
	var arbitrator_approved_datatable = $('#arb_emp_approved_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "arbitrator-empanelment/approved/get-datatable-data",
			type: 'POST',
			data: {
				csrf_trans_token: $('#arb_emp_token').val()
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'diary_number'
			},
			{
				data: 'full_name'
			},
			{
				data: 'email_id'
			},
			{
				data: 'phone_number'
			},
			{
				data: 'category'
			},
			{
				data: 'approved',
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return (data == 1) ? '<span class="badge bg-green">Approved</span>' : ''
				}
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return '<a href="' + base_url + 'arbitrator-empanelment/view?id=' + data.id + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a> <a href="' + base_url + 'arbitrator-empanelment/export/pdf?id=' + data.id + '" class="btn btn-info btn-sm" data-tooltip="tooltip" title="View Details" target="_BLANK"><span class="fa fa-file"></span></a>'
				}
			}

		],
		"columnDefs": [{
			"targets": [0, 2],
			"orderable": false,
			"sorting": false
		}],
		dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [{
			text: '<span class="fa fa-plus"></span> Add',
			className: 'btn btn-custom',
			init: function(api, node, config) {
				$(node).removeClass('dt-button');
			},
			action: function(e, dt, node, config) {
				add_panel_category_list_modal_open();
			}
		}],
		drawCallback: function() {
			$('body').tooltip({
				selector: '[data-tooltip="tooltip"]'
			});
		}
	});


	/* ---------  Datatable for Rejected Applications  ---------- */

	var arbitrator_rejected_datatable = $('#arb_emp_rejected_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "arbitrator-empanelment/rejected/get-datatable-data",
			type: 'POST',
			data: {
				csrf_trans_token: $('#arb_emp_token').val()
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'diary_number'
			},
			{
				data: 'full_name'
			},
			{
				data: 'email_id'
			},
			{
				data: 'phone_number'
			},
			{
				data: 'category'
			},
			{
				data: 'approved',
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return (data == 2) ? '<span class="badge bg-red">Rejected</span>' : ''
				}
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return `<a href="' + base_url + 'arbitrator-empanelment/view?id=' + data.id + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>  
					<a href="#" class="btn btn-primary btn-sm" title="Download PDF"><span class="fa fa-download"></span></a>
					`
				}
			}

		],
		"columnDefs": [{
			"targets": [0, 2],
			"orderable": false,
			"sorting": false
		}],
		dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [{
			text: '<span class="fa fa-plus"></span> Add',
			className: 'btn btn-custom',
			init: function(api, node, config) {
				$(node).removeClass('dt-button');
			},
			action: function(e, dt, node, config) {
				add_panel_category_list_modal_open();
			}
		}],
		drawCallback: function() {
			$('body').tooltip({
				selector: '[data-tooltip="tooltip"]'
			});
		}
	});

	/* filters for submitted applications */
	$(document).ready(function() {
		$('#btnSubmittedFilter').on('click', function() {
			arbitrator_setup_datatable.ajax.reload();
		});
		$('#btnResetSubmitted').on('click', function() {
			$('#subm_arb_emp_name').val('');
			$('#subm_arb_emp_phone').val('');
			$('#subm_arb_emp_email').val('');
			$('#subm_arb_emp_category option').prop('selected', false);
			arbitrator_setup_datatable.ajax.reload();
		});
	});
</script>