
// Datatable initialization
var dataTable = $('#user_role_assign_table').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"order": [],
	"ajax": {
		url: base_url+"service/get_all_user_role_assign/GET_USER_ROLE_GROUP_MAP",
		type: 'POST',
		data: {
			csrf_trans_token: $('#csrf_user_role_assign_token').val()
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    } 
		},
		{data: 'user_name'},
		{data: 'user_display_name'},
		{data: 'user_role_group_name'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-warning" onclick="btnEditUrgForm(event)"><span class="fa fa-edit"></span></button>'
		    }
		}
		
	],
	"columnDefs": [
		{
			"target": [0, 8],
			"orderable": false,
			"sorting": false
		}
	],
	dom: 'lBfrtip',
    buttons: [
    	{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-orange',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
                add_urg_list_modal_open();
            }
        }
    ]
});

// Open the modal to add urg
function add_urg_list_modal_open(){
	$('.urg-modal-title').html('<span class="fa fa-plus"></span> Add User Role Assign');
	$('#urg_op_type').val('ADD_USER_ROLE_ASSIGN');
	
	$('#user_role_assign_modal').modal({
    	backdrop: 'static',
    	keyboard: false
    })
}

// On closing the modal reset the form
$('#user_role_assign_modal').on("hidden.bs.modal", function(e){
	$('#user_role_assign_form').trigger('reset');
	$('.urg-modal-title').html('');
	$('#urg_hidden_id').val('');
	$('#urg_op_type').val('');
	
	$("#urg_btn_submit")[0].innerHTML ="<i class='fa fa-paper-plane'></i> Add";
	$('#user_role_assign_form').data('bootstrapValidator').resetForm(true);
});

// Button edit form to open the modal with edit form
function btnEditUrgForm(event){

	$('.urg-modal-title').html('<span class="fa fa-edit"></span> Edit User Role Assign');
	$('#user_role_assign_modal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);

	// Change the submit button to edit
	$('#urg_btn_submit').attr('disabled',false);
	$("#urg_btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";

	// Reset the form
	$('#user_role_assign_form').trigger('reset');
    
    // Change the op type
    $('#urg_op_type').val('EDIT_USER_ROLE_ASSIGN');

    // Get data table instance to get the data through row
    var oTable = $('#user_role_assign_table').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	console.log(oTable.fnGetData(row));
	var id = oTable.fnGetData(row)['id'];
    var user_code = oTable.fnGetData(row)['user_code'];
    var role_group_code = oTable.fnGetData(row)['role_group_code'];
    
    $('#urg_hidden_id').val(id);
    
    $('#urg_role_group option[value="'+role_group_code+'"]').prop('selected', true);
    $('#urg_user_code option[value="'+user_code+'"]').prop('selected', true);

}

// Add user role assign form
$('#user_role_assign_form').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#counsel_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("user_role_assign_form"));
		urls =base_url+"service/add_urg_operation";

		$.ajax({
			url : urls,
			method : 'POST',
			data:formData,
	        contentType: false,
	        processData: false,
			success : function(response){
				// try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	// Enable the submit button
	                	enable_submit_btn('urg_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('urg_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{
	                	//Reseting form
						$('#user_role_assign_form').trigger('reset');
	                	toastr.success(obj.msg);

	                	// Redraw the datatable
	                	dataTableCauseList = $('#user_role_assign_table').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#user_role_assign_modal').modal("hide");
	                }
	            // } catch (e) {
	            //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
	            // }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
    },
	fields: {
        urg_role_group: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        urg_user_code: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
    }
})


// Delete function for user role assign
function btnDeleteUrgList(id){
	if(id){
		swal({
			type: 'error',
			title: 'Are you sure?',
			text: 'You want to delete the record.',
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Delete",
			cancelButtonText: "Cancel"
		}, function(isConfirm){
			if(isConfirm){
				$.ajax({
					url: base_url+'service/delete_case_counsel/DELETE_CASE_COUNSEL',
					type: 'POST',
					data: {
						id: id,
						csrf_trans_token: $('#csrf_counsels_trans_token').val()
					},
					success: function(response){
						
						try{
							var obj = JSON.parse(response);

			                if (obj.status == false) {
			                	$('#errorlog').html('');
			                	$('#errorlog').hide();
			                	toastr.error(obj.msg);
			                }else if(obj.status === 'validationerror'){
			                	swal({
			                		title: 'Validation Error',
			                		text: obj.msg,
			                		type: 'error',
			                		html: true
			                	});
			                }else{
			                	$('#errorlog').html('');
			                	$('#errorlog').hide();
			                	toastr.success(obj.msg);

			                	dataTableCauseList = $('#dataTableCounselsList').DataTable();
								dataTableCauseList.draw();
								dataTableCauseList.clear();
			                }
						}
						catch(e){
							swal("Sorry",'Unable to Save.Please Try Again !', "error");
						}
					},
					error: function(error){
						toastr.error('Something went wrong.');
					}
				})
			}
			else{
				swal.close();
			}
		})
	}
}


// Function to enable the submit button
function enable_submit_btn(button){
	$('#'+button).attr('disabled',false);
}