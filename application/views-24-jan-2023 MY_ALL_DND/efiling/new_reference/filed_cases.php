<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/file-new-case') ?>" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> File New Reference
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body">
                <table id="datatable_my_cases" class="table table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th width="8%">S. No.</th>
                            <th>Diary No.</th>
                            <th>Type of arbitration</th>
                            <th>Case Type</th>
                            <th>Filed On</th>
                            <th>Claim Amount</th>
                            <!-- <th>Your Payable Share</th> -->
                            <th>Payment Status</th>
                            <th>Application Status</th>
                            <th width="15%">Action</th>
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
    $('#datatable_my_cases').DataTable()
</script>


<script>
    $(document).ready(function() {
        var datatable_my_cases = $("#datatable_my_cases").dataTable({
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
                url: base_url + "efiling/new-references/get-datatable-list",
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
                    data: "type_of_arbitration_desc"
                },
                {
                    data: "case_type_desc"
                },
                {
                    data: "filed_on"
                },
                {
                    data: "claim_amount"
                },
                // {
                //     data: "your_payable_share"
                // },
                {
                    data: "payment_status_desc"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var spanClass = 'bg-gradient-secondary';
                        var application_status = 'Initiated';

                        if (data.application_status == 1) {
                            spanClass = 'bg-gradient-success';
                            application_status = 'Approved';
                        }
                        if (data.application_status == 2) {
                            spanClass = 'bg-gradient-info';
                            application_status = 'Pending';
                        }
                        if (data.application_status == 3) {
                            spanClass = 'bg-gradient-danger';
                            application_status = 'Rejected';
                        }
                        return "<span class='badge " + spanClass + "'>" + application_status + "</span>";
                    },
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var button = '';
                        if (data.application_status == 0) {
                            button = "<a href='" + base_url + "efiling/new-reference/edit?id=" + data.id + "' class='action-icon tooltipTable tooltipTable btn btn btn-info btn-sm'  id='edit' title='Edit'><i class='fa fa-edit'></i></a>";
                        }
                        return "<a href='" + base_url + "efiling/new-reference/view?id=" + encodeURI(btoa(data.id)) + "' class='action-icon tooltipTable tooltipTable btn btn btn-success btn-sm' title='View'><i class='fas fa-eye'></i></a> " + button + " <a href='" + base_url + "public/upload/efiling/new_reference/" + data.document + "' class='action-icon tooltipTable tooltipTable btn btn-warning btn-sm' title='View Document' target='_BLANK'><i class='fa fa-file'></i></a>";
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