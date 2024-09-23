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

                        <div>
                            <table id="datatable_applications" class="table table-condensed table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="8%">S. No.</th>
                                        <th>Diary No.</th>
                                        <th>Case No.</th>
                                        <th>Case Title</th>
                                        <th>Application Type</th>
                                        <th>Filed Type</th>
                                        <th>On Behalf Of</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th>Noting</th>
                                        <th width="15%">Action</th>
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
                        <input type="hidden" name="hidden_beneficiary_id" id="cs_hidden_beneficiary_id">
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
        var datatable_applications = $("#datatable_applications").dataTable({
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
                url: base_url + "ims/efiling/application/get-datatable-list",
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
                    data: "case_no_desc"
                },
                {
                    data: 'case_title_desc'
                },
                {
                    data: "app_type_desc"
                },
                {
                    data: "filing_type_desc"
                },
                {
                    data: "behalf_of_desc"
                },
                {
                    data: "remarks"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var spanClass = 'badge-info';
                        var application_status = 'Pending';
                        if (data.application_status == 1) {
                            spanClass = 'badge-success';
                            application_status = 'Approved';
                        }
                        if (data.application_status == 3) {
                            spanClass = 'badge-danger';
                            application_status = 'Rejected';
                        }
                        return "<span class='badge " + spanClass + "'>" + application_status + "</span>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return "<a href='" + base_url + "ims/other-notings?noting_group=" + customURIEncode('EFILING') + "&type_code=" + customURIEncode(data.a_code) + "&type_name=" + customURIEncode('APPLICATION') + "' class='btn bg-maroon btn-sm'> Click <i class='fa fa-arrow-right'></i></a>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var button = '';
                        if (role_code == 'CASE_FILER') {
                            button = "<button class='btn_change_status btn btn-success btn-sm' type='button' data-beneficiary-id='" + data.id + "'title='Approve/Reject'><i class='fa fa-check-square'></i></button> ";
                        }
                        return button + " <a href='" + base_url + "public/upload/efiling/applications/" + data.upload + "' class='action-icon tooltipTable tooltipTable btn btn-primary btn-sm' title='View Document' target='_BLANK'><i class='fa fa-file'></i></a>";
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
        var beneficiaryId = $(this).data('beneficiary-id');
        console.log(beneficiaryId)
        if (beneficiaryId) {
            $('#cs_hidden_beneficiary_id').val(beneficiaryId)
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
                url: base_url + "change_application_status",
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