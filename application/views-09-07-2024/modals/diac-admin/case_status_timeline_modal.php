<div id="case_status_timeline_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cause-list-modal-title" style="text-align: 	center;">Case Status Timeline</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="8%">S.No.</th>
                                <th>Case Hearing</th>
                                <th width="35%">Case Status</th>
                                <th>Changed By</th>
                                <th>Changed On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($case_status_timelines) > 0) : ?>
                                <?php foreach ($case_status_timelines as $key => $cst) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $cst['case_status_desc'] ?></td>
                                        <td><?= $cst['minor_case_status_name'] ?></td>
                                        <td><?= $cst['user_display_name'] . '(' . $cst['job_title'] . ')' ?></td>
                                        <td><?= formatReadableDateTime($cst['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">No record available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>