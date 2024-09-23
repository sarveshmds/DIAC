<div class="content-wrapper">
	<div class="content">
		<div class="content-heading text-center">
			<h4 class="content-title">
				Other Pleadings - <?= $caseDetails['case_no'] ?>
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
                        <th style="width: 20%;">Date of filing</th>
                        <th style="width: 20%;">Filed by</th>
					</tr>
				</thead>
				<tbody>
					<?php $serialNo = 1; ?>
					<?php foreach($caseOtherPleadingsList as $copl): ?>
						<tr>
							<td><?= $serialNo ?></td>
							<td><?= $copl['details'] ?></td>
							<td><?= $copl['date_of_filing'] ?></td>
							<td><?= $copl['filed_by'] ?></td>
						</tr>

					<?php $serialNo++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>