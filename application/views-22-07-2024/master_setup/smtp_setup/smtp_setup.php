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
                    <table id="smtp_setup_datatable" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                        <input type="hidden" name="csrf_trans_token" id="mcs_form_token" value="<?php echo generateToken('smtp_setup_datatable'); ?>">
                        <thead>
                            <tr>
                                <th style="width:8%;">S. No.</th>
                                <th>Provider Name</th>
                                <th>Host Name</th>
                                <th>Port No.</th>
                                <th>Email ID</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Smtp Auth</th>
                                <th>Smtp Secure</th>
                                <th>CC Email ID</th>
                                <th>Status</th>
                                <th style="width:15%;">Action</th>
                            </tr>
                        </thead>
                    </table>
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

    // Datatable initialization
    // =====================================================
    // Datatable for empanelled arbitrator
    var smtp_setup_datatable = $('#smtp_setup_datatable').DataTable({
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
            url: base_url + "smtp-setup/get-datatable-data",
            type: 'POST',
            data: function(d) {
                d.csrf_trans_token = $('#mcs_form_token').val();
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: "provider_name"
            },
            {
                data: "host_name"
            },
            {
                data: 'port_no'
            },
            {
                data: "email_id"
            },
            {
                data: "username"
            },
            {
                data: "password"
            },
            {
                data: 'smtp_auth'
            },
            {
                data: 'smtp_secure'
            },
            {
                data: 'cc_email_id'
            },
            {
                data: null,
                sClass: "text-center",
                render: function(data, type, row, meta) {
                    let status_badge = '';
                    if (data.record_status == 1) {
                        status_badge = `<span class="badge bg-green">Active</span>`
                    }
                    if (data.record_status == 0) {
                        status_badge = `<span class="badge bg-red">InActive</span>`
                    }

                    return status_badge;
                },
            },
            {
                data: null,
                sClass: "text-center",
                render: function(data, type, row, meta) {

                    return "<a href='" + base_url + "smtp-setup/edit?provider_id=" + data.provider_id + "' class='action-icon tooltipTable tooltipTable btn btn btn-primary btn-sm' title='Edit' ><i class='fa fa-edit'></i> Edit</a>";
                },
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
            text: '<span class="fa fa-plus"></span> Add New',
            className: 'btn btn-custom',
            init: function(api, node, config) {
                $(node).removeClass('dt-button');
            },
            action: function(e, dt, node, config) {
                window.location.href = base_url + 'smtp-setup/add';
            }
        }],

        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });
</script>