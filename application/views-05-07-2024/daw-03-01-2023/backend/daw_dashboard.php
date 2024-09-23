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

        <div class="box box-body">
            <div class="row mb-15">
                <div class="col-xs-12">
                    <h3>Welcome To DAW Dashboard</h3>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script>
    /*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE START */
    history.pushState(null, null, document.URL);
    window.addEventListener("popstate", function() {
        history.pushState(null, null, document.URL);
    });
    /*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */
</script>