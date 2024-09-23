<footer class="main-footer text-center">
	<strong>Copyright &copy; DIAC <?php echo date('Y'); ?> <a href="#" target="_blank"></a> <span>All rights reserved; Delhi International Arbitration Center.</span></strong>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Create the tabs -->
	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li><a href="#put-on-tab" data-toggle="tab"><i class="fa fa-clock-o"></i> Todays Reminders</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<!-- Home tab content -->
		<div class="tab-pane active" id="put-on-tab">
			<div class="text-center">
				<button class="btn btn-custom btn-sm" id="reminderModalBtn"><i class="fa fa-plus"></i> Add Reminder</button>
			</div>
			<hr>
			<ul class="control-sidebar-menu">
				<?php $reminders = getTodaysReminders(); ?>
				<?php if (count($reminders) > 0) : ?>
					<?php foreach ($reminders as $reminder) : ?>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									<?= $reminder['note'] ?>
								</h4>
								<small class="text-muted"><?= $reminder['date'] ?></small>
							</a>
						</li>
					<?php endforeach; ?>
				<?php else : ?>
					<li>
						<a href="javascript:void(0)">
							<h4 class="control-sidebar-subheading">
								No reminder found
							</h4>
						</a>
					</li>
				<?php endif;  ?>

			</ul>
			<!-- /.control-sidebar-menu -->
		</div>
		<!-- /.tab-pane -->

	</div>
</aside>

<!-- Csrf token -->
<input type="hidden" name="csrf_token_universal" id="csrf_token_universal" value="<?php echo generateToken('CSRF_UNIVERSAL_TOKEN'); ?>">
</div>

<!-- Common Modal Start -->
<!-- Common Modal to show the same structered data -->
<div id="common-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title common-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body common-modal-body"></div>
		</div>
	</div>
</div>
<!-- Common Modal End -->

<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/reminder.php'); ?>

<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
</script>
<script type="text/javascript" src="<?= base_url() ?>public/custom/js/diac_dashboard/diac-common-js.js?v=<?php echo filemtime(FCPATH.'public/custom/js/diac_dashboard/diac-common-js.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url() ?>public/custom/js/file_tag_configuration.js?v=<?php echo filemtime(FCPATH.'public/custom/js/file_tag_configuration.js'); ?>"></script>

</body>

</html>