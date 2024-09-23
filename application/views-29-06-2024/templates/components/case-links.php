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