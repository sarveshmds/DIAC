<section class="content-header">
    <h1 style="display: inline-block;">
        <?= $page_title ?>
        <?= (isset($case_details['case_no'])) ? ' : ' . $case_details['case_no'] . ' (' . $case_details['case_title'] . ')' : '' ?>
        <?= (isset($miscellaneous['misc_number'])) ? ' : (' . $miscellaneous['misc_number'] . ')' : '' ?>
    </h1>
    <a href="Javascript:void(0)" onclick="history.back()" class="btn btn-default pull-right"><span class="fa fa-backward"></span> Go Back</a>
</section>