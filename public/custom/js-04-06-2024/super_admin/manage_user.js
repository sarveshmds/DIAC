/*
 * Author: Debashish Jyotish
 * Date: 24/01/2018
 * Description : This is used for user creation (manage_user.php).
 *
 **/
var urls = base_url + "service/get_manage_user_data/get_userdata";
var user_table = $("#user_table").dataTable({
  processing: false, //Feature control the processing indicator.
  serverSide: true, //Feature control DataTables' server-side processing mode.
  destroy: true,
  paging: true,
  info: true,
  autoWidth: false,
  scrollX: false,
  responsive: false,
  searching: true,
  ordering: false,
  // Load data for the table's content from an ajax source
  ajax: {
    url: urls,
    type: "POST",
    data: function (data) {
      data.menu_role = $("#cmbMenuRole").val();
      data.csrf_user_token = $("#csrf_user_token").val();
    },
  },
  sDom: "<'row'<'col-xs-4 btn_user_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row'<'col-xs-5' i>>><'col-xs-6'p>>",
  columns: [
    { data: "sl_no", sClass: "alignCenter" },
    { data: "user_code", bVisible: false, sClass: "alignLeft" },
    {
      data: "button",
      data: null,
      sWidth: "12%",
      sDefaultContent:
        "<button type='button' class='btn btn-info btn-circle tooltipTable btn-sm' align='center' onclick='editUserData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>\
			<button type='button' class='btn btn-primary btn-circle tooltipTable btn-sm' align='center' id='btn_reset' onclick='resetpassword(event);' title='Reset Password' ><i class='fa fa-undo'></i></button>\
			<button type='button' class='btn btn-danger btn-circle tooltipTable btn-sm' align='center' id='btn_delete' onclick='delete_user(event);' title='Delete User' ><i class='fa fa-trash'></i></button>",
    },
    {
      data: null,
      sWidth: "5%",
      sClass: "alignCenter",
      mRender: function (data, type, full) {
        let span = "";
        if (data.record_status == 1) {
          span = '<span class="badge bg-green">Active</span>';
        }
        if (data.record_status == 0) {
          span = '<span class="badge bg-red">Deleted</span>';
        }
        return span;
      },
    },

    { data: "user_name", sClass: "alignLeft" },
    { data: "diac_serial_no", sClass: "alignLeft" },
    { data: "user_display_name", sClass: "alignLeft" },
    { data: "primary_role", sClass: "alignLeft" },
    {
      data: null,
      sWidth: "5%",
      sClass: "alignCenter",
      mRender: function (data, type, full) {
        var case_ref_no = "";
        if (
          data.primary_role == "DEPUTY_COUNSEL" ||
          data.primary_role == "CASE_MANAGER"
        ) {
          case_ref_no = data.start_index + "-" + data.end_index;
        }
        if (data.primary_role == "COORDINATOR") {
          case_ref_no = data.start_index2 + "-" + data.end_index2;
        }
        if (data.primary_role == "CASE_MANAGER") {
          case_ref_no = data.start_index3 + "-" + data.end_index3;
        }
        return case_ref_no;
      },
    },
    { data: "email", sClass: "alignLeft" },
    { data: "phone_number", sClass: "alignRight" },
    { data: "job_title", sClass: "alignRight" },
    { data: "state_name", sClass: "alignLeft" },
  ],
  //"columnDefs": [{"targets": [ 5,7 ],"orderable": false}],
  // to show tooltips in datatable
  fnDrawCallback: function (oSettings, json) {
    $(".tooltipTable").tooltipster({
      theme: "tooltipster-punk",
      animation: "grow",
      delay: 200,
      touchDevices: false,
      trigger: "hover",
    });
  },
  lengthMenu: [
    [50, 75, 100],
    [50, 75, 100],
  ], // Set the length menu options to only include 20
});
$("div.btn_user_modal").html(
  '<button class="btn btn-info tooltips btn-circle" title="Add" id="add_mng_user"><i class="fa fa-plus" aria-hidden="true"></i></button>'
);

$("#frm_user").bootstrapValidator({
  message: "This value is not valid",
  feedbackIcons: {
    valid: "glyphicon glyphicon-ok",
    invalid: "glyphicon glyphicon-remove",
    validating: "glyphicon glyphicon-refresh",
  },
  submitButtons: 'button[type="submit"]',

  submitHandler: function (validator, form, submitButton) {
    $("#btn_submit").html('<i class="fa fa-gear fa-spin"></i> Loading...');
    txtUserName = $("#txtUserName").val();
    txtpassword = "Password@123";

    var encSaltSHAPass = encryptShaPassCode(txtUserName, txtpassword);
    var formData = new FormData(document.getElementById("frm_user"));
    formData.append("secreatecode", encSaltSHAPass);

    urls = base_url + "service/operation_userdata";
    $.ajax({
      url: urls,
      method: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        try {
          var obj = JSON.parse(response);
          if (obj.status == false) {
            swal({
              title: "User",
              text: obj.msg,
              type: "error",
              html: true,
            });
          } else if (obj.status === "validationerror") {
            swal({
              title: "User",
              text: obj.msg,
              type: "error",
              html: true,
            });
          } else {
            swal(
              {
                title: "User",
                text: obj.msg,
                type: "success",
                html: true,
              },
              function () {
                window.location.reload();
              }
            );
            // user_table = $("#user_table").DataTable();
            // user_table.draw();
            // user_table.clear();

            // $("#frm_user").data("bootstrapValidator").resetForm(true); //Reseting user form
            // $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
            // $("#spanuser").html("Add User");
            // $("#hiduser_name").val("");
            // $("#frm_user input[name='op_type']").val("add_user");
            // $("#manage_user_modal").modal("hide");
          }
        } catch (e) {
          swal({
            title: "Sorry",
            text: "Unable to Save.Please Try Again !",
            type: "error",
            html: true,
          });
        }
      },
      error: function (err) {
        toastr.error("unable to save. Please try again.");
      },
      complete: function () {
        $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
        $("#btn_submit").attr("disabled", false);
      },
    });
  },
  //live: 'enabled',
  fields: {
    txtUserName: {
      //form input type name
      validators: {
        notEmpty: {
          message: "Required",
        },
      },
    },
    diac_serial_no: {
      //form input type name
      validators: {
        notEmpty: {
          message: "Required",
        },
      },
    },

    txtDisplayName: {
      //form input type name
      validators: {
        notEmpty: {
          message: "Required",
        },
      },
    },
    txtEmailId: {
      //form input type name
      validators: {
        emailAddress: {
          message: "The value is not a valid email address",
        },
      },
    },
    txtPhoneNumber: {
      //form input type name
      validators: {
        notEmpty: {
          message: "Mobile Number Required",
        },
        regexp: {
          regexp: /^[1-9][0-9]{0,9}$/,
          message: "Invalid Mobile Number",
        },
      },
    },
    user_status: {
      //form input type name
      validators: {
        notEmpty: {
          message: "Required",
        },
      },
    },
  },
});

$("#add_mng_user").click(function () {
  $("#spanuser")[0].innerHTML = "Add User";
  $("#frm_user").data("bootstrapValidator").resetForm(true); //to reset the form
  $("#manage_user_modal").modal("show");
});

function editUserData(event) {
  //on edit click assign the value to text field
  $("#dc_cm_case_ref_no").hide();

  $("#manage_user_modal").modal("show");
  $("#btn_submit")[0].innerHTML = "<i class='fa fa-edit'></i> Update";
  $("#spanuser")[0].innerHTML = "Edit User";
  $("#frm_user input[name='op_type']").val("edit_user");
  var oTable = $("#user_table").dataTable();
  var row;
  if (event.target.tagName == "BUTTON")
    row = event.target.parentNode.parentNode;
  else if (event.target.tagName == "I")
    row = event.target.parentNode.parentNode.parentNode;
  var user_code = oTable.fnGetData(row)["user_code"];
  var user_name = oTable.fnGetData(row)["user_name"];
  var user_display_name = oTable.fnGetData(row)["user_display_name"];
  var email = oTable.fnGetData(row)["email"];
  var phone_number = oTable.fnGetData(row)["phone_number"];
  var job_title = oTable.fnGetData(row)["job_title"];
  var diac_serial_no = oTable.fnGetData(row)["diac_serial_no"];
  var prof_img = oTable.fnGetData(row)["prof_img"];
  var status = oTable.fnGetData(row)["record_status"];
  var state_code = oTable.fnGetData(row)["fk_state_code"];
  var user_role = oTable.fnGetData(row)["primary_role"];
  var case_ref_no = oTable.fnGetData(row)["case_ref_no"];

  $("#hiduser_code").val(user_code);
  $("#txtUserName").val(user_name);
  $("#cmb_state_code").val(state_code);
  $("#hiduser_name").val(user_name);
  $("#txtDisplayName").val(user_display_name);
  $("#txtEmailId").val(email);
  $("#txtPhoneNumber").val(phone_number);
  $("#txtJobTitle").val(job_title);
  $("#diac_serial_no").val(diac_serial_no);

  $("#fileimage").val(prof_img);
  $("#user_status").val(status);
  $("#cmb_user_role option").attr("selected", false);
  $('#cmb_user_role option[value="' + user_role + '"]').attr("selected", true);

  if (
    user_role == "DEPUTY_COUNSEL" ||
    user_role == "CASE_MANAGER" ||
    user_role == "COORDINATOR"
  ) {
    $("#dc_cm_case_ref_no").show();
    get_case_ref_no(user_role, case_ref_no);
  }
}

function resetpassword(event) {
  $("#frm_user input[name='op_type']").val("sa_reset_password");
  var oTable = $("#user_table").dataTable();
  var row;
  if (event.target.tagName == "BUTTON") {
    event.target.innerHTML = '<i class="fa fa-gear fa-spin"></i>';
    row = event.target.parentNode.parentNode;
  } else if (event.target.tagName == "I") {
    $(event.target).removeClass("fa-undo");
    $(event.target).addClass("fa-gear fa-spin");
    row = event.target.parentNode.parentNode.parentNode;
  }

  var formData = new FormData(document.getElementById("frm_user"));
  txtpassword = "password";
  var encSaltSHAPass = encryptShaPassCode(
    oTable.fnGetData(row)["user_name"],
    txtpassword
  );
  formData.append("secreatecode", encSaltSHAPass);
  formData.append("hiduser_code", oTable.fnGetData(row)["user_code"]);
  formData.append("email", oTable.fnGetData(row)["email"]);
  formData.append("user_name", oTable.fnGetData(row)["user_name"]);
  formData.append(
    "user_display_name",
    oTable.fnGetData(row)["user_display_name"]
  );
  // $('#btn_reset').html('<i class="fa fa-gear fa-spin"></i>');

  $.ajax({
    url: base_url + "service/sa_reset_password",
    method: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      try {
        var obj = JSON.parse(response);
        if (!obj.status) {
          $("#btn_reset").html('<i class="fa fa-undo"></i>');
          sweetAlert("USER", obj.msg, "error");
        } else {
          $("#btn_reset").html('<i class="fa fa-undo"></i>');
          sweetAlert("USER", obj.msg, "success");
        }
      } catch (e) {
        sweetAlert("Sorry", "We are unable to Process !", "error");
      }
    },
    error: function (err) {
      toastr.error(err);
    },
    complete: function () {
      if (event.target.tagName == "BUTTON") {
        event.target.innerHTML = '<i class="fa fa-undo"></i>';
      } else if (event.target.tagName == "I") {
        $(event.target).addClass("fa-undo");
        $(event.target).removeClass("fa-gear fa-spin");
      }
    },
  });
}

function delete_user(event) {
  $("#frm_user input[name='op_type']").val("delete_user");
  var oTable = $("#user_table").dataTable();
  var row;
  if (event.target.tagName == "BUTTON")
    row = event.target.parentNode.parentNode;
  else if (event.target.tagName == "I")
    row = event.target.parentNode.parentNode.parentNode;

  var user_code = oTable.fnGetData(row)["user_code"];

  if (user_code) {
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
            url: base_url + "service/delete_user",
            type: "POST",
            data: { user_code: user_code },
            success: function (response) {
              try {
                var obj = JSON.parse(response);
                if (!obj.status) {
                  $("#btn_delete").html('<i class="fa fa-trash"></i>');
                  sweetAlert({
                    title: "USER",
                    text: obj.msg,
                    type: "error",
                    html: true,
                  });
                } else {
                  $("#btn_delete").html('<i class="fa fa-trash"></i>');
                  sweetAlert({
                    title: "USER",
                    text: obj.msg,
                    type: "success",
                    html: true,
                  });

                  user_table = $("#user_table").DataTable();
                  user_table.draw();
                  user_table.clear();
                }
              } catch (e) {
                sweetAlert("Sorry", "We are unable to Process !", "error");
              }
            },
            error: function (err) {
              toastr.error(err);
            },
          });
        } else {
          swal.close();
        }
      }
    );
  } else {
    sweetAlert(
      "Error",
      "Something went wrong. Please contact support.",
      "error"
    );
  }
}

//for form reset button click to reset the form
function form_reset() {
  $("#hiduser_name").val("");
  $("#frm_user").data("bootstrapValidator").resetForm(true); //to reset the form
  $("#frm_user").trigger("reset");
  $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
  $("#spanuser").html("Add User");
  $("#frm_user input[name='op_type']").val("add_user");
}

$("#txtUserName").on("keyup", function () {
  $(this).val($(this).val().toUpperCase());
});
