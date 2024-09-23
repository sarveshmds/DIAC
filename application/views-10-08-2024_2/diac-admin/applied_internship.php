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
						<table id="applied_intern_tbl" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
							<input type="hidden" name="csrf_trans_token" id="" value="<?php echo generateToken('applied_intern_token'); ?>">
							<thead>
								<tr>
									<th style="width:8%;">S. No.</th>
									<th>Name of Applicant:</th>
									<th>College/Institute Name:</th>
									<th>Email:</th>
									<th>Mobile:</th>
									<th>Programme Duration:</th>
									<th>Semester Pursuing:</th>
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
	var feedbackDataTable = $('#applied_intern_tbl').DataTable({
		"processing": true,
		"serverSide": true,
		"autoWidth": false,
		"responsive": true,
		"order": [],
		"ajax": {
			url: base_url + "applied-internship/getDatatableData",
			type: 'POST',
			data: function(d) {
				d.csrf_trans_token =  $('#applied_intern_token').val();
				// d.name_of_arbitrator = $('#name_of_arbitrator').val();
				// d.name_of_advocate = $('#name_of_advocate').val();
				// d.diac_case_number = $('#diac_case_number').val();
			}
		},
		"columns": [{
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: 'name_of_applicant'
			},
			{
				data: 'name_of_college_institute'
			},
			{
				data: 'email'
			},
			{
				data: 'mobile'
			},
			{
				data: 'programme_duration'
			},
			{
				data: 'semester_pursuing'
			},
			{
				data: null,
				"sWidth": "10%",
				"sClass": "alignCenter",
				"render": function(data, type, row, meta) {
					return '<a href="' + base_url + 'applied-internship/view?code=' + data.code + '" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>'
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