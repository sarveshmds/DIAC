<footer class="footer pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted">
                    &copy; <?= date('Y') ?>,
                    All rights reserved for DIAC.
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
</main>

<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
<!--   Core JS Files   -->
<script src="<?= base_url('public/') ?>efiling_assets/js/core/popper.min.js"></script>
<script src="<?= base_url('public/') ?>efiling_assets/js/core/bootstrap.min.js"></script>
<script src="<?= base_url('public/') ?>efiling_assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="<?= base_url('public/') ?>efiling_assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= base_url('public/') ?>efiling_assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
</body>

</html>