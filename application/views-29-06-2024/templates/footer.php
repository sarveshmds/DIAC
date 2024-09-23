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

<?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'COORDINATOR'])) : ?>
	<?php $pending_works_list = $this->work_status_model->get_pending_works_for_calender($this->session->userdata('user_code')); ?>
	<div id="pending_work_status_calender_modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title cause-list-modal-title" style="text-align: 	center;">Pending Work Status</h4>
				</div>
				<div class="modal-body">
					<!-- THE CALENDAR -->
					<div id="pending_work_calendar"></div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

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

<?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'COORDINATOR'])) : ?>
	<script>
		$(function() {
			var pending_work_status = '<?= json_encode($pending_works_list) ?>';
			pending_work_status = JSON.parse(pending_work_status);
			var pending_work_events = [];

			if (pending_work_status.length > 0) {
				pending_work_status.forEach((pws) => {
					pending_work_events.push({
						title: (pws.case_no) ? pws.case_no_prefix + '/' + pws.case_no + '/' + pws.case_no_year : 'N/A',
						start: new Date(pws.noting_date),
						end: (pws.next_date) ? new Date(pws.next_date) : '',
						backgroundColor: "#3c8dbc", //yellow
						borderColor: "#3c8dbc", //yellow
						allDay: true // This ensures the event is shown as an all-day event

					});
				})
			}

			/* initialize the external events
     -----------------------------------------------------------------*/
			function init_events(ele) {
				ele.each(function() {
					// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
					// it doesn't need to have a start or end
					var eventObject = {
						title: $.trim($(this).text()), // use the element's text as the event title
					};

					// store the Event Object in the DOM element so we can get to it later
					$(this).data("eventObject", eventObject);

					// make the event draggable using jQuery UI
					$(this).draggable({
						zIndex: 1070,
						revert: true, // will cause the event to go back to its
						revertDuration: 0, //  original position after the drag
					});
				});
			}

			init_events($("#external-events div.external-event"));

			/* initialize the calendar
     -----------------------------------------------------------------*/
			//Date for the calendar events (dummy data)
			var date = new Date();
			var d = date.getDate(),
				m = date.getMonth(),
				y = date.getFullYear();
			$("#pending_work_calendar").fullCalendar({
				header: {
					left: "prev,next today",
					center: "title",
					right: "month,agendaWeek,agendaDay",
				},
				buttonText: {
					today: "today",
					month: "month",
					week: "week",
					day: "day",
				},
				//Random default events
				events: pending_work_events,
				editable: true,
				droppable: true, // this allows things to be dropped onto the calendar !!!
				drop: function(date, allDay) {
					// this function is called when something is dropped

					// retrieve the dropped element's stored Event Object
					var originalEventObject = $(this).data("eventObject");

					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);

					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = allDay;
					copiedEventObject.backgroundColor = $(this).css("background-color");
					copiedEventObject.borderColor = $(this).css("border-color");

					// render the event on the calendar
					// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
					$("#calendar").fullCalendar("renderEvent", copiedEventObject, true);

					// is the "remove after drop" checkbox checked?
					if ($("#drop-remove").is(":checked")) {
						// if so, remove the element from the "Draggable Events" list
						$(this).remove();
					}
				},
			});

			/* ADDING EVENTS */
			var currColor = "#3c8dbc"; //Red by default
			//Color chooser button
			var colorChooser = $("#color-chooser-btn");
			$("#color-chooser > li > a").click(function(e) {
				e.preventDefault();
				//Save color
				currColor = $(this).css("color");
				//Add color effect to button
				$("#add-new-event").css({
					"background-color": currColor,
					"border-color": currColor,
				});
			});
			$("#add-new-event").click(function(e) {
				e.preventDefault();
				//Get value and make sure it is not null
				var val = $("#new-event").val();
				if (val.length == 0) {
					return;
				}

				//Create events
				var event = $("<div />");
				event
					.css({
						"background-color": currColor,
						"border-color": currColor,
						color: "#fff",
					})
					.addClass("external-event");
				event.html(val);
				$("#external-events").prepend(event);

				//Add draggable funtionality
				init_events(event);

				//Remove event from text input
				$("#new-event").val("");
			});
		});
	</script>
<?php endif; ?>

<script type="text/javascript" src="<?= base_url() ?>public/custom/js/diac_dashboard/diac-common-js.js?v=<?php echo filemtime(FCPATH . 'public/custom/js/diac_dashboard/diac-common-js.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url() ?>public/custom/js/file_tag_configuration.js?v=<?php echo filemtime(FCPATH . 'public/custom/js/file_tag_configuration.js'); ?>"></script>

</body>

</html>