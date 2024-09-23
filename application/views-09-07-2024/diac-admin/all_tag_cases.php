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
                            <table id="datatable_tagged_cases" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('datatable_tagged_cases'); ?>">
                                <thead>
                                    <tr>
                                        <th style="width:8%;">S. No.</th>
                                        <th width="15%">Master Case</th>
                                        <th width="40%">Tagged Cases</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th style="width:10%;">Action</th>
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

<?php require_once(APPPATH . 'views/modals/diac-admin/tag_case_modal.php') ?>

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
<script type="text/javascript">
    // Datatable initialization
    var datatable_tagged_cases = $("#datatable_tagged_cases").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        searching: false,
        order: [],
        ajax: {
            url: base_url + 'tag-cases/all-tagged-case-datatable',
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
                render: function(data, type, row, meta) {
                    return `${data.case_no_prefix}/${data.case_no}/${data.case_no_year}`;
                }
            },
            {
                data: "tagged_cases"
            },
            {
                data: "user_display_name"
            },
            {
                data: "created_at"
            },
            {
                data: null,
                sWidth: "15%",
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    let delete_btn = '';
                    if (role_code == 'DIAC') {
                        delete_btn = '<button type="button" class="btn btn-danger btn-sm btn_tc_delete" data-tooltip="tooltip" data-master-case="' + data.master_case + '" title="Delete Record"><span class="fa fa-trash"></span></button>';
                    }
                    return (
                        '<button type="button" class="btn btn-warning btn-sm btn_tc_edit" data-tooltip="tooltip" data-master-case="' + data.master_case + '" title="Edit Details"><span class="fa fa-edit"></span></button> ' + delete_btn
                    );
                },
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
            text: '<span class="fa fa-plus"></span> Add New',
            className: "btn btn-custom",
            init: function(api, node, config) {
                $(node).removeClass("dt-button");
            },
            action: function(e, dt, node, config) {
                $('#tc_modal_title').text('Add Tag Case');
                $('#tc_op_type').val('ADD_TAG_CASE');
                $('#tc_master_case').val('').trigger('change');
                $('#tc_tagged_case').val('').trigger('change');
                $('#tag_case_form').trigger('reset');
                $('#tag_case_modal').modal('show');
            },
        }, ],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
    });


    // ==========================================================================================
    // Add tag case form
    $("#tag_case_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#tc_btn_submit").attr("disabled", "disabled");

            var formData = new FormData(document.getElementById("tag_case_form"));
            let url = '';
            if ($('#tc_op_type').val() == 'EDIT_TAG_CASE') {
                url = base_url + "tag-cases/update"
            } else {
                url = base_url + "tag-cases/store"
            }

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                complete: function() {
                    // Enable the submit button
                    enable_submit_btn("tc_btn_submit");
                },
                success: function(response) {
                    try {
                        var obj = JSON.parse(response);
                        if (obj.status == true) {
                            swal({
                                    title: "Success",
                                    text: obj.msg,
                                    type: "success",
                                    html: true,
                                },
                                function() {
                                    $('#tag_case_modal').modal('hide');
                                    datatable_tagged_cases.ajax.reload();
                                }
                            );

                        } else if (obj.status === "validationerror") {
                            swal({
                                title: "Validation Error",
                                text: obj.msg,
                                type: "error",
                                html: true,
                            });
                        } else {
                            swal({
                                title: "Error",
                                text: obj.msg,
                                type: "error",
                                html: true,
                            });
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
        fields: {
            tc_master_case: {
                //form input type name
                validators: {
                    notEmpty: {
                        message: "Required",
                    },
                },
            },
            tc_tagged_case: {
                validators: {
                    notEmpty: {
                        message: "Required",
                    },
                },
            },
        },
    });

    $(document).on('click', '.btn_tc_edit', function() {
        let master_case = $(this).data('master-case');

        if (master_case) {
            // Fetch the details
            $.ajax({
                url: base_url + 'tag-cases/get-single-details',
                type: 'POST',
                data: {
                    master_case: master_case,
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    let data = res.data;

                    $('#tc_modal_title').text('Edit Tag Case');
                    $('#tc_op_type').val('EDIT_TAG_CASE');
                    $('#tc_master_case').val(data[0].master_case).trigger('change');

                    $.each(data, function(index, row) {
                        $('#tc_tagged_case').val(row.tagged_case).trigger('change');
                    })

                    $('#tag_case_modal').modal('show');
                },
                error: function(error) {
                    toastr.error('Server error')
                }
            })
        }
    })

    $(document).on('click', '.btn_tc_delete', function() {
        let master_case = $(this).data('master-case');

        swal({
                title: "Are you sure?",
                text: "You want to delete this record.",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    if (master_case) {
                        // Fetch the details
                        $.ajax({
                            url: base_url + 'tag-cases/delete',
                            type: 'POST',
                            data: {
                                csrf_trans_token: $('#csrf_trans_token').val(),
                                master_case: master_case,
                            },
                            success: function(response) {
                                let obj = JSON.parse(response);
                                if (obj.status == true) {
                                    swal({
                                            title: "Success",
                                            text: obj.msg,
                                            type: "success",
                                            html: true,
                                        },
                                        function() {
                                            datatable_tagged_cases.ajax.reload();
                                        }
                                    );

                                } else if (obj.status === "validationerror") {
                                    swal({
                                        title: "Validation Error",
                                        text: obj.msg,
                                        type: "error",
                                        html: true,
                                    });
                                } else {
                                    swal({
                                        title: "Error",
                                        text: obj.msg,
                                        type: "error",
                                        html: true,
                                    });
                                }

                            },
                            error: function(error) {
                                toastr.error('Server error')
                            }
                        })
                    }
                } else {
                    swal.close()
                }
            }
        );

    })
</script>