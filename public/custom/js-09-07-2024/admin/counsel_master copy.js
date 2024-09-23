
// function for get state for permanent address
$(document).on('change', '#perm_country', function() {
    var country_code= $(this).val();

    get_states(country_code,'','#perm_state')
});

// function for get state for correspondence address
$(document).on('change', '#corr_country', function() {
    var country_code= $(this).val();

    get_states(country_code,'','#corr_state')
});


function get_states(country_code,select='',select_ele) {

    $.ajax({
        url: base_url+"service/get_states_using_country_code",
        type: 'POST',
        data: {country_code:country_code},
        success:function(response){
            var response= JSON.parse(response)
            var options = '<option value="">Select State</option>';
            $.each(response, function (index, state) {
                if (select=='') {
                options +='<option value="' + state.id + '">' + state.name + "</option>";
                }else { 
                    if (select==state.id) {
                        options +='<option value="' + state.id + '" selected>' + state.name + "</option>";
                    }else{
                        options +='<option value="' + state.id + '">' + state.name + "</option>";
                    }
                }
            });
            $(select_ele).html(options);
        }
    })
}

$("#counsel_master_form").bootstrapValidator({
    message: "This value is not valid",
    submitButtons: 'button[type="submit"]',
    submitHandler: function (validator, form, submitButton) {
        $("#btn_submit").attr("disabled", "disabled");
        var formData = new FormData(document.getElementById("counsel_master_form"));
        var op_type=$('#op_type').val();
        if (op_type == 'ADD_COUNSEL_MASTER') {
            var url=base_url+"counsel-setup/add";
        }
        else if(op_type == 'EDIT_COUNSEL_MASTER'){
            var url=base_url+"counsel-setup/update";
        }
        else{
            var url =''; 
        }

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                try {
                    var obj = JSON.parse(response);
                    if (obj.status == false) {
                        // Enable the submit button
                        enable_submit_btn("btn_submit");

                        swal({
                            title: "Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else if (obj.status === "validationerror") {
                        // Enable the submit button
                        enable_submit_btn("btn_submit");

                        swal({
                            title: "Validation Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else {
                        // Enable the submit button
                        enable_submit_btn("btn_submit");
                        swal(
                         {
                             title: "Success",
                             text: obj.msg,
                             type: "success",
                             html: true,
                         },
                         function () {
                             window.location.href =base_url+'counsel-setup';
                         }
                        );
                    }
                } catch (e) {
                    sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                }
            },
            error: function (err) {
                toastr.error("unable to save");
            },
        });
    },
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        enrollment_no: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        email: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        phone_number: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        perm_add_1: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        perm_country: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        perm_state: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        perm_pincode: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        corr_add_1: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        corr_country: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        corr_state: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
        corr_pincode: {
            validators: {
                notEmpty: {
                    message: "Required",
                },
            },
        },
    },
});

// Datatable initialization
    // datatable for approved counsel
    var counsel_setup_datatable = $('#counsel_setup_datatable_appr').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "responsive": true,
        "order": [],
        "ajax": {
            url: base_url + "counsel-setup/get-datatable-data",
            type: 'POST',
            data: {
                csrf_trans_token: $('#arb_form_token').val()
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name'
            },
            {
                data: 'email'
            },
            {
                data: 'phone_number'
            },
            {
                data: 'enrollment_no'
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<span>'+data.perm_address_1 + '<br>' + data.perm_address_2+'<br>'+data.perm_country_name+'<br>'+data.perm_state_name+'<br>'+data.perm_pincode+'</span>';
                }
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<span>'+data.corr_address_1 + '<br>' + data.corr_address_2+'<br>'+data.corr_country_name+'<br>'+data.corr_state_name+'<br>'+data.corr_pincode+'</span>';
                }
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<a href="'+base_url+'counsel-setup/edit/'+data.code+'" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></a> <button class="btn btn-danger btn-sm" onclick="delete_counsel_master(' + data.code + ')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
                }
            }

        ],
        "columnDefs": [{
            "targets": [0, 2],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config) {
                $(node).removeClass('dt-button');
            },
            action: function(e, dt, node, config) {
                window.location= base_url+'counsel-setup/add-counsel';
            }
        }],
        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });

// Datatable initialization
    // datatable for Unapproved counsel
    var counsel_setup_datatable = $('#counsel_setup_datatable_unappr').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "responsive": true,
        "order": [],
        "ajax": {
            url: base_url + "counsel-setup/get-datatable-data-unappr",
            type: 'POST',
            data: {
                csrf_trans_token: $('#arb_form_token').val()
            }
        },
        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name'
            },
            {
                data: 'email'
            },
            {
                data: 'phone_number'
            },
            {
                data: 'enrollment_no'
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<span>'+data.perm_address_1 + '<br>' + data.perm_address_2+'<br>'+data.perm_country_name+'<br>'+data.perm_state_name+'<br>'+data.perm_pincode+'</span>';
                }
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<span>'+data.corr_address_1 + '<br>' + data.corr_address_2+'<br>'+data.corr_country_name+'<br>'+data.corr_state_name+'<br>'+data.corr_pincode+'</span>';
                }
            },
            {
                data: null,
                "sWidth": "10%",
                "sClass": "alignCenter",
                "render": function(data, type, row, meta) {
                    return '<a href="'+base_url+'counsel-setup/edit/'+data.code+'" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></a> <button class="btn btn-danger btn-sm" onclick="delete_counsel_master(' + data.code + ')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
                }
            }

        ],
        "columnDefs": [{
            "targets": [0, 2],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config) {
                $(node).removeClass('dt-button');
            },
            action: function(e, dt, node, config) {
                window.location= base_url+'counsel-setup/add-counsel';
            }
        }],
        drawCallback: function() {
            $('body').tooltip({
                selector: '[data-tooltip="tooltip"]'
            });
        }
    });


// Delete function for case counsel details
function delete_counsel_master(code) {
    if (code) {
        swal(
            {
                type: "error",
                title: "Are you sure?",
                text: "You want to delete the record.",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: base_url + "counsel-setup/delete",
                        type: "POST",
                        data: {
                            code: code,
                            
                        },
                        success: function (response) {
                            try {
                                var obj = JSON.parse(response);

                                if (obj.status == false) {
                                    // $("#errorlog").html("");
                                    // $("#errorlog").hide();
                                    toastr.error(obj.msg);
                                } else if (obj.status === "validationerror") {
                                    swal({
                                        title: "Validation Error",
                                        text: obj.msg,
                                        type: "error",
                                        html: true,
                                    });
                                } else {
                                    // $("#errorlog").html("");
                                    // $("#errorlog").hide();
                                    toastr.success(obj.msg);
                                    window.location.reload();

                                    // dataTableCauseList = $("#dataTableCounselsList").DataTable();
                                    // dataTableCauseList.draw();
                                    // dataTableCauseList.clear();
                                }
                            } catch (e) {
                                swal("Sorry", "Unable to Delete.Please Try Again !", "error");
                            }
                        },
                        error: function (error) {
                            toastr.error("Something went wrong.");
                        },
                    });
                } else {
                    swal.close();
                }
            }
        );
    }
}