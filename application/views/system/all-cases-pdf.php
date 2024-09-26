<div class="content-wrapper">
	<div class="content">
		<div class="content-heading text-center">
			<h4 class="content-title">
				All Cases List
			</h4>
		</div>

		<div class="content-body">
			<table class="table table-bordered table-text-center" cellspacing="0">
				<thead>
					<tr>
						<th>S. No</th>
						<th>Case No.</th>
						<th>Case Title</th>
						<th>Referred  On</th>
						<th>Referred  By</th>
						<th>Received On</th>
						<th>Registered On</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php $serialNo = 1; ?>
					<?php foreach ($allRegisteredCasesList as $rcl) : ?>
						<tr>
							<td><?= $serialNo ?></td>
							<td><?= $rcl['case_no_desc'] ?></td>
							<td><?= $rcl['case_title'] ?></td>
							<td><?= $rcl['reffered_on'] ?></td>
							<td><?= $rcl['reffered_by_desc'] ?></td>
							<td><?= $rcl['recieved_on'] ?></td>
							<td><?= $rcl['registered_on'] ?></td>
							<td><?= $rcl['case_status_dec'] ?></td>
						</tr>

						<?php $serialNo++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>