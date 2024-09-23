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
							<div class="col-md-4 col-sm-4 col-6">
								<div class="form-group">
									<label class="form-label">Name of Arbitrator:</label>
									<input type="text" name="name_of_arbitrator" id="name_of_arbitrator" class="form-control">
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-6">
								<div class="form-group">
									<label class="form-label">Name of Advocate:</label>
									<input type="text" name="name_of_advocate" id="name_of_advocate" class="form-control">
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-6">
								<div class="form-group">
									<label class="form-label">DIAC Case Number:</label>
									<input type="text" name="diac_case_number" id="diac_case_number" class="form-control">
								</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group text-center">
									<button  class="btn btn-custom" id="filterBtn"><span class="fa fa-filter"> Filter</button>
									<button class="btn btn-reset" id="resetBtn"><span class="fa fa-refresh"></span> Reset</button>
								</div>
							</div>
						</div>
						<table id="feedback_tbl" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
							<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('feedback_token'); ?>">
							<thead>
								<tr>
									<th style="width:8%;">S. No.</th>
									<th>Name of Arbitrator</th>
									<th>DIAC Case No.</th>
									<th>Name of Advocate</th>
									<th>Enrolment Number of Advocate</th>
									<th>Phone</th>
									<th>Email</th>
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
	var feedbackDataTable = $('#feedback_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "all-feedbacks/get-datatable-data",
			type: 'POST',
			data: function(d) {
				d.csrf_trans_token =  $('#feedback_token').val();
				d.name_of_arbitrator = $('#name_of_arbitrator').val();
				d.name_of_advocate = $('#name_of_advocate').val();
				d.diac_case_number = $('#diac_case_number').val();
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
				data: 'diac_case_number'
			},
			{
				data: 'name_of_advocate'
			},
			{
				data: 'enroll_number_of_advocate'
			},
			{
				data: 'mobile'
			},
			{
				data: 'email'
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return '<a href="' + base_url + 'view-feedbacks/view?code=' + data.code + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>'
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

	$(document).ready(function() {
		$('#filterBtn').on('click', function() {
			var name_of_arbitrator =$("#name_of_arbitrator").val();
			var name_of_advocate = $('#name_of_advocate').val();
			var diac_case_number = $('#diac_case_number').val();
        	feedbackDataTable.ajax.reload();
        	console.log(name_of_arbitrator);
        	console.log(name_of_advocate);
        	console.log(diac_case_number);
	    });
	    $('#resetBtn').on('click', function() {
	        $('#name_of_arbitrator').val('');
	        $('#name_of_advocate').val('');
	        $('#diac_case_number').val('');
	        feedbackDataTable.ajax.reload();
	    });
	});
</script>