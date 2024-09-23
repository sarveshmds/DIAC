<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/requests/add') ?>" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> Make Request
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->
        <div class="alert alert-info">
            <p class="mb-0">
                <strong>Information:</strong> You can make any kind of request to DIAC like adding your mobile number in particular case or changing your mobile number.
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable_my_cases" class="table table-bordered align-items-center mb-0 w-100 custom-table">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th>Diary No.</th>
                                <th width="15%">Request Title</th>
                                <th width="30%">Message</th>
                                <th>Case No.</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th width="12%">Action</th>
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


<script>
    $(document).ready(function() {
        var datatable_applications = $("#datatable_my_cases").dataTable({
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
                url: base_url + "efiling/request/get-datatable-list",
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
                    data: "request_title"
                },
                {
                    data: "message"
                },
                {
                    data: "case_number"
                },
                {
                    data: "remarks"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var spanClass = 'bg-gradient-info';
                        var application_status = 'Pending';
                        if (data.application_status == 1) {
                            spanClass = 'bg-gradient-success';
                            application_status = 'Approved';
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
                        // if (data.application_status == 2) {
                        //     button = "<a href='" + base_url + "efiling/requests/edit?id=" + data.id + "' class='action-icon tooltipTable tooltipTable btn btn btn-info btn-sm'  id='edit' title='Edit'><i class='fa fa-edit'></i></a>";
                        // }

                        // return button;
                        var document_button = '<span class="badge badge-secondary">No File</span>';
                        if (data.document) {
                            document_button = "<a href='" + base_url + "public/upload/efiling/requests/" + data.document + "' class='action-icon tooltipTable tooltipTable btn btn-warning btn-sm' title='View File' target='_BLANK'><i class='fa fa-file'></i> File</a>";
                        }
                        return button + " " + document_button;
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