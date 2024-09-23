<style>
    .btn-top-links {
        margin-bottom: 5px;
    }
</style>
<section class="case-links-header">
    <ul class="list-unstyled case-links-list">

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL'])) : ?>
            <li>
                <a href="<?= base_url('allotted-case') ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Alloted Case</a>
            </li>
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC'])) : ?>
            <li>
                <a href="<?= base_url('status-of-pleadings/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Status of Pleadings</a>
            </li>
            <li>
                <a href="<?= base_url('claimant-respondant-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Claimant & Respondent</a>
            </li>
            <li>
                <a href="<?= base_url('counsels/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Counsels</a>
            </li>
            <li>
                <a href="<?= base_url('arbitral-tribunal/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Arbitral Tribunal</a>
            </li>

        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC', 'COORDINATOR', 'ACCOUNTS'])) : ?>
            <li>
                <a href="<?= base_url('noting/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Noting</a>
            </li>

            <li>
                <a href="<?= base_url('view-fees-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Fees Details</a>
            </li>

            <li>
                <a href="<?= base_url('case-all-files/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Case Files</a>
            </li>

            <li>
                <a href="<?= base_url('case-order/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Case Orders</a>
            </li>
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['ACCOUNTS', 'DEPUTY_COUNSEL', 'DIAC'])) : ?>
            <li>
                <a href="<?= base_url('case-fees-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Case Fee</a>
            </li>
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC'])) : ?>
            <li>
                <a href="<?= base_url('termination/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links"><i class="fa fa-paper-plane-o"></i> Termination</a>
            </li>
        <?php endif; ?>

        <li>
            <a href="<?= base_url('view-case/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links" target="_BLANK"><i class="fa fa-paper-plane-o"></i> View Case</a>
        </li>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC', 'COORDINATOR', 'ACCOUNTS'])) : ?>
            <div class="btn-group dropleft">
                <button type="button" class="btn btn-warning btn-top-links dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Draft Letters <i class="fa fa-chevron-down"></i></button>
                <ul class="dropdown-menu btn-draft-letters-ul" role="menu">
                    <li><a href="<?= base_url() ?>response-format/editable?type=SOC_FORMAT&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Statement of Claim</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=REMINDER_SOC_FORMAT&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Reminder of S.O.C.</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=FINAL_REMINDER_SOC_FORMAT&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Final Reminder of S.O.C.</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=CLOSED_DRAFT&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Administratively Closed Draft</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=NAMES_TO_OPPOSITE_PARTY&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Names to opposite party</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=FEE_DRAFT&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> Fee Draft</a></li>
                    <li><a href="<?= base_url() ?>response-format/editable?type=DOH_LETTER&case_no=<?= $case_details['slug'] ?>" target="_BLANK"><i class="fa fa-book"></i> DOH Letter</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </ul>
</section>

<!-- Linked Cases -->
<?php
$linkedCaseDetails = getLinkedCaseDetails($case_details['slug']);

if ($linkedCaseDetails) {
    $masterCase = getLinkedMasterCase($linkedCaseDetails['master_case']);

    // Get all linked cases
    $linkedCasesList = getAllLinkedWithMasterCase($linkedCaseDetails['master_case']);
}
?>

<?php if ($linkedCaseDetails) : ?>
    <div class="case-links-header">
        <div class="card wrapper-box">
            <div class="card-header">
                <h4 class="card-title">
                    Linked Cases:
                </h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled case-links-list">
                    <?php if (isset($masterCase) && $masterCase) : ?>
                        <li>
                            <a href="<?= base_url('view-case/' . $masterCase['case_no']) ?>" class="btn btn-warning btn-top-links " target="_BLANK"><i class="fa fa-paper-plane-o"></i> <?= get_full_case_number($masterCase) ?> (Master)</a>
                        </li>
                    <?php endif; ?>

                    <?php foreach ($linkedCasesList as $case) : ?>
                        <li>
                            <a href="<?= base_url('view-case/' . $case['tagged_case']) ?>" class="btn btn-warning btn-top-links" target="_BLANK"><i class="fa fa-paper-plane-o"></i> <?= $case['tagged_case_no_prefix'] . '/' . $case['tagged_case_desc'] . '/' . $case['tagged_case_no_year'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>