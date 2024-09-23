<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/document/add') ?>" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> Add Document
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
                <div class="table-responsive">
                    <table id="datatable_my_document" class="table align-items-center mb-0 table-bordered">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Diary No.</th>
                                <th>Case No.</th>
                                <th>Case Title</th>
                                <th>Type of document</th>
                                <th>On Behalf Of</th>
                                <th>Name (On Behalf Of)</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Department Remarks</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var datatable_applications = $("#datatable_my_document").dataTable({
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
                url: base_url + "efiling/document/get-datatable-list",
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
                    data: "case_no_desc"
                },
                {
                    data: "case_title"
                },
                {
                    data: "type_of_document_desc"
                },
                {
                    data: "behalf_of_desc"
                },
                {
                    data: "name"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var spanClass = 'bg-info';
                        var application_status = 'Pending';
                        if (data.application_status == 1) {
                            spanClass = 'bg-success';
                            application_status = 'Approved';
                        }
                        if (data.application_status == 3) {
                            spanClass = 'bg-danger';
                            application_status = 'Rejected';
                        }
                        return "<span class='badge " + spanClass + "'>" + application_status + "</span>";
                    },
                },
                {
                    data: "remarks"
                },
                {
                    data: "department_remarks"
                },
                {
                    data: null,
                    sClass: "text-center",
                    render: function(data, type, row, meta) {
                        var button = '';
                        if (data.application_status == 2) {
                            button = "<a href='" + base_url + "efiling/document/edit?id=" + data.id + "' class='action-icon tooltipTable tooltipTable btn btn btn-info btn-sm'  id='edit' title='Edit'><i class='fa fa-edit'></i> Edit</a>";
                        }
                        return button + " <a href='" + base_url + "public/upload/efiling/documents/" + data.upload + "' class='action-icon btn btn-warning btn-sm' title='View Document' target='_BLANK'><i class='fa fa-file'></i> File</a>";
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