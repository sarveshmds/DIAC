<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<div>
							<table id="dataTableNotificationsList" class="table table-condensed table-striped table-bordered" data-page-size="10">
								<input type="hidden" id="csrf_notifications_trans_token" value="<?php echo generateToken('dataTableNotificationsList'); ?>">

								<thead>
									<tr>
										<th width="5%"></th>
										<th style="width:7%;">S. No.</th>
										<th>Notifications</th>
										<th style="width:15%;" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div>
							<button type="button" class="btn btn-custom" id="btn_delete_selected_notification"><i class="fa fa-trash"></i> Delete Selected</button>
							<button type="button" class="btn btn-danger" id="btn_delete_all_notification" style="margin-left: 2px;"><i class="fa fa-trash"></i> Delete All</button>
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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/notifications.js') ?>"></script>