<div class="content-wrapper">
	<div class="content">
		<div class="content-heading text-center">
			<h4 class="content-title">
				DIAC - Panel Of Arbitrators
			</h4>
		</div>

		<div class="content-body">
			<table class="table table-bordered table-text-center" cellspacing="0">
				<thead>
					<tr>
                        <th style="width:6%;">S. No.</th>
                        <th style="width:16%;">Name</th>
                        <th style="">Category</th>
                        <th hidden>Experience</th>
                        <th hidden>Enrollment No.</th>
                        <th hidden>Contacts Details</th>
                        <th hidden>Email Details</th>
                        <th hidden>Address</th>
                        <th hidden>Remarks</th>
					</tr>
				</thead>
				<tbody>
					<?php $serialNo = 1; ?>
					<?php foreach($panelOfArbitratorsList as $poa): ?>
						<tr>
							<td><?= $serialNo ?></td>
							<td><?= $poa['name'] ?></td>
							<td><?= $poa['category_name'] ?></td>
							<td><?= $poa['experience'] ?></td>
							<td><?= $poa['enrollment_no'] ?></td>
							<td><?= $poa['contact_details'] ?></td>
                            <td><?= $poa['email_details'] ?></td>
                            <td><?= $poa['address_details'] ?></td>
                            <td><?= $poa['remarks'] ?></td>
						</tr>

					<?php $serialNo++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>