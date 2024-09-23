<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="form-group">
            <div class="box wrapper-box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">Name of Arbitrator:</label>
                            <p><?= $grievance_data['name_of_arbitrator'] ?></p></div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">DIAC Case Number:</label>
                            <p><?= $grievance_data['diac_case_no'] ?></p></div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">Name of person filling the form:</label>
                            <p><?= $grievance_data['name_of_form_filled_by'] ?></p></div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">Participated in Arbitration as a Counsel or Party:</label>
                            <p><?= $grievance_data['whether_participated_in_arbitration'] == 1?'Party':'Counsel' ?></p></div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">Mobile Number:</label>
                            <p><?= $grievance_data['phone'] ?></p></div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 form-group">
                            <div class="view-col">
                            <label class="form-label">Email:</label>
                            <p><?= $grievance_data['email'] ?></p></div>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-md-4 col-sm-6 col-12 form-group">
                        <div class="view-col">
                           <label class="form-label h4 text-dark">Arbitrator's:</label>
                            <?php foreach ($grievance_options_data as $got): ?>
                                <?php if ($got['type'] == 'GRIEVANCE_ARB_OPTIONS'): ?>
                                    <p><li><?= $got['options'] ?></li></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                       </div>
                       <div class="col-md-4 col-sm-6 col-12 form-group">
                        <div class="view-col">
                           <label class="form-label h4 text-dark">Deputy Counsel’s/Case Manager’s/Stenographers:</label>
                            <?php foreach ($grievance_options_data as $got): ?>
                                <?php if ($got['type'] == 'GRIEVANCE_DC_CM_ST_OPT'): ?>
                                    <p><li><?= $got['options'] ?></li></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                       </div>
                       <div class="col-md-4 col-sm-6 col-12 form-group">
                        <div class="view-col">
                           <label class="form-label h4 text-dark">Pantry and other staff:</label>
                            <?php foreach ($grievance_options_data as $got): ?>
                                <?php if ($got['type'] == 'GRIEVANCE_PANTRY_OTHER'): ?>
                                    <p><li><?= $got['options'] ?></li></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                       </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12 form-group">
                            <div class="view-col">
                                <label class="form-label">Comments:</label>
                                <p>
                                    <?= $grievance_data['comments'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>