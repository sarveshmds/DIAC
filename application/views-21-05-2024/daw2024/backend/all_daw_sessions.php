<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<style>
    .pdf_button {
        background-color: red;
        color: white;
    }
</style>
<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatable_daw_live_sessions" class="table table-condensed table-striped table-bordered nowrap" data-page-size="10">
                                <input type="hidden" id="csrf_crl_trans_token" value="<?php echo generateToken('datatable_daw_live_sessions'); ?>" />

                                <thead>
                                    <tr>
                                        <th style="width:5%;">S. No.</th>
                                        <th>Action</th>
                                        <th>Session Date</th>
                                        <th>Session Name</th>
                                        <th>Seesion Time</th>
                                        <th>Session Venue</th>
                                        <th>Session Status</th>
                                        <th>Session Live Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Live Session Modal -->
<div id="live_session_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cd-modal-title" style="text-align: center;">Sessions</h4>
            </div>
            <div class="modal-body">

                <?= form_open(null, array('class' => 'wfst', 'id' => 'live_session_form')) ?>

                <input type="hidden" id="ls_op_type" name="op_type" value="ADD_LIVE_SESSION">

                <input type="hidden" id="hidden_ls_code" name="hidden_ls_code" value="">

                <input type="hidden" name="csrf_ls_form_token" value="<?php echo generateToken('live_session_form'); ?>">

                <div id="cd_wrapper">
                    <div class="row">
                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Date:</label>
                            <select name="session_date" id="session_date" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($session_dates as $sd) : ?>
                                    <option value="<?= $sd['code'] ?>"><?= $sd['session_date'] . '' . $sd['date_sup'] . ' ' . $sd['date_month'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Name:</label>
                            <input type="text" class="form-control" id="session_name" name="session_name" autocomplete="off" value="" />
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Time:</label>
                            <input type="text" class="form-control" id="session_time" name="session_time" autocomplete="off" value="" />
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Venue:</label>
                            <input type="text" class="form-control" id="session_venue" name="session_venue" autocomplete="off" value="" />
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Status:</label>
                            <select name="session_status" id="session_status" class="form-control">
                                <option value="">Select</option>
                                <option value="LIVE">Live Session</option>
                                <option value="UPCOMING">Upcoming</option>
                                <option value="ARCHIVE">Archive</option>
                            </select>
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Session Live Link:</label>
                            <input type="text" class="form-control" id="session_link" name="session_link" autocomplete="off" value="" />
                        </div>
                    </div>
                </div>

                <div class="row text-center mt-25">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom ls_btn_submit" id="ls_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
                    </div>
                </div>
                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>
<!-- Cost deposit Modal end -->

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
    function add_live_session() {
        $('#hidden_ls_code').val('')
        $("#ls_btn_submit").attr("disabled", false);
        $("#ls_btn_submit").html("<i class='fa fa-save'></i> Save Details");

        // Reset the form
        $("#live_session_form").trigger("reset");

        // Change the op type
        $("#ls_op_type").val("ADD_LIVE_SESSION");
        $('#live_session_modal').modal('show')
    }


    // Button edit form to open the modal with edit form
    function btnEditLiveSessionDetails(event) {
        // Change the submit button to edit
        $("#ls_btn_submit").attr("disabled", false);
        $("#ls_btn_submit").html("<i class='fa fa-edit'></i> Update Details");

        // Reset the form
        $("#live_session_form").trigger("reset");

        // Change the op type
        $("#ls_op_type").val("EDIT_LIVE_SESSION");

        // Get data table instance to get the data through row
        var oTable = $("#datatable_daw_live_sessions").dataTable();
        var row;
        if (event.target.tagName == "BUTTON")
            row = event.target.parentNode.parentNode;
        else if (event.target.tagName == "SPAN")
            row = event.target.parentNode.parentNode.parentNode;

        $("#hidden_ls_code").val(oTable.fnGetData(row)["code"]);
        $('#session_date option[value="' + oTable.fnGetData(row)["dsdc_code"] + '"]').prop("selected", true);

        $("#session_name").val(oTable.fnGetData(row)["session_name"]);
        $("#session_time").val(oTable.fnGetData(row)["session_time"]);
        $("#session_venue").val(oTable.fnGetData(row)["session_venue"]);
        $('#session_status option[value="' + oTable.fnGetData(row)["session_status"] + '"]').prop("selected", true);

        $("#session_link").val(oTable.fnGetData(row)["session_live_link"]);

        // Open the modal
        $("#live_session_modal").modal({
            backdrop: "static",
            keyboard: false,
        });
    }

    // Datatable initialization
    var dataTable = $("#datatable_daw_live_sessions").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: false,
        searching: false,
        order: [],
        ajax: {
            url: base_url + 'daw-2024/get-all-live-sessions',
            type: "POST",
            data: function(d) {
                d.csrf_trans_token = $("#csrf_trans_token").val();
            },
            dataSrc: function(response) {
                if (response.status == false) {
                    toastr.error(response.msg);
                    return false;
                } else {
                    return response.data;
                }
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
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    return (
                        '<button type="button" class="btn btn-custom btn-sm" data-tooltip="tooltip" title="Edit Details" onclick="btnEditLiveSessionDetails(event)"><span class="fa fa-edit"></span></button> '
                    );
                },
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return data.session_date + '' + data.date_sup + ' ' + data.date_month;
                },
            },
            {
                data: "session_name"
            },
            {
                data: "session_time"
            },
            {
                data: "session_venue"
            },

            {
                data: null,
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    var app_status_desc = '';
                    if (data.session_status == 'LIVE') {
                        app_status_desc = '<span class="badge bg-green">Live Session</span>';
                    }
                    if (data.session_status == 'UPCOMING') {
                        app_status_desc = '<span class="badge bg-blue">Upcoming</span>';
                    }
                    if (data.session_status == 'ARCHIVE') {
                        app_status_desc = '<span class="badge bg-red">Archive</span>';
                    }
                    return app_status_desc;
                },
            },
            {
                data: "session_live_link"
            },

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
            text: '<span class="fa fa-plus"></span> Add Session',
            className: "btn btn-success",
            init: function(api, node, config) {
                $(node).removeClass("dt-button");
            },
            action: function(e, dt, node, config) {
                add_live_session();
            },
        }, ],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
    });

    $('#btn_filter').on('click', function() {
        dataTable.ajax.reload();
    })

    $('#btn_reset').on('click', function() {
        $("#f_name").val('');
        $("#f_mobile_no").val('');
        $("#f_registrant_category option").prop('selected', false);
        $("#f_country option").prop('selected', false);
        dataTable.ajax.reload();
    })

    // =============================================
    $("#live_session_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#ls_btn_submit").attr("disabled", "disabled");
            var formData = new FormData(document.getElementById("live_session_form"));

            $.ajax({
                url: base_url + "daw-2024/live-sessions/store",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                complete: function() {
                    // Enable the submit button
                    enable_submit_btn("ls_btn_submit");
                },
                success: function(response) {
                    try {
                        var obj = JSON.parse(response);
                        if (obj.status == false) {

                            swal({
                                title: "Error",
                                text: obj.msg,
                                type: "error",
                                html: true,
                            });
                        } else if (obj.status === "validationerror") {
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
                    } catch (e) {
                        sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                    }
                },
                error: function(err) {
                    // Enable the submit button
                    enable_submit_btn("ls_btn_submit");
                    toastr.error("unable to save");
                },
            });
        },
        fields: {
            session_date: {
                validators: {
                    notEmpty: {
                        message: "Required",
                    },
                },
            },
        },
    });
</script>