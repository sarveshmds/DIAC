<div class="content-wrapper">
    <div class="content">
        <div class="content-heading">
            <table style="width: 100%;">
                <tr>
                    <td width="7%">
                        <img src="./public/custom/home/images/diac_logo.png" alt="Logo" style="width: 130px;" />
                    </td>
                    <td>
                        <div class="text-center">
                            <h4 class="content-title">
                                DELHI INTERNATIONAL ARBITRATION CENTRE
                            </h4>
                            <h4 class="content-subtitle" style="text-transform: uppercase;">
                                <?= $page_title ?><?php echo (isset($type) && $type == 'today') ? ': ' . date('d-m-Y') : '' ?>
                            </h4>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content-body">
            <table class="table cause-list-table table-bordered table-text-center" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:6%;">S. No.</th>
                        <th width="8%">Case No.</th>
                        <th>Title of Case</th>
                        <th>Arbitrator Name</th>
                        <th width="8%">Purpose</th>
                        <th width="8%">Date</th>
                        <th width="8%">Time</th>
                        <th width="8%">Mode of hearing</th>
                        <th width="8%">Room No.</th>
                        <th width="7%">Status</th>
                        <th width="10%">Remarks</th>
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
                                <td><?= $cl['mode_of_hearing'] ?></td>
                                <td><?= ($cl['room_no']) ? $cl['room_no'] : 'N/A' ?></td>
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