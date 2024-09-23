<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div>
                            <h4>
                                Click here to view the details for noting.
                            </h4>
                            <button class="btn btn-warning" id="btn-view-details">
                                View Details
                            </button>
                        </div>

                    </div>
                </div>

                <div class="box wrapper-box">
                    <div class="box-body">
                        <div>
                            <table id="messages-datatable" class="table table-condensed table-bordered dt-responsive" data-page-size="10">
                                <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('messages-datatable'); ?>">
                                <input type="hidden" id="hidden_on_code" name="hidden_on_code" value="<?= $on_code ?>">

                                <thead>
                                    <tr>
                                        <th width="40%">Message</th>
                                        <th>Message Date</th>
                                        <th>Sent By</th>
                                        <th>Sent To</th>
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

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/other-noting-content-details.php'); ?>

<?php require_once(APPPATH . 'views/modals/diac-admin/other-noting-message.php'); ?>

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
    $('#btn-view-details').on('click', function() {
        $('#on_content__modal').modal('show')
    })

    function add_onm_modal_open() {
        $('#onm_modal').modal('show')
    }


    var messages_datatable = $("#messages-datatable").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        order: [],
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        ajax: {
            url: base_url + "service/get_all_other_noting_messages_datatable",
            type: "POST",
            data: function(d) {
                d.csrf_trans_token = $("#csrf_ca_trans_token").val();
                d.hidden_on_code = $("#hidden_on_code").val();
            },
        },
        columns: [{
                data: "message"
            },
            {
                data: "message_date"
            },
            {
                data: "sent_by_desc"
            },
            {
                data: "sent_to_desc"
            },
        ],
        columnDefs: [{
            targets: '_ALL',
            orderable: false,
            sorting: false,
        }, ],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            text: '<span class="fa fa-plus"></span> Send Message',
            className: "btn btn-custom",
            init: function(api, node, config) {
                $(node).removeClass("dt-button");
            },
            action: function(e, dt, node, config) {
                add_onm_modal_open();
            },
        }, ],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
        "createdRow": function(row, data, dataIndex) {
            if (data.sent_to == '<?= $this->session->userdata('user_code') ?>') {
                $(row).addClass('bg-danger');
            }
        }
    });


    // On closing the modal reset the form
    $("#onm_modal").on("hidden.bs.modal", function(e) {
        $("#message_form").trigger("reset");

        $("#message_op_type").val("");
        $("#message_text").summernote("code", "");

        $("#send_to option").prop("selected", false);
        $("#send_to").select2();

        $("#message_btn_submit")[0].innerHTML =
            "<i class='fa fa-paper-plane'></i> Send";
        $("#message_form").data("bootstrapValidator").resetForm(true);
    });


    // Add case noting form
    $("#message_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#message_btn_submit").attr("disabled", "disabled");

            var formData = new FormData(document.getElementById("message_form"));
            urls = base_url + "service/other_noting_message_operation";

            $.ajax({
                url: urls,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                complete: function() {
                    // Enable the submit button
                    $("#message_btn_submit").attr("disabled", false);
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
                            //Reseting user form
                            $("#message_form").trigger("reset");
                            toastr.success(obj.msg);

                            // Redraw the datatable
                            messages_datatable = $("#messages-datatable").DataTable();
                            messages_datatable.draw();
                            messages_datatable.clear();

                            $("#onm_modal").modal("hide");
                        }
                    } catch (e) {
                        sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                    }
                },
                error: function(err) {
                    toastr.error("unable to save");
                },
            });
        },
        fields: {},
    });
</script>