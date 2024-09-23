<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">
                <fieldset class="fieldset">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label class="control-label">Arbitrator Name:</label>
                                <input type="text" class="form-control" id="arbitrator_name" name="arbitrator_name" />
                            </div>

                            <div class="form-group col-md-3 col-sm-6 col-xs-12 text-center">
                                <button type="button" class="btn btn-default" id="acc_f_btn_reset" name="acc_f_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>
                                <button type="submit" class="btn btn-info" id="acc_f_btn_submit" name="acc_f_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <table id="datatableArbitratorCaseCountReport" class="table  table-striped table-bordered dt-responsive" data-page-size="10">
                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('datatableArbitratorCaseCountReport'); ?>">
                    <thead>
                        <tr>
                            <th style="width:7%;">S. No.</th>
                            <th>Name of Arbitrator</th>
                            <th>Category</th>
                            <th>Case Status</th>
                            <th>Case Count</th>
                        </tr>
                    </thead>
                </table>
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
    var datatableArbitratorCaseCountReport = $("#datatableArbitratorCaseCountReport").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        searching: false,
        order: [],
        ajax: {
            url: base_url + "reports/arbitrator-case-status-count/datatable",
            type: "POST",
            data: function(d) {
                d.csrf_trans_token = $("#csrf_trans_token").val();
                d.arbitrator_name = $('#arbitrator_name').val();
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
                data: "name_of_arbitrator"
            },
            {
                data: "category_name"
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return (data.case_count != 0) ? data.case_status : '<span class="badge bg-danger">N/A</span>';
                },
            },
            {
                data: "case_count"
            },
        ],
        lengthMenu: [
            [20, 50, 100, 1000],
            [20, 50, 100, 1000]
        ],
        columnDefs: [{
            targets: ["_all"],
            orderable: false,
            sorting: false,
        }, ],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [],
        drawCallback: function() {
            $("body").tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        },
    });


    $("#acc_f_btn_submit").on("click", function() {
        datatableArbitratorCaseCountReport.ajax.reload();
    });

    $("#acc_f_btn_reset").on("click", function() {
        $("#arbitrator_name").val("");
        datatableArbitratorCaseCountReport.ajax.reload();
    });
</script>