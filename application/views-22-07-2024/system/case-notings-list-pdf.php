<div class="content-wrapper">
	<div class="content">
		<div class="content-heading text-center">
			<h4 class="content-title">
				Notings - <?= $caseDetails['case_no'] ?>
			</h4>
			<h4 class="content-subtitle">
				<?= $caseDetails['case_title'] ?>
			</h4>
		</div>

		<div class="content-body">
			<table class="table table-bordered table-text-center" cellspacing="0">
				<thead>
					<tr>
						<th style="width:7%;">S. No.</th>
						<th style="width: 40%">Noting</th>
						<th>Marked by</th>
						<th>Marked to/File sent</th>
						<th>Date of noting</th>
						<th>Next Date</th>
					</tr>
				</thead>
				<tbody>
					<?php $serialNo = 1; ?>
					<?php foreach ($caseNotingsList as $cnl) : ?>
						<tr>
							<td><?= $serialNo ?></td>
							<td style="text-align: left;">
								<?= $cnl['noting'] ?>
								<?= $cnl['noting_text'] ?>
							</td>
							<td><?= $cnl['marked_by_user'] . ' (' . $cnl['marked_by_job_title'] . ')' ?></td>
							<td><?= $cnl['marked_to_user'] . ' (' . $cnl['marked_to_job_title'] . ')' ?></td>
							<td><?= formatReadableDateTime($cnl['noting_date']) ?></td>
							<td><?= $cnl['next_date'] ?></td>
						</tr>

						<?php $serialNo++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>