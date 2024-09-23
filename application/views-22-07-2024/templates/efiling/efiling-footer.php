<footer class="footer py-3 d-print-none bg-light">
    <div class="container-xl">
        <div class="row text-center align-items-center justify-content-center">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item text-dark">
                        Copyright &copy; <?= date('Y') ?>
                        <a href="#" class="link-warning">Delhi International Arbitration Centre</a>.
                        All rights reserved.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</div>
</div>


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

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';

    $(document)
        .ajaxStart(function() {
            $(".site-loader").show();
        })
        .ajaxStop(function() {
            $(".site-loader").hide();
        });

    $('#btn_go_back').on('click', function() {
        history.back();
    })
</script>

</body>

</html>