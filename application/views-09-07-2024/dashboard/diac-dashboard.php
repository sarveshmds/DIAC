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
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/diac-common-js.js') ?>"></script>
<script>
	/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE START */
	history.pushState(null, null, document.URL);
	window.addEventListener("popstate", function() {
		history.pushState(null, null, document.URL);
	});
	/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */

	$(document).ready(function() {

		// Common Datatable for all tables
		$("#db_amount_datatable").dataTable({
			sorting: false,
			ordering: false,
			responsive: false,
			searching: false,
			lengthChange: false,
		});

	})
</script>