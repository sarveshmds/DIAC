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
                        <div>
                            <fieldset class="fieldset" id="filters-fieldset">
                                <legend class="legend">Filters</legend>
                                <div class="col-xs-12 flex-row">
                                    <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                        <label for="" class="control-label">First Name</label>
                                        <input type="text" name="f_name" id="f_name" class="form-control">
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                        <label for="" class="control-label">Mobile Number</label>
                                        <input type="text" name="f_mobile_no" id="f_mobile_no" class="form-control">
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                        <label for="" class="control-label">Registrant Category</label>
                                        <select name="f_registrant_category" id="f_registrant_category" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($registrant_categories as $rc) : ?>
                                                <option value="<?= $rc['gen_code'] ?>"><?= $rc['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                        <label for="" class="control-label">Country</label>
                                        <select name="f_country" id="f_country" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12 mt-10 text-center">
                                        <a href="Javascript:void(0)" class="text-black font-weight-bold mr-10" id="btn_reset"><span class="fa fa-refresh"></span> Reset</a>
                                        <button class="btn btn-custom" id="btn_filter"><span class="fa fa-search"></span> Search</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable_daw_registrations" class="table table-condensed table-striped table-bordered nowrap" data-page-size="10">
                                <input type="hidden" id="csrf_crl_trans_token" value="<?php echo generateToken('datatable_daw_registrations'); ?>" />

                                <input type="hidden" name="dt_application_status" id="dt_application_status" value="<?= $application_status ?>">

                                <thead>
                                    <tr>
                                        <th style="width:5%;">S. No.</th>
                                        <th>Profile Photo</th>
                                        <th style="width:10%;">Action</th>
                                        <th>Application Status</th>
                                        <th>Reg No.</th>
                                        <th>Full Name</th>
                                        <th>Nick Name</th>
                                        <th>Mobile No.</th>
                                        <th>Email ID</th>
                                        <th>Registrant Category</th>
                                        <th>Organization</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>Hear About Event</th>
                                        <th>Date Of Registration</th>

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
    function generate_registrations_excel() {
        var f_name = $("#f_name").val();
        var f_mobile_no = $("#f_mobile_no").val();
        var f_registrant_category = $("#f_registrant_category").val();
        var f_country = $("#f_country").val();
        var dt_application_status = $("#dt_application_status").val();

        let form = document.createElement("form");
        let element1 = document.createElement("input");

        form.method = 'POST';
        form.target = '_BLANK';
        form.action = base_url + 'daw/registration/generate-excel';
        element1.name = 'filter_data';

        element1.value = JSON.stringify({
            filter_data: {
                f_name: f_name,
                f_mobile_no: f_mobile_no,
                f_registrant_category: f_registrant_category,
                f_country: f_country,
                dt_application_status: dt_application_status,
            }
        });

        form.appendChild(element1);

        document.body.appendChild(form);
        form.submit();
    }

    function generate_registrations_pdf() {
        var f_name = $("#f_name").val();
        var f_mobile_no = $("#f_mobile_no").val();
        var f_registrant_category = $("#f_registrant_category").val();
        var f_country = $("#f_country").val();
        var dt_application_status = $("#dt_application_status").val();

        let form = document.createElement("form");
        let element1 = document.createElement("input");

        form.method = 'POST';
        form.target = '_BLANK';
        form.action = base_url + 'daw/registration/generate-pdf';
        element1.name = 'filter_data';

        element1.value = JSON.stringify({
            filter_data: {
                f_name: f_name,
                f_mobile_no: f_mobile_no,
                f_registrant_category: f_registrant_category,
                f_country: f_country,
                dt_application_status: dt_application_status
            }
        });

        form.appendChild(element1);

        document.body.appendChild(form);
        form.submit();
        // window.open(base_url + 'daw/registration/generate-pdf', '_BLANK');
    }

    // Datatable initialization
    var dataTable = $("#datatable_daw_registrations").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: false,
        searching: false,
        order: [],
        ajax: {
            url: base_url + 'daw/get-all-registrations',
            type: "POST",
            data: function(d) {
                d.csrf_trans_token = $("#csrf_trans_token").val();
                d.f_name = $("#f_name").val();
                d.f_mobile_no = $("#f_mobile_no").val();
                d.f_registrant_category = $("#f_registrant_category").val();
                d.f_country = $("#f_country").val();
                d.dt_application_status = $('#dt_application_status').val();
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
                    return '<img src="' + base_url + 'public/upload/daw/profile_photo/' + data.profile_photo + '" width="60px" />';
                },
            },
            {
                data: null,
                sWidth: "10%",
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    return (
                        '<a href="' + base_url + 'daw/registration/view?id=' + data.id + '" type="button" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a> '
                    );
                },
            },
            {
                data: null,
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    var app_status_desc = '<span class="badge">Pending</span>';
                    if (data.application_status == 1) {
                        app_status_desc = '<span class="badge bg-green">Approved</span>';
                    }
                    if (data.application_status == 2) {
                        app_status_desc = '<span class="badge bg-red">Rejected</span>';
                    }
                    if (data.application_status == 3) {
                        app_status_desc = '<span class="badge bg-blue">Payment Pending</span>';
                    }
                    return app_status_desc;
                },
            },

            {
                data: "reg_number"
            },
            {
                data: null,
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    return data.salutation_desc + ' ' + data.first_name + ' ' + data.last_name;
                },
            },
            {
                data: "nick_name"
            },
            {
                data: null,
                sClass: "alignCenter",
                render: function(data, type, row, meta) {
                    return ((data.mobile_no_country_code_desc) ? data.mobile_no_country_code_desc + '-' : '') + data.mobile_no;
                },
            },
            {
                data: "email_address"
            },
            {
                data: "registrant_category_desc"
            },
            {
                data: "organization"
            },
            {
                data: "country_name"
            },
            {
                data: "state_name"
            },
            {
                data: "hear_about_event_desc"
            },
            {
                data: "created_at"
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
                text: '<span class="fa fa-file-excel-o"></span> Excel',
                className: "btn btn-success",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                action: function(e, dt, node, config) {
                    generate_registrations_excel();
                },
            },
            {
                text: '<span class="fa fa-file-pdf-o"></span> PDF',
                className: "btn btn-danger",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                action: function(e, dt, node, config) {
                    generate_registrations_pdf();
                },
            },
        ],
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
</script>