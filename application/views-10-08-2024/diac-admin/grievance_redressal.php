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
						<table id="grievance_tbl" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
							<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('grievance_token'); ?>">
							<thead>
								<tr>
									<th style="width:8%;">S. No.</th>
									<th>Name of Arbitrator</th>
									<th>DIAC Case No.</th>
									<th>Form filled by</th>
									<th>Phone</th>
									<th>Email</th>
									<th>Participated in arbitration as a counsel or party</th>
									<th style="width:15%;">Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";

	// Timepicker initialization
	$('.timepicker').timepicker({
		showInputs: false
	});

	// Datatable initialization

	// datatable for approved arbitrator
	var arbitrator_setup_datatable = $('#grievance_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "all-grievance/get-datatable-data",
			type: 'POST',
			data: {
				csrf_trans_token: $('#grievance_token').val()
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'name_of_arbitrator'
			},
			{
				data: 'diac_case_no'
			},
			{
				data: 'name_of_form_filled_by'
			},
			{
				data: 'phone'
			},
			{
				data: 'email'
			},
			{
				data: 'whether_participated_in_arbitration',
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return (data == 1) ? 'Party' : 'Counsel'
				}
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return '<a href="' + base_url + 'all-grievance/view?code=' + data.code + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>'
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
		drawCallback: function() {
			$('body').tooltip({
				selector: '[data-tooltip="tooltip"]'
			});
		}
	});
</script>