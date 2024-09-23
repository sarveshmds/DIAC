<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->
        <div class="alert alert-info">
            <p class="mb-0">
                <strong>Information:</strong> If your case is not listed here, you can raise a query regarding this. <a href="<?= base_url('efiling/requests/add') ?>">Click Here</a>
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="datatable_my_cases" class="table align-items-center mb-0 custom-table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>DIAC Reg. No.</th>
                            <th width="25%">Case Title</th>
                            <th>Type of Arbitration</th>
                            <th>Case Type</th>
                            <th>Registered On</th>
                            <th>Registration Type</th>
                            <th>Case Status</th>
                            <th width="16%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var datatable_applications = $("#datatable_my_cases").dataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: base_url + "efiling/mycases/get-datatable-list",
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
                    data: "case_no_desc"
                },
                {
                    data: "case_title",
                },
                {
                    data: "type_of_arbitration"
                },
                {
                    data: "di_type_of_arbitration"
                },
                {
                    data: "registered_on"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return "<span class='badge bg-muted' >" + data.type.toUpperCase() + "</span>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return "<span class='badge bg-orange' >" + data.case_status + "</span>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        return "<a href='" + base_url + "efiling/view-case-fees/" + data.case_no + "' class='btn btn-warning btn-sm'  title='Fees'><i class='fa fa-money'></i></a> <a href='" + base_url + "efiling/view-case-details/" + data.case_no + "' class='btn btn-success btn-sm'  title='View'><i class='fa fa-eye'></i></a> <a href='" + base_url + "efiling/case/hearings?case_no=" + data.case_no + "' class='btn btn-info btn-sm'  title='Case Hearings'><i class='fa fa-paper-plane'></i> </a>";
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
</script>