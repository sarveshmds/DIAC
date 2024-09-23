<style>
    .btn-top-links {
        margin-bottom: 5px;
    }
</style>
<section class="case-links-header">
    <ul class="list-unstyled case-links-list">

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL'])) : ?>
            <li>
                <a href="<?= base_url('allotted-case') ?>" class="btn btn-warning btn-top-links">Alloted Case</a>
            </li>
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC'])) : ?>
            <li>
                <a href="<?= base_url('status-of-pleadings/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Status of Pleadings</a>
            </li>
            <li>
                <a href="<?= base_url('claimant-respondant-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Claimant & Respondent</a>
            </li>
            <li>
                <a href="<?= base_url('counsels/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Counsels</a>
            </li>
            <li>
                <a href="<?= base_url('arbitral-tribunal/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Arbitral Tribunal</a>
            </li>
            <li>
                <a href="<?= base_url('termination/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Termination</a>
            </li>

        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'DIAC', 'COORDINATOR', 'ACCOUNTS'])) : ?>
            <li>
                <a href="<?= base_url('noting/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Noting</a>
            </li>

            <li>
                <a href="<?= base_url('view-fees-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Fees Details</a>
            </li>

            <li>
                <a href="<?= base_url('case-all-files/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Case Files</a>
            </li>
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('role'), ['ACCOUNTS', 'DEPUTY_COUNSEL', 'DIAC'])) : ?>
            <li>
                <a href="<?= base_url('case-fees-details/' . $case_details['slug']) ?>" class="btn btn-warning btn-top-links">Case Fee</a>
            </li>
        <?php endif; ?>
    </ul>
</section>