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