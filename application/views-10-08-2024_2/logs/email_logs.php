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
                        <div class="table-responsive">
                            <table id="email_logs_datatable" class="table table-striped table-bordered w-100 nowrap">
                                <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('email_logs_datatable'); ?>">
                                <thead>
                                    <tr>
                                        <th style="width:7%;">S. No.</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Sent On</th>
                                        <th width="25%">To Mail</th>
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
    // Approved Application: Datatable initialization
    var dataTable = $("#email_logs_datatable").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        order: [],
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        ajax: {
            url: base_url + "email-logs/get-for-datatable",
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
                data: "mail_type"
            },

            {
                data: "subject"
            },
            {
                data: null,
                name: "status",
                render: function(data, type, row, meta) {
                    let status = "";
                    if (data.status == 'SUCCESS') {
                        status = "<span class='badge bg-green'>Success</span>"
                    }
                    if (data.status == 'FAILED') {
                        status = "<span class='badge bg-red'>Failed</span>"
                    }
                    return status;
                },
            },
            {
                data: "sent_on",
                name: "created_on"
            },
            {
                data: null,
                name: "to_mail",
                sWidth: '25%',
                render: function(data, type, row, meta) {
                    return "<div>" + data.to_mail + "</div>";
                },
            },
        ],
        columnDefs: [{
            targets: ["_all"],
            orderable: false,
            sorting: false,
        }, ],
        buttons: [],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
    });
</script>