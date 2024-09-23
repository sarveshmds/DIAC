<div class="content-wrapper">
    <div class="content">
        <div class="content-heading text-center">
            <h4 class="content-title">
                <?= $page_title ?><?php echo (isset($type) && $type == 'today') ? ': ' . date('d-m-Y') : '' ?>
            </h4>
            <h4 class="content-subtitle">
                Delhi International Arbitration Center
            </h4>
        </div>

        <div class="content-body">
            <table class="table table-bordered table-text-center" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:7%;">S. No.</th>
                        <th style="">Case No.</th>
                        <th style="">Title of Case</th>
                        <th style="">Arbitrator Name</th>
                        <th style="">Purpose</th>
                        <th style="">Date</th>
                        <th style="">Time</th>
                        <th style="">Room No.</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($causeList) > 0) : ?>
                        <?php $serialNo = 1; ?>
                        <?php foreach ($causeList as $cl) : ?>
                            <tr>
                                <td><?= $serialNo ?></td>
                                <td><?= $cl['case_no'] ?></td>
                                <td><?= $cl['title_of_case'] ?></td>
                                <td><?= $cl['amt_name_of_arbitrator'] ?></td>
                                <td><?= $cl['purpose_category_name'] ?></td>
                                <td><?= formatReadableDate($cl['date']) ?></td>
                                <td><?= $cl['time_from'] . '-' . $cl['time_to'] ?></td>
                                <td><?= $cl['room_name'] . ' - ' . $cl['room_no'] ?></td>
                                <td><?= $cl['active_status_desc'] ?></td>
                                <td><?= $cl['remarks'] ?></td>
                            </tr>

                            <?php $serialNo++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="10">No hearings today.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>