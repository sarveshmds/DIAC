<div class="content-wrapper">
	<div class="content">
		<div class="content-heading text-center">
			<h4 class="content-title">
				Other Correspondance - <?= $caseDetails['case_no'] ?>
			</h4>
            <h4 class="content-subtitle">
                <?= $caseDetails['case_title'] ?>
            </h4>
		</div>

		<div class="content-body">
			<table class="table table-bordered table-text-center" cellspacing="0">
				<thead>
					<tr>
                        <th style="width:8%;">S. No.</th>
                        <th style="">Details</th>
                        <th style="width: 10%;">Date of Correspondance</th>
                        <th style="width: 10%;">Send by</th>
                        <th style="width: 10%;">Sent to</th>
					</tr>
				</thead>
				<tbody>
					<?php $serialNo = 1; ?>
					<?php foreach($caseOtherCorrespondanceList as $cocl): ?>
						<tr>
							<td><?= $serialNo ?></td>
							<td><?= $cocl['details'] ?></td>
							<td><?= $cocl['date_of_correspondance'] ?></td>
							<td><?= $cocl['send_by'] ?></td>
							<td><?= $cocl['sent_to'] ?></td>
						</tr>

					<?php $serialNo++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>