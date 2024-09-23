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
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div>
                            <table id="case_ref_no_datatable" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                <input type="hidden" name="csrf_trans_token" id="csrf_crn_form_token" value="<?php echo generateToken('case_ref_no_datatable'); ?>">
                                <thead>
                                    <tr>
                                        <th style="width:8%;">S. No.</th>
                                        <th>Type</th>
                                        <th>Start Index</th>
                                        <th>End Index</th>
                                        <th>Slot</th>
                                        <th style="width:15%;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<div id="case_ref_no_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title pc-modal-title" style="text-align: center;"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(null, array('class' => 'wfst', 'id' => 'case_ref_no_form', 'enctype' => "multipart/form-data")); ?>
                <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                <input type="hidden" id="crn_op_type" name="op_type" value="">
                <input type="hidden" id="crn_hidden_id" name="crn_hidden_id" value="">
                <input type="hidden" name="csrf_case_form_token" id="csrf_crn_form_token" value="<?php echo generateToken('form_add_panel_category'); ?>">

                <div class="row">
                    <div class="form-group col-md-12 col-xs-12 required">
                        <label class="control-label">Type:</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="DC_CASE_REF_NO">DC</option>
                            <option value="CM_CASE_REF_NO">CM</option>
                            <option value="COORDINATOR_CASE_REF_NO">Coordinator</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-xs-12 required">
                        <label class="control-label">Start Index:</label>
                        <input type="number" name="start_index" id="start_index" class="form-control" maxlength="3">
                    </div>
                    <div class="form-group col-md-12 col-xs-12 required">
                        <label class="control-label">End Index:</label>
                        <input type="number" name="end_index" id="end_index" class="form-control" maxlength="3">
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
    var case_ref_no_datatable = $('#case_ref_no_datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "responsive": true,
        "order": [],
        "ajax": {
            url: base_url + "case-ref-no-setup/get-datatable-data",
            type: 'POST',
            data: {
                csrf_trans_token: $('#csrf_crn_form_token').val()
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'type'
            },
            {
                data: 'start_index'
            },
            {
                data: 'end_index'
            },
            {
                data: null,
                "sWidth": "20%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return data.start_index + '-' + data.end_index;
                }
            }, {
                data: null,
                "sWidth": "20%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<button class="btn btn-warning btn-sm" onclick="btn_edit(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="delete_case_ref_no(' + data.code + ')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
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
        }],
        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });

    // Open the modal to add panel category list
    function add_panel_category_list_modal_open() {
        $('#crn_op_type').val('ADD_CASE_REF_NO');
        $('.pc-modal-title').html('<span class="fa fa-plus"></span> Add Case Ref. No.');
        $('#case_ref_no_modal').modal({
            backdrop: 'static',
            keyboard: false
        })
    }

    // On closing the modal reset the form
    $('#case_ref_no_modal').on("hidden.bs.modal", function(e) {
        $('.pc-modal-title').html('');
        $('#crn_op_type').val('');
        $('#crn_hidden_id').val('');
        $('#case_ref_no_form').trigger('reset');
        $("#crn_btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
        $('#case_ref_no_form').data('bootstrapValidator').resetForm(true);
    });

    // Button edit form to open the modal with edit form
    function btn_edit(event) {

        // Change the submit button to edit
        $('#crn_btn_submit').attr('disabled', false);
        $("#crn_btn_submit").html('<span class="fa fa-edit"></span> Update');

        // Reset the form
        $('#case_ref_no_form').trigger('reset');

        // Change the op type
        $("#crn_op_type").val("EDIT_CASE_REF_NO");

        // Get data table instance to get the data through row
        var oTable = $('#case_ref_no_datatable').dataTable();
        var row;
        if (event.target.tagName == "BUTTON")
            row = event.target.parentNode.parentNode;
        else if (event.target.tagName == "SPAN")
            row = event.target.parentNode.parentNode.parentNode;

        var id = oTable.fnGetData(row)['id'];
        var category_name = oTable.fnGetData(row)['category_name'];

        $('#crn_hidden_id').val(id);
        $('#crn_category_name').val(category_name);

        $('.pc-modal-title').html('<span class="fa fa-edit"></span> Edit Panel Category');
        $('#case_ref_no_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }


    // Add panel category list form
    $('#case_ref_no_form').bootstrapValidator({
        message: 'This value is not valid',
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {

            var formData = new FormData(document.getElementById("case_ref_no_form"));

            if ($('#crn_op_type').val() == 'EDIT_CASE_REF_NO') {
                urls = base_url + "case-ref-no-setup/update";
            } else {
                urls = base_url + "case-ref-no-setup/store";
            }

            $.ajax({
                url: urls,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // try {
                    var obj = JSON.parse(response);

                    if (obj.status == true) {
                        //Reseting form
                        $('#case_ref_no_form').trigger('reset');
                        toastr.success(obj.msg);

                        // Redraw the datatable
                        case_ref_no_datatable = $('#case_ref_no_datatable').DataTable();
                        case_ref_no_datatable.draw();
                        case_ref_no_datatable.clear();

                        $('#case_ref_no_modal').modal("hide");
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
                    toastr.error("unable to save");
                }
            });
        },
        fields: {
            type: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            start_index: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            },
            end_index: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                }
            }
        }
    })


    // Delete function for panel category list
    function delete_case_ref_no(id) {
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
                        url: base_url + 'service/delete_panel_category/DELETE_PANEL_CATEGORY',
                        type: 'POST',
                        data: {
                            id: id,
                            csrf_trans_token: $('#csrf_crn_form_token').val()
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

                                    dataTableCauseList = $('#case_ref_no_datatable').DataTable();
                                    dataTableCauseList.draw();
                                    dataTableCauseList.clear();
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

    // Function to enable the submit button
    function enable_submit_btn(id) {
        $('#' + id).attr('disabled', false);
    }
</script>