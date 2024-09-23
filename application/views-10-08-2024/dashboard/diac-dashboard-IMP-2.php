<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Dashboard
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<section class="content">

		<div class="row mb-15">

			<div class="col-lg-8 col-xs-12">
				<div class="row">
					<?php if (in_array($this->session->userdata('role'), ALLOTMENT_ROLES)) : ?>
						<div class="col-md-4 col-sm-6 col-12">
							<div class="small-box bg-custom">
								<div class="inner">
									<h3><?= count($alloted_cases) ?></h3>
									<p>Alloted Cases Count</p>
								</div>
								<div class="icon">
									<i class="fa fa-bank"></i>
								</div>
								<a href="<?= base_url('allotted-case') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

					<?php endif; ?>

					<div class="col-md-4 col-sm-6 col-12">
						<div class="small-box bg-custom">
							<div class="inner">
								<h3><?= $dashboard_data['total_case_count'] ?></h3>
								<p>Registered Cases</p>
							</div>
							<div class="icon">
								<i class="fa fa-book"></i>
							</div>
							<a href="<?= base_url('all-cases') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6 col-12">
						<div class="small-box bg-custom">
							<div class="inner">
								<h3><?= $dashboard_data['total_hearings_today'] ?></h3>
								<p>Today's Hearing</p>
							</div>
							<div class="icon">
								<i class="fa fa-bank"></i>
							</div>
							<a href="<?= base_url('hearings-today') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'COORDINATOR'])) : ?>
						<div class="col-md-4 col-sm-6 col-12">
							<div class="small-box bg-custom">
								<div class="inner">
									<h3><?= count($pending_works_list) ?></h3>
									<p>Pending Work Count</p>
								</div>
								<div class="icon">
									<i class="fa fa-sticky-note-o"></i>
								</div>
								<a href="<?= base_url('work-status/pending') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					<?php endif; ?>

					<div class="col-md-4 col-sm-6 col-12">
						<div class="small-box bg-custom">
							<div class="inner">
								<h3><?= $dashboard_data['total_rooms'] ?></h3>
								<p>Total Rooms</p>
							</div>
							<div class="icon">
								<i class="fa fa-building"></i>
							</div>
							<?php if (in_array($this->session->userdata('role'), ['CAUSE_LIST_MANAGER', 'DIAC', 'ADMIN'])) : ?>
								<a href="<?= base_url('rooms-list') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>

							<?php else : ?>
								<span style="height: 26px;" class="small-box-footer"></span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- <div class="row mb-15">
					<div class="col-md-6 col-xs-12">
						<div class="box box-warning">
							<div class="box-header">
								<h4 class="box-title">
									<i class="fa fa-area-chart"></i> Nature of Arbiration Analysis
								</h4>
							</div>
							<div class="box-body">
								<div id="dc_type_of_arb_chart" style="height: 300px;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-12">
						<div class="box box-warning">
							<div class="box-header">
								<h4 class="box-title">
									<i class="fa fa-area-chart"></i> Type of Arbiration Analysis
								</h4>
							</div>
							<div class="box-body">
								<div id="dc_case_types_chart" style="height: 300px;"></div>
							</div>
						</div>
					</div>
				</div> -->


				<div class="box box-warning custom-box dashboard-box">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Monthwise Cases (<?= ($this->input->get('year')) ? base64_decode($this->input->get('year')) : date('Y') ?>)</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
							</button>
						</div>

					</div>

					<div class="box-body">
						<div class="monthwise-cases-search-col text-center">
							<select name="mcs_year" id="mcs_year" class="form-control">
								<option value="">Select Year</option>
								<?php foreach ($dashboard_data['yearWiseData'] as $year) : ?>
									<option value="<?= $year['registered_year'] ?>" <?php echo ($current_year == $year['registered_year']) ? 'selected' : ''; ?>><?= $year['registered_year'] ?></option>
								<?php endforeach; ?>
							</select>
							<button class="btn btn-custom" id="monthWiseFilterBtn"><i class="fa fa-filter"></i></button>
						</div>
						<div id="bar-chart" style="height: 300px;"></div>
					</div>

				</div>

			</div>
			<div class="col-lg-4 col-xs-12">
				<div class="row">

					<div class="col-xs-12">
						<div class="box box-warning custom-box dashboard-box">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-bell-o"></i> Latest Notifications</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
									</button>
								</div>

							</div>

							<div class="box-body">
								<ul class="db-notificaiton-list">
								</ul>
							</div>


						</div>
					</div>

					<!-- Yearwise Data -->
					<div class="col-xs-12">

						<div class="box box-warning custom-box dashboard-box">
							<div class="box-header with-border">
								<h4 class="box-title"><i class="fa fa-calendar-o"></i> Yearwise Cases</h4>
							</div>
							<div class="box-body">
								<table class="table table-bordered no-margin">
									<tbody>
										<?php if (count($dashboard_data['yearWiseData']) > 0) : ?>
											<?php foreach ($dashboard_data['yearWiseData'] as $year) : ?>
												<tr>
													<td>
														<strong><?= 'Year: ' . $year['registered_year'] ?></strong>
													</td>
													<td class="text-center"><span class="badge <?php echo ($year['cnt'] == 0) ? 'badge-primary' : 'badge-success'  ?>"><?= $year['cnt'] ?></span></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="2">No records found</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
	var monthwise_data = '<?php echo json_encode($dashboard_data['monthWiseData']); ?>';
	monthwise_data = JSON.parse(monthwise_data);
	var new_monthwise_data = monthwise_data.map(function(data) {
		return [data.month_name, data.cnt];
	})
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/diac-common-js.js') ?>"></script>
<script>
	/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE START */
	history.pushState(null, null, document.URL);
	window.addEventListener("popstate", function() {
		history.pushState(null, null, document.URL);
	});
	/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */

	// OnClick Monthwise Filter button
	$('#monthWiseFilterBtn').on('click', function() {
		var year = $('#mcs_year').val();
		window.location.href = base_url + "diac-admin-dashboard?year=" + btoa(year);
	})

	$(document).ready(function() {
		/*
		 * BAR CHART
		 * ---------
		 */

		var bar_data = {
			data: new_monthwise_data,
			color: '#f39c12'
		}
		$.plot('#bar-chart', [bar_data], {
			grid: {
				borderWidth: 1,
				borderColor: '#f3f3f3',
				tickColor: '#f3f3f3'
			},
			series: {
				bars: {
					show: true,
					barWidth: 0.5,
					align: 'center'
				}
			},
			xaxis: {
				mode: 'categories',
				tickLength: 0
			}
		})

		/* END BAR CHART */

		// =========================================
		// =========================================
		/*
		 * DONUT CHART: TYPE IF ARBITRATION
		 * -----------
		 */

		var donutData = [{
				label: 'Domestic',
				data: '<?= (isset($toa_data['domestic'])) ? $toa_data['domestic'] : 0 ?>',
				color: '#00b74a'
			},
			{
				label: 'International',
				data: '<?= (isset($toa_data['international'])) ? $toa_data['international'] : 0 ?>',
				color: '#F4951F'
			},
		]
		$.plot('#dc_type_of_arb_chart', donutData, {
			series: {
				pie: {
					show: true,
					radius: 1,
					innerRadius: 0.5,
					label: {
						show: true,
						radius: 2 / 3,
						formatter: labelFormatter,
						threshold: 0.1
					}

				}
			},
			legend: {
				show: false
			}
		})
		/*
		 * END DONUT CHART
		 */

		// =========================================
		// =========================================

		// =========================================
		// =========================================
		/*
		 * DONUT CHART: TYPE IF ARBITRATION
		 * -----------
		 */

		var donutData = [{
				label: 'General',
				data: '<?= (isset($toc_data['general'])) ? $toc_data['general'] : 0 ?>',
				color: '#00b74a'
			},
			{
				label: 'Emergency',
				data: '<?= (isset($toc_data['emergency'])) ? $toc_data['emergency'] : 0 ?>',
				color: '#EB1212'
			},
			{
				label: 'Fastrack',
				data: '<?= (isset($toc_data['fasttrack'])) ? $toc_data['fasttrack'] : 0 ?>',
				color: '#092958'
			},
		]
		$.plot('#dc_case_types_chart', donutData, {
			series: {
				pie: {
					show: true,
					radius: 1,
					innerRadius: 0.5,
					label: {
						show: true,
						radius: 2 / 3,
						formatter: labelFormatter,
						threshold: 0.1
					}

				}
			},
			legend: {
				show: false
			}
		})
		/*
		 * END DONUT CHART
		 */

		// =========================================
		// =========================================


		// Common Datatable for all tables
		$("#db_amount_datatable").dataTable({
			sorting: false,
			ordering: false,
			responsive: false,
			searching: false,
			lengthChange: false,
		});

	})

	/*
	 * Custom Label formatter
	 * ----------------------
	 */
	function labelFormatter(label, series) {
		return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
			label +
			'<br>' +
			Math.round(series.percent) + '%</div>'
	}
</script>