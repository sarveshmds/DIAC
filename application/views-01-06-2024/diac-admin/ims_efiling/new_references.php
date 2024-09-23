<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div class="text-right">
                            <a href="<?= base_url('add-new-case') ?>" class="btn btn-default">
                                Go to register case <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div>
                            <table id="datatable_document_pleadings" class="table table-condensed table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Diary No.</th>
                                        <th>DIAC Reg. No.</th>
                                        <th>Case Type.</th>
                                        <th>Type of Arbitration</th>
                                        <th>Department Remarks</th>
                                        <th>Application Status</th>
                                        <th>Noting</th>
                                        <th width="12%">Action</th>
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
<!-- View modal -->
<div class="modal fade" id='change_status_modal' role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" class="text-right">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class='modal-body'>
                <div class="box-body">
                    <form action="" id="change_status_form">
                        <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm'); ?>">
                        <input type="hidden" name="hidden_beneficiary_code" id="cs_hidden_beneficiary_code">
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label for="application_status">Status</label>
                                <select name="application_status" id="application_status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">Approved</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-12">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="cs_remarks" rows="4"></textarea>
                            </div>
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-custom" id="btn_submit"><i class="fa fa-paper-plane"></i> Save Details</button>
                            </div>
                        </div>
                    </form>
                </div>
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
</script>
<script>
    $(document).ready(function() {
        var datatable_document_pleadings = $("#datatable_document_pleadings").dataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            paging: true,
            info: true,
            autoWidth: false,
            scrollX: false,
            responsive: false,
            searching: false,
            ajax: {
                url: base_url + "ims/efiling/new_reference/get-datatable-list",
                type: "POST",
                data: function(data) {
                    data.csrf_prl_form_token = $("#csrf_prl_form_token").val();
                },
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },

                {
                    data: "diary_number"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return (data.case_no) ? data.case_no : '<span class="badge badge-primary">Not Alloted</span>';
                    },
                },
                {
                    data: "type_of_arbitration_desc"
                },
                {
                    data: "case_type_desc"
                },
                {
                    data: "remarks"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var spanClass = 'badge-default';
                        var application_status = 'Initiated';
                        if (data.application_status == 1) {
                            spanClass = 'bg-green';
                            application_status = 'Approved';
                        }
                        if (data.application_status == 2) {
                            spanClass = 'bg-blue';
                            application_status = 'Pending';
                        }
                        if (data.application_status == 3) {
                            spanClass = 'bg-red';
                            application_status = 'Rejected';
                        }
                        return "<span class='badge " + spanClass + "'>" + application_status + "</span>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return "<a href='" + base_url + "ims/other-notings?noting_group=" + customURIEncode('EFILING') + "&type_code=" + customURIEncode(data.nr_code) + "&type_name=" + customURIEncode('NEW_REFERENCE') + "' class='btn bg-maroon btn-sm'> Click <i class='fa fa-arrow-right'></i></a>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var button = '';
                        if (role_code == 'CASE_FILER') {
                            button = "<button class='btn_change_status btn btn-success btn-sm' type='button' data-beneficiary-code='" + data.nr_code + "' title='Approve/Reject'><i class='fa fa-check-square'></i></button>";
                        }
                        return button + " <a href='" + base_url + "public/upload/efiling/new_reference/case_doc/" + data.document + "' class='action-icon tooltipTable tooltipTable btn btn-warning btn-sm' title='View Document' target='_BLANK'><i class='fa fa-file'></i></a> <a href='" + base_url + "ims/new-reference/view?nr_code=" + encodeURI(btoa(data.nr_code)) + "' class='action-icon tooltipTable tooltipTable btn btn btn-custom btn-sm' title='View' ><i class='fa fa-eye'></i> </a>   ";
                    },
                },
            ],
            columnDefs: [{
                targets: [0],
                orderable: false,
                sorting: false
            }],
        });
    })

    $(document).on('click', '.btn_change_status', function() {
        var beneficiary_code = $(this).data('beneficiary-code');
        console.log(beneficiary_code)
        if (beneficiary_code) {
            $('#cs_hidden_beneficiary_code').val(beneficiary_code)
            $('#change_status_modal').modal('show');
        }
    })

    $("#change_status_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#btn_submit").attr("disabled", "disabled");
            var formData = new FormData(document.getElementById("change_status_form"));

            $.ajax({
                url: base_url + "change_new_reference_status",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                complete: function() {
                    $("#btn_submit").removeAttr("disabled");
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == false) {
                        $("#btn_submit").removeAttr("disabled");

                        swal({
                            title: "Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else if (obj.status === "validation_error") {

                        swal({
                            title: "Validation Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else {

                        swal({
                                title: "Success",
                                text: obj.msg,
                                type: "success",
                                html: true,
                            },
                            function() {
                                window.location.reload();
                            }
                        );
                    }
                    try {} catch (e) {
                        sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                    }
                },
                error: function(err) {
                    toastr.error("unable to save");
                },
            });
        },
        fields: {
            application_status: {
                //form input type name
                validators: {
                    notEmpty: {
                        message: "Required",
                    },
                },
            },
        },
    });
</script>