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
        <div class="box wrapper-box">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="mcs_setup_datatable" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                        <input type="hidden" name="csrf_trans_token" id="mcs_form_token" value="<?php echo generateToken('mcs_setup_datatable'); ?>">
                        <thead>
                            <tr>
                                <th style="width:8%;">S. No.</th>
                                <th>Major Stage</th>
                                <th>Serial Number</th>
                                <th>Code of Stage</th>
                                <th>Name of Stage</th>
                                <th style="width:15%;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<div id="mcs_setup_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title mcs-modal-title" style="text-align: center;"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(null, array('class' => 'wfst', 'id' => 'mcs_form', 'enctype' => "multipart/form-data")); ?>
                <input type="hidden" id="mcs_op_type" name="op_type" value="">
                <input type="hidden" id="mcs_hidden_id" name="mcs_hidden_id" value="">
                <input type="hidden" name="csrf_case_form_token" id="mcs_form_token" value="<?php echo generateToken('mcs_form'); ?>">
                <div class="row">

                    <div class="form-group col-md-6 col-xs-12 required">
                        <label class="control-label">Major Stage:</label>
                        <select name="major_stage_code" id="major_stage_code" class="form-control">
                            <option value="">Select</option>
                            <option value="UNDER_PROCESS">Pre-Hearing Stage</option>
                            <option value="UNDER_HEARING">Under Hearing Stage</option>
                            <option value="AWARD_TERMINATION">Award/Termination Stage</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-xs-12 required">
                        <label class="control-label">Serial No.:</label>
                        <input type="text" name="sl_no" id="sl_no" class="form-control">
                    </div>

                    <div class="form-group col-md-6 col-xs-12 required">
                        <label class="control-label">Code of Stage:</label>
                        <input type="text" name="code_of_stage" id="code_of_stage" class="form-control">
                    </div>

                    <div class="form-group col-md-6 col-xs-12 required">
                        <label class="control-label">Name of Stage:</label>
                        <input type="text" name="name_of_stage" id="name_of_stage" class="form-control">
                    </div>
                </div>

                <div class="box-footer with-border text-center">
                    <div class="box-tools">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom" id="mcs_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
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

    // Datatable initialization
    // =====================================================
    // Datatable for empanelled arbitrator
    var mcs_setup_datatable = $('#mcs_setup_datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "responsive": true,
        "ordering": false,
        "order": [],
        "lengthMenu": [
            [50, 100, 1000, 'ALL'],
            [50, 100, 1000, 'ALL']
        ],
        "ajax": {
            url: base_url + "minor-stages-setup/get-datatable-data",
            type: 'POST',
            data: function(d) {
                d.csrf_trans_token = $('#mcs_form_token').val();
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'major_stage_code'
            },
            {
                data: 'sl_no'
            },
            {
                data: 'code'
            },
            {
                data: 'name'
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<button class="btn btn-warning btn-sm" onclick="btn_edit_mcs(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> '
                }
            }

        ],
        "columnDefs": [{
            "targets": ['_ALL'],
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
                add_mcs_form_modal();
            }
        }],

        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });


    $("#f_cl_btn_submit").on("click", function() {
        mcs_setup_datatable.ajax.reload();
    });

    $("#f_cl_btn_reset").on("click", function() {
        $("#f_name_of_stage").val("");
        mcs_setup_datatable.ajax.reload();
    });

    // Open the modal to add arbitrator
    function add_mcs_form_modal() {
        $('#mcs_op_type').val('ADD_MCS');
        $('.mcs-modal-title').html('<span class="fa fa-plus"></span> Add New.');
        $('#mcs_setup_modal').modal({
            backdrop: 'static',
            keyboard: false
        })
    }

    // On closing the modal reset the form
    $('#mcs_setup_modal').on("hidden.bs.modal", function(e) {
        $('.mcs-modal-title').html('');
        $('#mcs_op_type').val('');
        $('#mcs_hidden_id').val('');
        $('#major_stage_code option').prop('selected', false);
        $('#code_of_stage').prop('disabled', false);
        $('#mcs_form').trigger('reset');
        $("#mcs_btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
        $('#mcs_form').data('bootstrapValidator').resetForm(true);
    });

    // Button edit form to open the modal with edit form
    function btn_edit_mcs(event) {

        // Change the submit button to edit
        $('#mcs_btn_submit').attr('disabled', false);
        $("#mcs_btn_submit").html('<span class="fa fa-edit"></span> Update');

        // Reset the form
        $('#mcs_form').trigger('reset');

        // Get data table instance to get the data through row
        var oTable = $('#mcs_setup_datatable').dataTable();
        var row;
        if (event.target.tagName == "BUTTON")
            row = event.target.parentNode.parentNode;
        else if (event.target.tagName == "SPAN")
            row = event.target.parentNode.parentNode.parentNode;

        var id = oTable.fnGetData(row)['id'];
        var major_stage_code = oTable.fnGetData(row)['major_stage_code'];
        var sl_no = oTable.fnGetData(row)['sl_no'];
        var code = oTable.fnGetData(row)['code'];
        var name = oTable.fnGetData(row)['name'];

        // Change the op type
        $("#mcs_op_type").val("EDIT_MCS");
        $('#mcs_hidden_id').val(id);
        $('#sl_no').val(sl_no);
        $('#major_stage_code option[value="' + major_stage_code + '"]').prop('selected', true);

        $('#code_of_stage').val(code);
        $('#code_of_stage').prop('disabled', true);

        $('#name_of_stage').val(name);

        $('.mcs-modal-title').html('<span class="fa fa-edit"></span> Edit');
        $('#mcs_setup_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    // Add panel category list form
    $('#mcs_form').bootstrapValidator({
        message: 'This value is not valid',
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {

            var formData = new FormData(document.getElementById("mcs_form"));

            if ($('#mcs_op_type').val() == 'EDIT_MCS') {
                urls = base_url + "minor-stages-setup/update";
            } else {
                urls = base_url + "minor-stages-setup/store";
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
                    enable_submit_btn("mcs_btn_submit");
                    var obj = JSON.parse(response);

                    if (obj.status == true) {
                        //Reseting form
                        $('#mcs_form').trigger('reset');
                        toastr.success(obj.msg);

                        // Redraw the datatables
                        mcs_setup_datatable.ajax.reload();
                        $('#mcs_setup_modal').modal("hide");

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
                    enable_submit_btn("mcs_btn_submit");
                    toastr.error("Internal server error.");
                }
            });
        },
        fields: {
            sl_no: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            code_of_stage: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            name_of_stage: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
        }
    })


    // Function to enable the submit button
    function enable_submit_btn(id) {
        $('#' + id).attr('disabled', false);
    }
</script>