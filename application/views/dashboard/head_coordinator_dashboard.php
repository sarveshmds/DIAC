<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content">

        <div class="row mb-15">
            <div class="col-lg-8 col-xs-12">

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="<?= base_url('referral-requests') ?>">
                            <div class="small-box bg-custom <?= ($dashboard_data['pending_refferal_req_count'] > 0) ? 'db-small-box-animation' : '' ?>">
                                <div class="inner">
                                    <h3><?= $dashboard_data['pending_refferal_req_count'] ?></h3>
                                    <p>Pending Refferals/Requests</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-building"></i>
                                </div>
                                <?php if (in_array($this->session->userdata('role'), ['CAUSE_LIST_MANAGER', 'DIAC', 'ADMIN'])) : ?>
                                    <a href="<?= base_url('rooms-list') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>

                                <?php else : ?>
                                    <span style="height: 26px;" class="small-box-footer"></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="small-box bg-custom">
                            <div class="inner">
                                <h3><?= $dashboard_data['total_rooms'] ?></h3>
                                <p>Total Rooms</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-building"></i>
                            </div>
                            <?php if (in_array($this->session->userdata('role'), ['CAUSE_LIST_MANAGER', 'DIAC', 'ADMIN'])) : ?>
                                <a href="<?= base_url('rooms-list') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>

                            <?php else : ?>
                                <span style="height: 26px;" class="small-box-footer"></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="small-box bg-custom">
                            <div class="inner">
                                <h3><?= $dashboard_data['total_case_count'] ?></h3>
                                <p>Total Cases</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-book"></i>
                            </div>
                            <a href="<?= base_url('all-cases') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="small-box bg-custom">
                            <div class="inner">
                                <h3><?= $dashboard_data['total_hearings_today'] ?></h3>
                                <p>Hearings today</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bank"></i>
                            </div>
                            <a href="<?= base_url('hearings-today') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row mb-15">
                    <div class="col-md-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="dc_type_of_arb_chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="dc_case_types_chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>



                <?php if (isset($fees_deficiency) && $fees_deficiency) : ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-warning custom-box dashboard-box collapsed-box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money"></i> Case Fees Deficiency & Excess</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table class="table table-bordered" id="db_amount_datatable">
                                        <thead>
                                            <tr>
                                                <th>Case No.</th>
                                                <th>Case Title</th>
                                                <th>Claimant Deficiency</th>
                                                <th>Respondant Deficiency</th>
                                                <th>Claimant Excess</th>
                                                <th>Respondant Excess</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fees_deficiency as $fee) : ?>
                                                <tr>
                                                    <td><?= $fee['case_no_desc'] ?></td>
                                                    <td><?= $fee['case_title'] ?></td>
                                                    <td><?= INDMoneyFormat($fee['arb_cs_remaining'] + $fee['adm_cs_remaining']) ?></td>
                                                    <td><?= INDMoneyFormat($fee['arb_rs_remaining'] + $fee['adm_rs_remaining']) ?></td>
                                                    <td><?= INDMoneyFormat($fee['excess_arb_cs'] + $fee['excess_adm_cs']) ?></td>
                                                    <td><?= INDMoneyFormat($fee['excess_arb_rs'] + $fee['excess_adm_rs']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="box box-warning custom-box dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Monthwise Cases (<?= ($this->input->get('year')) ? base64_decode($this->input->get('year')) : date('Y') ?>)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>

                    </div>

                    <div class="box-body">
                        <div class="monthwise-cases-search-col text-center">
                            <select name="mcs_year" id="mcs_year" class="form-control">
                                <option value="">Select Year</option>
                                <?php foreach ($dashboard_data['yearWiseData'] as $year) : ?>
                                    <option value="<?= $year['registered_year'] ?>" <?php echo ($current_year == $year['registered_year']) ? 'selected' : ''; ?>><?= $year['registered_year'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-custom" id="monthWiseFilterBtn"><i class="fa fa-filter"></i></button>
                        </div>
                        <div id="bar-chart" style="height: 300px;"></div>
                    </div>

                </div>

            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="row">

                    <div class="col-xs-12">
                        <div class="box box-warning custom-box dashboard-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-bell-o"></i> Latest Notifications</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>

                            </div>

                            <div class="box-body">
                                <ul class="db-notificaiton-list">
                                </ul>
                            </div>

                            <div class="">

                                <?php if (in_array($this->session->userdata('role'), ['CASE_FILER', 'COORDINATOR', 'HEAD_COORDINATOR'])) : ?>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="box box-warning dashboard-box mb-0 dashboard-notification-ef-box">
                                                <div class="box-header with-border without-background">
                                                    <h3 class="box-title"><i class="fa fa-book"></i> E-filing Pendings</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="box-body">
                                                    <table class="table dasboard-efiling-notification-tbl">
                                                        <tbody>
                                                            <tr>
                                                                <th>
                                                                    <span class="fa fa-plus-square dIcon"></span>
                                                                    <a href="<?= base_url('ims/efiling/new-references') ?>" class="dText">New Refference</a>
                                                                </th>
                                                                <!-- <td><?= $pending_efiling['new_reference'] ?></td> -->
                                                                <td>
                                                                    <span class="badge badge-dark dBadge">
                                                                        <?= $pending_efiling['new_reference'] ?></span>
                                                                </td>

                                                            </tr>

                                                            <tr>
                                                                <th>
                                                                    <span class="fa fa-file-o dIcon"></span>
                                                                    <a href="<?= base_url('ims/efiling/document-pleadings') ?>" class="dText">Documents</a>
                                                                </th>
                                                                <td><span class="badge badge-dark dBadge">
                                                                        <?= $pending_efiling['document'] ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="fa fa-id-card-o dIcon"></span>
                                                                    <a href="<?= base_url('ims/efiling/application') ?>" class="dText">Applications</a>
                                                                </th>
                                                                <td><span class="badge badge-dark dBadge"><?= $pending_efiling['application'] ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <span class="fa fa-tasks dIcon"></span>
                                                                    <a href="<?= base_url('ims/efiling/requests') ?>" class="dText">Requests</a>
                                                                </th>
                                                                <td>
                                                                    <span class="badge badge-dark dBadge">
                                                                        <?= $pending_efiling['request'] ?></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="box box-warning custom-box dashboard-box mb-0 dashboard-notification-ef-box">
                                                <div class="box-header with-border without-background">
                                                    <h3 class="box-title"><i class="fa fa-envelope"></i> Pending Messages - Other Noting</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="box-body">
                                                    <div class="box-comments db-box-comments">
                                                        <?php if (count($pending_efiling_messages) > 0) : ?>
                                                            <?php foreach ($pending_efiling_messages as $msg) : ?>

                                                                <a href="<?= base_url('ims/other-notings?noting_group=' . customURIEncode($msg['noting_group']) . '&type_code=' . customURIEncode($msg['type_code']) . '&type_name=' . customURIEncode($msg['type_name'])) ?>">
                                                                    <div class="box-comment">
                                                                        <div class="comment-text">
                                                                            <span class="username">
                                                                                <?= $msg['sent_by_desc'] ?>
                                                                                <span class="text-muted pull-right">
                                                                                    <?= formatReadableDate($msg['message_date']) ?>
                                                                                </span>
                                                                            </span>
                                                                            <div>
                                                                                <?= $msg['message'] ?>
                                                                            </div>
                                                                            <span class="badge badge-primary"><?= CONSTANTS_LIST[$msg['type_name']] ?></span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <p><i class="fa fa-times"></i> No new message</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="box box-warning custom-box dashboard-box mb-0 dashboard-notification-ef-box">
                                                <div class="box-header with-border without-background">
                                                    <h3 class="box-title"><i class="fa fa-list-alt"></i> Pending Fees Assessment</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="box-body">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th>
                                                                    <span class="fa fa-plus-square dIcon"></span>
                                                                    <a href="<?= base_url('fees-assessment') ?>" class="dText">Fees Assessment</a>
                                                                </th>
                                                                <td>
                                                                    <span class="badge badge-dark dBadge">
                                                                        <?= $new_fees_assessment['new_fees_assessment'] ?></span>
                                                                </td>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <!-- Yearwise Data -->
                    <div class="col-xs-12">

                        <div class="box box-warning custom-box dashboard-box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="fa fa-calendar-o"></i> Yearwise Cases</h4>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered no-margin">
                                    <tbody>
                                        <?php if (count($dashboard_data['yearWiseData']) > 0) : ?>
                                            <?php foreach ($dashboard_data['yearWiseData'] as $year) : ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= 'Year: ' . $year['registered_year'] ?></strong>
                                                    </td>
                                                    <td class="text-center"><span class="badge <?php echo ($year['cnt'] == 0) ? 'badge-primary' : 'badge-success'  ?>"><?= $year['cnt'] ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="2">No records found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
    var monthwise_data = '<?php echo json_encode($dashboard_data['monthWiseData']); ?>';
    monthwise_data = JSON.parse(monthwise_data);
    var new_monthwise_data = monthwise_data.map(function(data) {
        return [data.month_name, data.cnt];
    })
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/diac-common-js.js') ?>"></script>
<script>
    /*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE START */
    history.pushState(null, null, document.URL);
    window.addEventListener("popstate", function() {
        history.pushState(null, null, document.URL);
    });
    /*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */

    // OnClick Monthwise Filter button
    $('#monthWiseFilterBtn').on('click', function() {
        var year = $('#mcs_year').val();
        window.location.href = base_url + "diac-admin-dashboard?year=" + btoa(year);
    })

    $(document).ready(function() {
        /*
         * BAR CHART
         * ---------
         */

        var bar_data = {
            data: new_monthwise_data,
            color: '#f39c12'
        }
        $.plot('#bar-chart', [bar_data], {
            grid: {
                borderWidth: 1,
                borderColor: '#f3f3f3',
                tickColor: '#f3f3f3'
            },
            series: {
                bars: {
                    show: true,
                    barWidth: 0.5,
                    align: 'center'
                }
            },
            xaxis: {
                mode: 'categories',
                tickLength: 0
            }
        })

        /* END BAR CHART */

        // =========================================
        // =========================================
        /*
         * DONUT CHART: TYPE IF ARBITRATION
         * -----------
         */

        var donutData = [{
                label: 'Domestic',
                data: '<?= (isset($toa_data['domestic'])) ? $toa_data['domestic'] : 0 ?>',
                color: '#00b74a'
            },
            {
                label: 'International',
                data: '<?= (isset($toa_data['international'])) ? $toa_data['international'] : 0 ?>',
                color: '#F4951F'
            },
        ]
        $.plot('#dc_type_of_arb_chart', donutData, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: labelFormatter,
                        threshold: 0.1
                    }

                }
            },
            legend: {
                show: false
            }
        })
        /*
         * END DONUT CHART
         */

        // =========================================
        // =========================================

        // =========================================
        // =========================================
        /*
         * DONUT CHART: TYPE IF ARBITRATION
         * -----------
         */

        var donutData = [{
                label: 'General',
                data: '<?= (isset($toc_data['general'])) ? $toc_data['general'] : 0 ?>',
                color: '#00b74a'
            },
            {
                label: 'Emergency',
                data: '<?= (isset($toc_data['emergency'])) ? $toc_data['emergency'] : 0 ?>',
                color: '#EB1212'
            },
            {
                label: 'Fastrack',
                data: '<?= (isset($toc_data['fasttrack'])) ? $toc_data['fasttrack'] : 0 ?>',
                color: '#092958'
            },
        ]
        $.plot('#dc_case_types_chart', donutData, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: labelFormatter,
                        threshold: 0.1
                    }

                }
            },
            legend: {
                show: false
            }
        })
        /*
         * END DONUT CHART
         */

        // =========================================
        // =========================================


        // Common Datatable for all tables
        $("#db_amount_datatable").dataTable({
            sorting: false,
            ordering: false,
            responsive: false,
            searching: false,
            lengthChange: false,
        });

    })

    /*
     * Custom Label formatter
     * ----------------------
     */
    function labelFormatter(label, series) {
        return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
            label +
            '<br>' +
            Math.round(series.percent) + '%</div>'
    }
</script>