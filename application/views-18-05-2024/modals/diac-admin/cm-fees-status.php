<div id="feesStatusModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
	    <div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal">&times;</button>
	            <h4 class="modal-title cause-list-modal-title" style="text-align: 	center;">Fees Status</h4>
	        </div>
	        <div class="modal-body">
                <div class="fees-status-wrapper">
                    <input type="hidden" id="csrf_fees_status_trans_token" value="<?php echo generateToken('getFeesStatus'); ?>">
					<div class="fs-fee-details">
						<div>
							<h5><strong>Case No.:</strong> <span id="fs_case_no"></span></h5>
							<h5><strong>Case Title:</strong> <span id="fs_case_title"></span></h5>
							<h5><strong>Total Arbitrators Fees (Rs.):</strong> <span id="fs_total_arb_fees"></span></h5>
						</div>
						<table class="table table-bordered" style="margin-top: 20px;">
							<tbody>
								<tr>
									<th></th>
									<th colspan="2" class="text-center">Claimant Share:</th>
									<th colspan="2" class="text-center">Respondent Share:</th>
								</tr>
								<tr>
									<th></th>
									<th>Arbitrators Fees (Rs.):</th>
									<th>Administrative Expenses (Rs.):</th>
									<th>Arbitrators Fees (Rs.):</th>
									<th>Administrative Expenses (Rs.):</th>
								</tr>
								<tr>
									<td>
										<strong>Total</strong>
									</td>
									<td>
										<span id="fs_total_cs_arb_fees"></span>
									</td>
									<td>
										<span id="fs_total_cs_admin_fees"></span>
									</td>
									<td>
										<span id="fs_total_rs_arb_fees"></span>
									</td>
									<td>
										<span id="fs_total_rs_admin_fees"></span>
									</td>
								</tr>
								<tr>
									<td>
										<strong>Deposited</strong>
									</td>
									<td>
										<span id="fs_deposited_cs_arb_fees"></span>
									</td>
									<td>
										<span id="fs_deposited_cs_admin_fees"></span>
									</td>
									<td>
										<span id="fs_deposited_rs_arb_fees"></span>
									</td>
									<td>
										<span id="fs_deposited_rs_admin_fees"></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
                </div>
	        </div>
	    </div>
  	</div>
</div>