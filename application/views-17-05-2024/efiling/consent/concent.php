<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/consents/add') ?>" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> Add Consents
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
                <div class="mb-3 alert alert-info">
                    <p class="mb-1">
                        Note: If you want to physically sign the consent/declaration, download the format and fill up the form and send it to DIAC.
                    </p>
                    <a href="<?= base_url('public/download/consent_format.pdf') ?>" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-download"></i> Download Format</a>
                </div>
                <table id="datatable_consents" class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Diary No.</th>
                            <th>Case No.</th>
                            <th>Case Title</th>
                            <th>Status</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var datatable_applications = $("#datatable_consents").dataTable({
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
                url: base_url + 'efiling/consent/get-datatable-list',
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
                        if (data.application_status == 2) {
                            button = "<a href='" + base_url + "efiling/consent/edit?id=" + data.id + "' class='action-icon tooltipTable tooltipTable btn btn btn-info btn-sm'  title='Edit'><i class='fa fa-edit'></i></a>";
                        }

                        return "<a href='" + base_url + "efiling/consent/view?id=" + data.id + "' class='action-icon tooltipTable tooltipTable btn btn-success btn-sm' title='View Details'><i class='fa fa-eye'></i></a> " + button + "  <a href='" + base_url + "public/upload/efiling/consent/" + data.upload + "' class='action-icon tooltipTable tooltipTable btn btn-warning btn-sm' title='View Document' target='_BLANK'><i class='fa fa-file'></i></a>";
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