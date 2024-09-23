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

        <div class="card">
            <div class="card-body">
                <table id="datatable_case_hearings" class="table align-items-center mb-0 custom-table table-bordered">
                    <input type="hidden" name="hidden_case_no" id="hidden_case_no" value="<?= $case_details['case_no'] ?>">
                    <thead>
                        <tr>
                            <th style="width:7%;">S. No.</th>
                            <th>Case No.</th>
                            <th>Title of Case</th>
                            <th>Arbitrator Name</th>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Room No.</th>
                            <th>Status</th>
                            <th>Remarks</th>
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
        var datatable_applications = $("#datatable_case_hearings").dataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: base_url + "efiling/mycases/get-case-hearings",
                type: "POST",
                data: function(data) {
                    data.hidden_case_no = $("#hidden_case_no").val();
                },
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }

                },
                {
                    data: 'case_no'
                },
                {
                    data: 'title_of_case'
                },
                {
                    data: 'arbitrator_name'
                },
                {
                    data: 'purpose_category_name'
                },
                {
                    data: 'date'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        var time_to = (data.time_to) ? ' - ' + data.time_to : '';
                        return data.time_from + time_to;
                    }
                },
                {
                    data: 'room_no'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        var status = '';
                        if (data.active_status == 2) {
                            status = '<span class="badge bg-danger">' + data.active_status_desc + '</span>'
                        }
                        if (data.active_status == 1) {
                            status = '<span class="badge bg-success">' + data.active_status_desc + '</span>'
                        }
                        return status;
                    }
                },
                {
                    data: 'remarks'
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