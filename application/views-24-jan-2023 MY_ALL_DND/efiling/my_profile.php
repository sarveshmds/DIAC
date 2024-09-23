<div class="container-xl">
    <!-- Page title -->
    <div class="card card-body blur shadow-blur overflow-hidden mt-3">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="<?= base_url('public/efiling_assets/profile.png') ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        <?php echo $this->session->userdata('user_display_name') ?>,
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        <?php echo $this->session->userdata('role') ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 card-title"><a href="javascript:;">
                                        <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Profile" aria-label="Edit Profile"></i><span class="sr-only">Edit Profile</span>
                                    </a> Profile Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 ps-0 pt-0 pb-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp;<?php echo $this->session->userdata('user_display_name') ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp;<?php echo $details->phone_number ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Alternate Mobile:</strong> &nbsp;<?php echo $details2->alternate_mobile_number ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; <?php echo $details->email ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Address:</strong> &nbsp; <?php echo $details2->address ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Pincode:</strong> &nbsp; <?php echo $details2->pincode ?></li>
                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">User Type:</strong> &nbsp; <?php echo $this->session->userdata('role') ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 card-title"><i class="fa fa-edit"></i> Edit Profile</h6>
                            </div>
                            <div class="card-body p-3">
                                <form action="" id="profile" class="profile" method="post">
                                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm'); ?>">
                                    <input type="hidden" name="hidden_id" value="<?php echo $details->id ?>">
                                    <input type="hidden" name="fk_user_code" value="<?php echo $details2->fk_user_code ?>">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Name</label>
                                            <input type="text" value="<?php echo $this->session->userdata('user_display_name') ?>" name="name" id="name" class="form-control">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Email ID</label>
                                            <input type="text" value="<?php echo $details->email ?>" readonly name="email" id="email" class="form-control">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Reg. Mobile No.</label>
                                            <input type="text" name="phone" id="phone" value="<?php echo $details->phone_number ?>" class="form-control" readonly value="9560314444">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Address</label>
                                            <input type="text" name="address" value="<?php echo $details2->address ?>" id="address" class="form-control">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Pin Code</label>
                                            <input type="text" name="pincode" value="<?php echo $details2->pincode ?>" id="pincode" class="form-control">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                                            <label for="" class="form-label">Alternate Mobile</label>
                                            <input type="text" name="alternate_mobile_number" value="<?php echo $details2->alternate_mobile_number ?>" id="alternate_mobile_number" class="form-control">
                                        </div>
                                        <div class="col-12 mb-3 text-center">
                                            <button type="submit" class="btn btn-custom">
                                                <i class="fa fa-save"></i> Update Details
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#profile').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                name: {
                    required: true
                },
                address: {
                    required: true
                },
                pincode: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                alternate_mobile_number: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                $.ajax({
                    url: base_url + 'efiling/profile/update',
                    data: new FormData(document.getElementById('profile')),
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.msg
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = base_url + 'efiling/user/profile';
                                    // location.realod();
                                }
                            })

                        } else if (response.status == 'validation_error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: response.msg
                            })
                        } else if (response.status == false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.msg
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Server Error'
                            })
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Server Error, please try again'
                        })
                    }
                });
            }
        })
    })
</script>