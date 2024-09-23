<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
    <section class="content-header">

        <div class="row">

            <div class="col-md-6 col-sm-12 col-xs-12">
                <h3 style="display: inline-block; margin-top: 5px; margin-bottom: 0px;"> <?= $page_title ?></h3>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="pull-right vc-header-btn-col">
                    <a href="<?= base_url('all-registered-case'); ?>" class="btn btn-default "><span class="fa fa-backward"></span> Go Back</a>
                </div>
            </div>

        </div>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true" data-case-type="GENERAL">
                                    General Information
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="EMERGENCY">
                                    Arbitrators
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- General Information Tab -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label class="control-label">Diary Number:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['diary_number_prefix'])) ? $case_details['diary_number_prefix'] : '' ?>/<?= (isset($case_details['diary_number'])) ? $case_details['diary_number'] : '' ?>/<?= (isset($case_details['diary_number_year'])) ? $case_details['diary_number_year'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label class="control-label">DIAC Registration No.:</label>
                                        <p class="mb-0">
                                            <?= get_full_case_number($case_details) ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                        <label class="control-label">Case Title:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['case_title'])) ? $case_details['case_title'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-12 col-xs-12 ">
                                        <div class="box mb-3">

                                            <div class="box-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="20%">Claimant Name</th>
                                                            <th colspan="2" class="text-center" width="30%">Address</th>
                                                            <th width="20%">E-mail</th>
                                                            <th width="20%">Mobile No.</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-class-claimant">
                                                        <?php if (isset($claimants_list)) : ?>
                                                            <?php foreach ($claimants_list as $cl) : ?>
                                                                <tr>
                                                                    <td><?= $cl['name'] ?></td>
                                                                    <td colspan="2">
                                                                        <p class="mb-1">
                                                                            <?= $cl['perm_address_1'] . ', ' . $cl['perm_address_2'] ?>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <?= $cl['state_name'] ?>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <?= $cl['country_name'] . ' - ' . $cl['perm_pincode'] ?>
                                                                        </p>
                                                                    </td>
                                                                    <td><?= $cl['email'] ?></td>
                                                                    <td><?= $cl['contact'] ?></td>

                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="box mb-3">

                                            <div class="box-body">
                                                <div class="table-responsive p-0">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width="20%">Respondent Name</th>
                                                                <th colspan="2" class="text-center" width="30%">Address</th>
                                                                <th width="20%">E-mail</th>
                                                                <th width="20%">Mobile No.</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-class-respondant">
                                                            <?php if (isset($respondants_list)) : ?>
                                                                <?php foreach ($respondants_list as $res) : ?>
                                                                    <tr>
                                                                        <td><?= $res['name'] ?></td>
                                                                        <td colspan="2">
                                                                            <p class="mb-1">
                                                                                <?= $res['perm_address_1'] . ', ' . $res['perm_address_2'] ?>
                                                                            </p>
                                                                            <p class="mb-1">
                                                                                <?= $res['state_name'] ?>
                                                                            </p>
                                                                            <p class="mb-1">
                                                                                <?= $res['country_name'] . ' - ' . $res['perm_pincode'] ?>
                                                                            </p>
                                                                        </td>
                                                                        <td><?= $res['email'] ?></td>
                                                                        <td><?= $res['contact'] ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label for="" class="control-label">Mode of reference:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['reffered_by'])) ? $case_details['reffered_by_desc'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Date of reference:</label>

                                        <p class="mb-0">
                                            <?= (isset($case_details['reffered_on'])) ? formatReadableDate($case_details['reffered_on']) : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Court:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['reffered_by_court'])) ? $case_details['court_name'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Name of Judges:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['reffered_by_judge'])) ? $case_details['reffered_by_judge'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Name of Authority:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['name_of_court'])) ? $case_details['name_of_court'] : '' ?>
                                        </p>

                                    </div>

                                    <?php if (isset($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') : ?>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Case Type:</label>
                                            <p class="mb-0">
                                                <?= (isset($case_details['reffered_by_court_case_type'])) ? $case_details['reffered_by_court_case_type'] : '' ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Reference No.:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['arbitration_petition'])) ? $case_details['arbitration_petition'] : '' ?>
                                        </p>
                                    </div>

                                    <?php if (isset($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') : ?>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Year:</label>
                                            <p class="mb-0">
                                                <?= (isset($case_details['reffered_by_court_case_year'])) ? $case_details['reffered_by_court_case_year'] : '' ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Ref. Received On:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['recieved_on'])) ? formatReadableDate($case_details['recieved_on']) : '' ?>
                                        </p>

                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Date of registration:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['registered_on'])) ? formatReadableDate($case_details['registered_on']) : '' ?>
                                        </p>

                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label for="" class="control-label">Nature of arbitration:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['type_of_arbitration'])) ? $case_details['type_of_arbitration_desc'] : '' ?>
                                        </p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
                                        <label for="" class="control-label">Type of Arbitration:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['di_type_of_arbitration'])) ? $case_details['di_type_of_arbitration_desc'] : '' ?>
                                        </p>

                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
                                        <label for="" class="control-label">Arbitrator Status:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['arbitrator_status'])) ? $case_details['arbitrator_status_desc'] : '' ?>
                                        </p>
                                    </div>

                                    <?php if (isset($case_details['arbitrator_status']) && $case_details['arbitrator_status'] == 1) : ?>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
                                            <label for="" class="control-label">Arbitral Tribunal Strength:</label>
                                            <p class="mb-0">
                                                <?php if (isset($case_details['arbitral_tribunal_strength'])) : ?>
                                                    <?php if ($case_details['arbitral_tribunal_strength'] == 1) : ?>
                                                        Sole
                                                    <?php else : ?>
                                                        <?= $case_details['arbitral_tribunal_strength'] ?>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-xs-12">
                                        <label class="control-label">Uploaded Supporting Documents:</label>
                                        <?php if (isset($case_supporting_docs) && count($case_supporting_docs) > 0) : ?>
                                            <div class="">
                                                <?php foreach ($case_supporting_docs as $doc) : ?>
                                                    <a href="<?= base_url(VIEW_CASE_FILE_UPLOADS_FOLDER . $doc['file_name']) ?>" class="btn btn-primary btn-sm" target="_BLANK">
                                                        <i class="fa fa-eye"></i> View</a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <p class="mb-0">
                                                No Document
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 ">
                                        <label class="control-label">Remarks:</label>
                                        <p class="mb-0">
                                            <?= (isset($case_details['remarks'])) ? $case_details['remarks'] : '' ?>
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <!-- Claimant Details Tab -->
                            <div class="tab-pane" id="tab_2" <?= (isset($case_details['arbitrator_status']) && $case_details['arbitrator_status'] == '2') ? 'style="display: none;"' : '' ?>>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="datatableArbList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                                                <input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('datatableArbList'); ?>">
                                                <?php if (isset($case_details['slug'])) : ?>
                                                    <input type="hidden" id="at_case_no" value="<?php echo $case_details['slug']; ?>">
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
                                                        <!-- <th width="20%">Action</th> -->
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    var dataTable = $("#datatableArbList").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: false,
        order: [],
        // lengthMenu: [
        // 	[10, 25, 50, -1],
        // 	[10, 25, 50, "All"],
        // ],
        ajax: {
            url: base_url + "service/get_all_arb_case_list/CASE_ARBITRAL_TRIBUNAL_LIST",
            type: "POST",
            data: {
                csrf_trans_token: $("#csrf_at_trans_token").val(),
                case_no: $("#at_case_no").val(),
            },
        },
        columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },

            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.whether_on_panel == 1 ? "Yes" : "No";
                },
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.whether_on_panel == 1 ?
                        data.amt_name_of_arbitrator :
                        data.name_of_arbitrator;
                },
            },
            {
                data: "arbitrator_type_desc"
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.whether_on_panel == 1 ? data.amt_email : data.email;
                },
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.whether_on_panel == 1 ?
                        data.amt_contact_no :
                        data.contact_no;
                },
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.whether_on_panel == 1 ?
                        data.amt_category_name :
                        data.category_name;
                },
            },
            {
                data: "appointed_by_desc"
            },
            {
                data: "date_of_appointment"
            },
            {
                data: "date_of_declaration"
            },
            {
                data: "arb_terminated_desc"
            },
            // {
            // 	data: null,
            // 	sWidth: "20%",
            // 	sClass: "alignCenter",
            // 	render: function (data, type, row, meta) {
            // 		var fileViewBtn = "";
            // 		if (data.termination_files) {
            // 			var files = JSON.parse(data.termination_files);
            // 			if (files.length > 0) {
            // 				fileViewBtn +=
            // 					'<div class="btn-group dropleft">\
            // 				<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="View Files"><span class="fa fa-file"></span></button>\
            // 				<ul class="dropdown-menu" role="menu">';
            // 				files.forEach((element, index) => {
            // 					fileViewBtn +=
            // 						'<li><a href="' +
            // 						base_url +
            // 						"public/upload/arbitral_tribunal/" +
            // 						element +
            // 						'" target="_BLANK" >File ' +
            // 						(index + 1) +
            // 						"</a></li>";
            // 				});
            // 				fileViewBtn +=
            // 					"</ul>\
            // 				</div>";
            // 			}
            // 		}

            // 		return (
            // 			// <button class="btn btn-success btn-sm" onclick="viewATForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button>
            // 			// <button class="btn btn-primary btn-sm" onclick="uploadConsentForm(event)" data-tooltip="tooltip" title="Upload consent form"><span class="fa fa-upload"></span></button>

            // 			"" +
            // 			fileViewBtn +
            // 			'  <button class="btn btn-warning btn-sm" onclick="btnEditATForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteATForm(' +
            // 			data.at_code +
            // 			')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
            // 		);
            // 	},
            // },
        ],
        columnDefs: [{
            targets: ["_all"],
            orderable: false,
            sorting: false,
        }, ],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            text: '<span class="fa fa-plus"></span> Add New Arbitrator',
            className: "btn btn-custom",
            init: function(api, node, config) {
                $(node).removeClass("dt-button");
            },
            action: function(e, dt, node, config) {
                add_arb_tri_list_modal_open();
            },
        }, ],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
    });
</script>