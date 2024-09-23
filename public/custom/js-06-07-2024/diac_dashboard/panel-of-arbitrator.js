// Datatable initialization
var dataTable = $('#dataTablePOAList').DataTable({
	"processing": true,
	"serverSide": true,
	"order": [],
	"responsive": true,
	"ajax": {
		url: base_url+"service/get_all_poa_list/ALL_POA_LIST",
		type: 'POST',
		data: function(d){
			d.csrf_trans_token = $('#csrf_poa_trans_token').val();
			d.category = $('#poa_f_category').val();
			d.name = $('#poa_f_name').val();
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    }
			
		},
		{data: 'name'},
		{data: 'category_name'},
		{data: 'experience'},
		{data: 'enrollment_no'},
		{data: 'contact_details'},
		{data: 'email_details'},
		{data: 'address_details'},
		{data: 'remarks'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-success btn-sm" onclick="viewPOAData(event)"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditPOA(event)"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeletePOA('+data+')"><span class="fa fa-trash"></span></button>'
		    }
		}
		
	],
	"columnDefs": [
		{
			"targets": [0, 1, 2, 3, 4, 5, 6,7 , 8, 9],
			"orderable": false,
			"sorting": false
		},
		{
			"targets": [3,4,5,6,7, 8],
			"visible": false
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
                add_poa_modal_open();
            }
        },
        {
            text: '<span class="fa fa-file-pdf-o"></span> PDF',
            className: 'btn btn-danger',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
				window.open(base_url+'pdf/panel-of-arbitrators', '_BLANK');
            }
        }
    ]
});

// Open the modal to add panel of arbitrator
function add_poa_modal_open(){
	$('#poa_op_type').val('ADD_POA_FORM');
	$('.poa-modal-title').html('<span class="fa fa-plus"></span> Add Panel of Arbitrator');

	$('#addPOAListModal').modal({
    	backdrop: 'static',
    	keyboard: false
    })
}

// On closing the modal reset the form
$('#addPOAListModal').on("hidden.bs.modal", function(e){
	$('.poa-modal-title').html('');
	$('#poa_op_type').val('');
	$('#poa_hidden_id').val('');

	$('#form_add_poa').trigger('reset');
	$("#poa_btn_submit").html("<i class='fa fa-paper-plane'></i> Add Details");
	$('#form_add_poa').data('bootstrapValidator').resetForm(true);
});


// View the panel of arbitrator data
function viewPOAData(event){
	// Get data table instance to get the data through row
    var oTable = $('#dataTablePOAList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;


	var name = oTable.fnGetData(row)['name'];
	var category_name = oTable.fnGetData(row)['category_name'];
	var contact_details = oTable.fnGetData(row)['contact_details'];
	var email_details = oTable.fnGetData(row)['email_details'];
	var address_details = oTable.fnGetData(row)['address_details'];
	var enrollment_no = oTable.fnGetData(row)['enrollment_no'];
	var experience = oTable.fnGetData(row)['experience'];

	// Set data into table
	var table = "<table class='table table-responsive table-bordered table-striped'>";
	table += "<tr><th width='30%'>Name: </th> <td>"+name+"</td></tr>";
	table += "<tr><th>Category: </th> <td>"+category_name+"</td></tr>";
	table += "<tr><th>Enrollment No.: </th> <td>"+enrollment_no+"</td></tr>";
	table += "<tr><th>Experience: </th> <td>"+experience+"</td></tr>";
	table += "<tr><th>Contact Details: </th> <td>"+contact_details+"</td></tr>";
	table += "<tr><th>Email Details: </th> <td>"+email_details+"</td></tr>";
	table += "<tr><th>Address Details: </th> <td>"+address_details+"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$('.common-modal-title').html('<span class="fa fa-file"></span> Panel of arbitrator');
	$('.common-modal-body').html(table);
	$('#common-modal').modal('show');
}

// Button edit form to open the modal with edit form
function btnEditPOA(event){

	// Reset the form
	$('#form_add_poa').trigger('reset');
    
    // Change the op type
    $("#poa_op_type").val("EDIT_POA_FORM");

    // Get data table instance to get the data through row
    var oTable = $('#dataTablePOAList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
    var name = oTable.fnGetData(row)['name'];
    var category_id = oTable.fnGetData(row)['category_id'];
    var contact_details = oTable.fnGetData(row)['contact_details'];
    var address_details = oTable.fnGetData(row)['address_details'];
    var email_details = oTable.fnGetData(row)['email_details'];
    var remarks = oTable.fnGetData(row)['remarks'];
    var experience = oTable.fnGetData(row)['experience'];
    var enrollment_no = oTable.fnGetData(row)['enrollment_no'];
    
    $('#poa_hidden_id').val(id);
    $('#poa_name').val(name);
    $('#poa_contact_details').val(contact_details);
    $('#poa_address_details').val(address_details);
    $('#poa_email_details').val(email_details);
    $('#poa_remarks').val(remarks);
    $('#poa_experience').val(experience);
    $('#poa_enrollment_no').val(enrollment_no);
    $('#poa_category option[value="'+category_id+'"]').prop('selected', true); 
    

    // Change the submit button to edit
	$('#poa_btn_submit').attr('disabled',false);
	$("#poa_btn_submit").html("<i class='fa fa-edit'></i> Update Details");

    $('.poa-modal-title').html('<span class="fa fa-edit"></span> Edit Panel of Arbitrator');
	$('#addPOAListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);
}


// Add panel of arbitrator form
$('#form_add_poa').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("form_add_poa"));
		// var stringData = new URLSearchParams(formData).toString();
		// console.log(formData);
		// var string = "poa_name=formData.poa_name+'&'+poa_category=formData.poa_category+'&'+poa_experience=formData.poa_experience+'&'+poa_enrollment_no=formData.poa_enrollment_no+'&'+poa_contact_details=formData.poa_contact_details+'&'+poa_email_details=formData.poa_email_details+'&'+poa_address_details=formData.poa_address_details+'&'+poa_remarks=formData.poa_remarks";

		urls =base_url+"service/poa_operation";
		$.ajax({
			url: urls,
			method: 'POST',
			data: formData,
	        contentType: false,
	        processData: false,
			success : function(response){
				// try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	// Enable the submit button
	                	enable_submit_btn('poa_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('poa_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{
	                	//Reseting user form
						$('#form_add_cause_list').trigger('reset');
	                	toastr.success(obj.msg);

	                	// Redraw the datatable
	                	dataTableCauseList = $('#dataTablePOAList').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#addPOAListModal').modal("hide");
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
        poa_name: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        poa_category: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
    }
})


// Delete function for panel of arbitrator
function btnDeletePOA(id){
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
					url: base_url+'service/delete_poa/DELETE_POA',
					type: 'POST',
					data: {
						id: id,
						csrf_trans_token: $('#csrf_poa_trans_token').val()
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

			                	dataTableCauseList = $('#dataTablePOAList').DataTable();
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
function enable_submit_btn(id){
	$('#'+id).attr('disabled',false);
}