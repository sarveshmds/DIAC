<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<style>
    .pdf_button {
        background-color: red;
        color: white;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true" data-case-type="GENERAL">
                        Empanelled Arbitrators
                    </a>
                </li>
                <li class="">
                    <a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
                        Not Empanelled Arbitrators
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    <div class="box wrapper-box">
                        <div class="box-body">
                            <fieldset class="fieldset">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group col-md-3">
                                            <label class="control-label">Arbitrator Name:</label>
                                            <input type="text" name="f_arbitrator_name" id="f_arbitrator_name" class="form-control" />
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="button" class="btn btn-default" id="f_cl_btn_reset" name="f_cl_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>

                                            <button type="submit" class="btn btn-info" id="f_cl_btn_submit" name="f_cl_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>

                            <div class="table-responsive">
                                <table id="arbitrator_setup_datatable_appr" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                    <input type="hidden" name="csrf_trans_token" id="arb_form_token" value="<?php echo generateToken('arbitrator_setup_datatable'); ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:8%;">S. No.</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>D.O.B.</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Case Title Number</th>  
                                            <th>Case Status</th>   
                                            <th style="width:15%;">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab_2">

                    <div class="box wrapper-box">
                        <div class="box-body">
                            <fieldset class="fieldset">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group col-md-3">
                                            <label class="control-label">Arbitrator Name:</label>
                                            <input type="text" name="f_ne_arbitrator_name" id="f_ne_arbitrator_name" class="form-control" />
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="button" class="btn btn-default" id="f_cl_ne_btn_reset" name="f_cl_ne_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>

                                            <button type="submit" class="btn btn-info" id="f_cl_ne_btn_submit" name="f_cl_ne_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>

                            <div class="table-responsive">
                                <table id="arbitrator_setup_datatable_not_emp" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                    <input type="hidden" name="csrf_trans_token" id="arb_form_token" value="<?php echo generateToken('arbitrator_setup_datatable'); ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:8%;">S. No.</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>D.O.B.</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th style="width:15%;">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</div>


<div id="arbitrator_setup_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title pc-modal-title" style="text-align: center;"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(null, array('class' => 'wfst', 'id' => 'add_arbitrator_form', 'enctype' => "multipart/form-data")); ?>
                <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                <input type="hidden" id="crn_op_type" name="op_type" value="">
                <input type="hidden" id="crn_hidden_id" name="crn_hidden_id" value="">
                <input type="hidden" name="csrf_case_form_token" id="arb_form_token" value="<?php echo generateToken('form_add_arbitrator'); ?>">
                <div class="row">

                    <div class="form-group col-md-4 col-xs-6 required">
                        <label class="control-label">Whether on panel:</label>
                        <select class="form-control" name="wt_on_panel" id="wt_on_panel">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-xs-6 required">
                        <label class="control-label">Name of Arbitrator:</label>
                        <input type="text" name="name_of_arbitrator" id="name_of_arbitrator" class="form-control">
                    </div>

                    <div class="form-group col-md-4 col-xs-6 required">
                        <label class="control-label">Category:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Select category</option>
                            <?php foreach ($panel_category as $category) : ?>
                                <option value="<?= $category['code'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-xs-6">
                        <label class="control-label">Email:</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-xs-6">
                        <label class="control-label">Contact Number:</label>
                        <input type="text" name="contact_no" id="contact_no" class="form-control">
                    </div>


                    <div class="form-group col-md-4 col-xs-6">
                        <label class="control-label">Date of Birth:</label>
                        <input type="text" name="dob" id="dob" class="form-control custom-all-previous-dates" placeholder="DD-MM-YYYY" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <fieldset class="fieldset">
                            <legend class="legend">Permanent Address</legend>
                            <div class="fieldset-content-box">
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Address 1</label>
                                    <input type="text" name="permanent_address_1" id="permanent_address_1" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Address 2</label>
                                    <input type="text" name="permanent_address_2" id="permanent_address_2" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Country</label>
                                    <select class="form-control" name="permanent_country" id="permanent_country">
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $country) : ?>
                                            <option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">State</label>
                                    <select class="form-control" name="permanent_state" id="permanent_state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Pincode</label>
                                    <input type="text" name="permanent_pincode" id="permanent_pincode" class="form-control" value="">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <fieldset class="fieldset">
                            <legend class="legend">Correspondence Address</legend>
                            <div class="fieldset-content-box">
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Address 1</label>
                                    <input type="text" name="corr_address_1" id="corr_address_1" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Address 2</label>
                                    <input type="text" name="corr_address_2" id="corr_address_2" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Country</label>
                                    <select class="form-control" name="corr_country" id="corr_country">
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $country) : ?>
                                            <option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">State</label>
                                    <select class="form-control" name="corr_state" id="corr_state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label">Pincode</label>
                                    <input type="text" name="corr_pincode" id="corr_pincode" class="form-control" value="">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="box-footer with-border text-center">
                    <div class="box-tools">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom" id="crn_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";

    // Timepicker initialization
    $('.timepicker').timepicker({
        showInputs: false
    });

    // Datatable initialization
    // =====================================================
      // Datatable for empanelled arbitrator
var arbitrator_setup_datatable = $('#arbitrator_setup_datatable_appr').DataTable({
    "processing": true,
    "serverSide": true,
    "autoWidth": false,
    "responsive": true,
    "ordering": false,
    "order": [],
    "ajax": {
        url: base_url + "arbitrator-setup/get-datatable-data_appr",
        type: 'POST',
        data: function(d) {
            d.csrf_trans_token = $('#arb_form_token').val();
            d.f_arbitrator_name = $("#f_arbitrator_name").val();
            d.f_whether_on_panel = 1;
        }
    },
    "columns": [
        {
            data: null,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'name_of_arbitrator'
        },
        {
            data: 'category'
        },
        {
            data: 'dob'  // This will show in the table but be excluded in the export
        },
        {
            data: 'email'  // This will show in the table but be excluded in the export
        },
        {
            data: 'contact_no'  // This will show in the table but be excluded in the export
        },
        {
            data: 'case_no_desc',
            "sWidth": "20%",  // Increase width for case title
            render: function(data, type, row, meta) {
                return data ? data.replace(/,/g, '<br>') : '';  // Replace commas with <br> for new line
            }
        },
        {
            data: 'case_status',
            "sWidth": "20%",  // Increase width for case status
            render: function(data, type, row, meta) {
                return data ? data.replace(/,/g, '<br>') : '';  // Replace commas with <br> for new line
            }
        },
        {
            data: null,
            "sWidth": "10%",
            "sClass": "alignCenter",
            "render": function(data, type, row, meta) {
                return '<button class="btn btn-warning btn-sm" onclick="btn_edit_appr(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="delete_arbitrator_details(' + data.id + ')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>';
            }
        }
    ],
    "columnDefs": [{
        "targets": [0, 2],
        "orderable": false,
        "sorting": false
    }],
    dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        {
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config) {
                $(node).removeClass('dt-button');
            },
            action: function(e, dt, node, config) {
                add_panel_category_list_modal_open();
            }
        },
        {
            extend: 'excelHtml5',
            text: ' <span class="fa fa-file-excel-o"></span> Export Pink List',
            className: 'btn btn-custom',
            exportOptions: {
                // Exclude the 4th, 5th, and 6th columns (DOB, Email, Contact No) from export
                columns: function(idx, data, node) {
                    return idx !== 3 && idx !== 4 && idx !== 5 && idx !== 8; // Keep all columns except the 4th, 5th, and 6th
                }
            }
        }
    ],
    lengthMenu: [
        [20, 50, 100, 1000],
        [20, 50, 100, 1000]
    ],
    drawCallback: function() {
        $('body').tooltip({
            selector: '[data-tooltip="tooltip"]'
        });
    }
});


    // =====================================================
    // Datatable for not empanelled arbitrator
    var arbitrator_setup_datatable_not_emp = $('#arbitrator_setup_datatable_not_emp').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "responsive": true,
        "ordering": false,
        "order": [],
        "ajax": {
            url: base_url + "arbitrator-setup/get-datatable-data_appr",
            type: 'POST',
            data: function(d) {
                d.csrf_trans_token = $('#arb_form_token').val();
                d.f_arbitrator_name = $("#f_ne_arbitrator_name").val();
                d.f_whether_on_panel = 2;
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name_of_arbitrator'
            },
            {
                data: 'category'
            },
            {
                data: 'dob'
            },
            {
                data: 'email'
            },
            {
                data: 'contact_no'
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<button class="btn btn-warning btn-sm" onclick="btn_edit_unappr(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="delete_arbitrator_details(' + data.id + ')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
                }
            }

        ],
        "columnDefs": [{
            "targets": [0, 2],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config) {
                $(node).removeClass('dt-button');
            },
            action: function(e, dt, node, config) {
                add_panel_category_list_modal_open();
            }
        },
        {
            extend: 'excelHtml5',
            text: ' <span class="fa fa-file-excel-o"></span> Export Pink List',
            className: 'btn btn-custom'
        }  
    ],
        lengthMenu: [
            [20, 50, 100, 1000],
            [20, 50, 100, 1000]
        ],
        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });


    $("#f_cl_btn_submit").on("click", function() {
        arbitrator_setup_datatable.ajax.reload();
    });

    $("#f_cl_btn_reset").on("click", function() {
        $("#f_arbitrator_name").val("");
        arbitrator_setup_datatable.ajax.reload();
    });

    $("#f_cl_ne_btn_submit").on("click", function() {
        arbitrator_setup_datatable_not_emp.ajax.reload();
    });

    $("#f_cl_ne_btn_reset").on("click", function() {
        $("#f_ne_arbitrator_name").val("");
        arbitrator_setup_datatable_not_emp.ajax.reload();
    });



    // Open the modal to add arbitrator
    function add_panel_category_list_modal_open() {
        $('#crn_op_type').val('ADD_ARBITRATOR');
        $('.pc-modal-title').html('<span class="fa fa-plus"></span> Add Arbitrator.');
        $('#arbitrator_setup_modal').modal({
            backdrop: 'static',
            keyboard: false
        })
    }

    // On closing the modal reset the form
    $('#arbitrator_setup_modal').on("hidden.bs.modal", function(e) {
        $('.pc-modal-title').html('');
        $('#crn_op_type').val('');
        $('#crn_hidden_id').val('');
        $('#add_arbitrator_form').trigger('reset');
        $("#crn_btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
        $('#add_arbitrator_form').data('bootstrapValidator').resetForm(true);
    });

    // Button edit form to open the modal with edit form
    function btn_edit_appr(event) {

        // Change the submit button to edit
        $('#crn_btn_submit').attr('disabled', false);
        $("#crn_btn_submit").html('<span class="fa fa-edit"></span> Update');

        // Reset the form
        $('#add_arbitrator_form').trigger('reset');

        // Change the op type
        $("#crn_op_type").val("EDIT_ARBITRATOR");

        // Get data table instance to get the data through row
        var oTable = $('#arbitrator_setup_datatable_appr').dataTable();
        var row;
        if (event.target.tagName == "BUTTON")
            row = event.target.parentNode.parentNode;
        else if (event.target.tagName == "SPAN")
            row = event.target.parentNode.parentNode.parentNode;

        var id = oTable.fnGetData(row)['id'];
        var name_of_arbitrator = oTable.fnGetData(row)['name_of_arbitrator'];
        var email = oTable.fnGetData(row)['email'];
        var category_code = oTable.fnGetData(row)['category_code'];
        var contact = oTable.fnGetData(row)['contact_no'];
        var dob = oTable.fnGetData(row)['dob'];
        var wt_on_panel = oTable.fnGetData(row)['whether_on_panel'];
        var perm_address_1 = oTable.fnGetData(row)['perm_address_1'];
        var perm_address_2 = oTable.fnGetData(row)['perm_address_2'];
        var perm_country = oTable.fnGetData(row)['perm_country'];
        var perm_state = oTable.fnGetData(row)['perm_state'];
        var perm_pincode = oTable.fnGetData(row)['perm_pincode'];
        var corr_address_1 = oTable.fnGetData(row)['corr_address_1'];
        var corr_address_2 = oTable.fnGetData(row)['corr_address_2'];
        var corr_country = oTable.fnGetData(row)['corr_country'];
        var corr_state = oTable.fnGetData(row)['corr_state'];
        var corr_pincode = oTable.fnGetData(row)['corr_pincode'];
        // console.log(perm_country)


        $('#crn_hidden_id').val(id);
        $('#name_of_arbitrator').val(name_of_arbitrator);
        $('#email').val(email);
        $('#contact_no').val(contact);
        $('#category option[value = "' + category_code + '"]').prop("selected", true);
        $('#dob').val(dob);
        // $('#category').val(category);
        $('#wt_on_panel option[value = "' + wt_on_panel + '"]').prop("selected", true);
        $('#permanent_address_1').val(perm_address_1);
        $('#permanent_address_2').val(perm_address_2);
        $('#permanent_country option[value = "' + perm_country + '"]').prop("selected", true);
        get_states(perm_country, perm_state, "#permanent_state");
        $('#permanent_pincode').val(perm_pincode);

        $('#corr_address_1').val(corr_address_1);
        $('#corr_address_2').val(corr_address_2);
        $('#corr_country option[value = "' + corr_country + '"]').prop("selected", true);
        get_states(corr_country, corr_state, "#corr_state");
        $('#corr_pincode').val(corr_pincode);

        $('.pc-modal-title').html('<span class="fa fa-edit"></span> Edit');
        $('#arbitrator_setup_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    // Button edit form to open the modal with edit form
    function btn_edit_unappr(event) {

        // Change the submit button to edit
        $('#crn_btn_submit').attr('disabled', false);
        $("#crn_btn_submit").html('<span class="fa fa-edit"></span> Update');

        // Reset the form
        $('#add_arbitrator_form').trigger('reset');

        // Change the op type
        $("#crn_op_type").val("EDIT_ARBITRATOR");

        // Get data table instance to get the data through row
        var oTable = $('#arbitrator_setup_datatable_not_emp').dataTable();
        var row;
        if (event.target.tagName == "BUTTON")
            row = event.target.parentNode.parentNode;
        else if (event.target.tagName == "SPAN")
            row = event.target.parentNode.parentNode.parentNode;

        var id = oTable.fnGetData(row)['id'];
        var name_of_arbitrator = oTable.fnGetData(row)['name_of_arbitrator'];
        var email = oTable.fnGetData(row)['email'];
        var category_code = oTable.fnGetData(row)['category_code'];
        var dob = oTable.fnGetData(row)['dob'];
        var contact = oTable.fnGetData(row)['contact_no'];
        var wt_on_panel = oTable.fnGetData(row)['whether_on_panel'];
        var perm_address_1 = oTable.fnGetData(row)['perm_address_1'];
        var perm_address_2 = oTable.fnGetData(row)['perm_address_2'];
        var perm_country = oTable.fnGetData(row)['perm_country'];
        var perm_state = oTable.fnGetData(row)['perm_state'];
        var perm_pincode = oTable.fnGetData(row)['perm_pincode'];
        var corr_address_1 = oTable.fnGetData(row)['corr_address_1'];
        var corr_address_2 = oTable.fnGetData(row)['corr_address_2'];
        var corr_country = oTable.fnGetData(row)['corr_country'];
        var corr_state = oTable.fnGetData(row)['corr_state'];
        var corr_pincode = oTable.fnGetData(row)['corr_pincode'];

        $('#crn_hidden_id').val(id);
        $('#name_of_arbitrator').val(name_of_arbitrator);
        $('#email').val(email);
        $('#contact_no').val(contact);
        $('#category option[value = "' + category_code + '"]').prop("selected", true);
        $('#dob').val(dob);
        // $('#category').val(category);
        $('#wt_on_panel option[value = "' + wt_on_panel + '"]').prop("selected", true);
        // $('#wt_on_panel').val(wt_on_panel);
        $('#permanent_address_1').val(perm_address_1);
        $('#permanent_address_2').val(perm_address_2);
        $('#permanent_country option[value = "' + perm_country + '"]').prop("selected", true);
        get_states(perm_country, perm_state, "#permanent_state");
        $('#permanent_pincode').val(perm_pincode);

        $('#corr_address_1').val(corr_address_1);
        $('#corr_address_2').val(corr_address_2);
        $('#corr_country option[value = "' + corr_country + '"]').prop("selected", true);
        get_states(corr_country, corr_state, "#corr_state");
        $('#corr_pincode').val(corr_pincode);

        $('.pc-modal-title').html('<span class="fa fa-edit"></span> Edit');
        $('#arbitrator_setup_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }


    // Add panel category list form
    $('#add_arbitrator_form').bootstrapValidator({
        message: 'This value is not valid',
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {

            var formData = new FormData(document.getElementById("add_arbitrator_form"));

            if ($('#crn_op_type').val() == 'EDIT_ARBITRATOR') {
                urls = base_url + "arbitrator-setup/update";
            } else {
                urls = base_url + "arbitrator-setup/add_arbitrator";
            }

            $.ajax({
                url: urls,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // try {
                    // Enable the submit button
                    enable_submit_btn("cause_list_btn_submit");
                    var obj = JSON.parse(response);

                    if (obj.status == true) {
                        //Reseting form
                        $('#add_arbitrator_form').trigger('reset');
                        toastr.success(obj.msg);

                        // Redraw the datatables
                        arbitrator_setup_datatable.ajax.reload();
                        arbitrator_setup_datatable_not_emp.ajax.reload();

                        $('#arbitrator_setup_modal').modal("hide");
                    } else if (obj.status == false) {
                        swal({
                            title: 'Error',
                            text: obj.msg,
                            type: 'error',
                            html: true
                        });
                    } else if (obj.status === 'validationerror') {
                        swal({
                            title: 'Validation Error',
                            text: obj.msg,
                            type: 'error',
                            html: true
                        });
                    } else {
                        swal({
                            title: 'Error',
                            text: 'Something went wrong.',
                            type: 'error',
                            html: true
                        });
                    }
                    // } catch (e) {
                    //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
                    // }
                },
                error: function(err) {
                    // Enable the submit button
                    enable_submit_btn("cause_list_btn_submit");
                    toastr.error("unable to save");
                }
            });
        },
        fields: {
            name_of_arbitrator: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            // email: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // contact_no: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            category: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            // wt_on_panel: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // permanent_address_1: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // permanent_country: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // permanent_state: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // permanent_pincode: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // corr_address_1: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // corr_country: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // corr_state: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // },
            // corr_pincode: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Required'
            //         },
            //     }
            // }
        }
    })


    // Delete function for panel category list
    function delete_arbitrator_details(id) {
        if (id) {
            swal({
                type: 'error',
                title: 'Are you sure?',
                text: 'You want to delete the record.',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel"
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: base_url + 'arbitrator-setup/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            csrf_trans_token: $('#arb_form_token').val()
                        },
                        success: function(response) {

                            try {
                                var obj = JSON.parse(response);

                                if (obj.status == false) {
                                    $('#errorlog').html('');
                                    $('#errorlog').hide();
                                    toastr.error(obj.msg);
                                } else if (obj.status === 'validationerror') {
                                    swal({
                                        title: 'Validation Error',
                                        text: obj.msg,
                                        type: 'error',
                                        html: true
                                    });
                                } else {
                                    $('#errorlog').html('');
                                    $('#errorlog').hide();
                                    toastr.success(obj.msg);

                                    // Redraw the datatables
                                    arbitrator_setup_datatable.ajax.reload();
                                    arbitrator_setup_datatable_not_emp.ajax.reload();
                                }
                            } catch (e) {
                                swal("Sorry", 'Unable to Save.Please Try Again !', "error");
                            }
                        },
                        error: function(error) {
                            toastr.error('Something went wrong.');
                        }
                    })
                } else {
                    swal.close();
                }
            })
        }
    }

    // function for get state for permanent address
    $(document).on("change", "#permanent_country", function() {
        var country_code = $(this).val();

        get_states(country_code, "", "#permanent_state");
    });

    // function for get state for correspondence address
    $(document).on("change", "#corr_country", function() {
        var country_code = $(this).val();

        get_states(country_code, "", "#corr_state");
    });

    function get_states(country_code, select = "", select_ele) {
        $.ajax({
            url: base_url + "service/get_states_using_country_code",
            type: "POST",
            data: {
                country_code: country_code
            },
            success: function(response) {
                var response = JSON.parse(response);
                var options = '<option value="">Select State</option>';
                $.each(response, function(index, state) {
                    if (select == "") {
                        options +=
                            '<option value="' + state.id + '">' + state.name + "</option>";
                    } else {
                        if (select == state.id) {
                            options +=
                                '<option value="' +
                                state.id +
                                '" selected>' +
                                state.name +
                                "</option>";
                        } else {
                            options +=
                                '<option value="' + state.id + '">' + state.name + "</option>";
                        }
                    }
                });
                $(select_ele).html(options);
            },
        });
    }

    // Function to enable the submit button
    function enable_submit_btn(id) {
        $('#' + id).attr('disabled', false);
    }
</script>