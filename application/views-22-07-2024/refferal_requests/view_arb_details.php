<!-- <div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered dt-responsive nowrap">
                <tr>
                    <th>S.No</th>
                    <th>Is Empanelled</th>
                    <th>Name of Arbitrator</th>
                    <th>Arbitrator Type</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Category</th>
                    <th>Appointed By</th>
                    <th>Date Of Appointment</th>
                    <th>Date Of Consent</th>
                    <th>Terminated</th>
                </tr>
                <tr>
                    <th><?= $arbitrator_list['atbitrator_type'] ?></th>
                </tr>
            </table>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="table-responsive">
            <table id="dataTableArbTriList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                <input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('dataTableArbTriList'); ?>">
                    <?php if (isset($case_no)) : ?>
                        <input type="hidden" id="at_case_no" value="<?php echo $case_no; ?>">
                    <?php endif; ?>
                    <thead>
                        <tr>
                            <th style="width:8%;">S. No.</th>
                            <th>Is Empanelled</th>
                            <th>Name of Arbitrator</th>
                            <th>Arbitrator Type</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <th>Appointed By</th>
                            <th>Date Of Appointment</th>
                            <th>Date Of Consent</th>
                            <th>Terminated</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
</div>