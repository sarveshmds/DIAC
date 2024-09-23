
// Datatable initialization
var dataTable = $('#dataTableOtherCorrespondanceList').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"responsive": true,
	"order": [],
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"ajax": {
		url: base_url+"service/get_all_other_correspondance_list/CASE_OTHER_CORRESPONDANCE_LIST",
		type: 'POST',
		data: {
			csrf_trans_token: $('#csrf_oc_trans_token').val(),
			case_no: $('#op_case_no').val()
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    }
		},
		{data: 'details'},
		{data: 'date_of_correspondance'},
		{data: 'send_by'},
		{data: 'sent_to'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-success btn-sm" onclick="viewOCForm(event)"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditOCForm(event)"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteOCList('+data+')"><span class="fa fa-trash"></span></button>'
		    }
		}
		
	],
	"columnDefs": [
		{
			"targets": [0, 1, 2, 3, 4],
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
                add_oc_list_modal_open();
            }
        },
        {
            text: '<span class="fa fa-file-pdf-o"></span> PDF',
            className: 'btn btn-danger',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
				window.open(base_url+'pdf/other-correspondance/'+$('#hidden_case_no').val(), '_BLANK');
            }
        }
    ]
});

// Open the modal to add cause list
function add_oc_list_modal_open(){
	$('.oc-modal-title').html('<span class="fa fa-plus"></span> Add Other Correspondance');
	$('#addOCListModal').modal({
    	backdrop: 'static',
    	keyboard: false
    })
}

// On closing the modal reset the form
$('#addOCListModal').on("hidden.bs.modal", function(e){
	$('#oc_form').trigger('reset');
	$('#hidden_op_id').val('');
	$('.oc-modal-title').html('');
	$('#oc_op_type').val('ADD_CASE_OTHER_CORRESPONDANCE');
	$("#oc_btn_submit")[0].innerHTML ="<i class='fa fa-paper-plane'></i> Save Details";
	$('#oc_form').data('bootstrapValidator').resetForm(true);
});


// View the other correspondance data
function viewOCForm(event){
	// Get data table instance to get the data through row
    var oTable = $('#dataTableOtherCorrespondanceList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var details = oTable.fnGetData(row)['details'];
	var date_of_correspondance = oTable.fnGetData(row)['date_of_correspondance'];
	var send_by = oTable.fnGetData(row)['send_by'];
	var sent_to = oTable.fnGetData(row)['sent_to'];

	// Set data into table
	var table = "<table class='table table-responsive table-bordered table-striped'>";
	table += "<tr><th width='30%'>Details: </th> <td>"+details+"</td></tr>";
	table += "<tr><th>Date of correspondance: </th> <td>"+date_of_correspondance+"</td></tr>";
	table += "<tr><th>Send By: </th> <td>"+send_by+"</td></tr>";
	table += "<tr><th>Sent To: </th> <td>"+sent_to+"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$('.common-modal-title').html('<span class="fa fa-file"></span> Other Correspondance Detail');
	$('.common-modal-body').html(table);
	$('#common-modal').modal('show');
}

// Button edit form to open the modal with edit form
function btnEditOCForm(event){

	$('.oc-modal-title').html('<span class="fa fa-edit"></span> Edit Other Correspondance');
	$('#addOCListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);

	// Change the submit button to edit
	$('#oc_btn_submit').attr('disabled',false);
	$("#oc_btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$('#oc_form').trigger('reset');
    
    // Change the op type
    $("#oc_form input[name='op_type']").val("EDIT_CASE_OTHER_CORRESPONDANCE");

    // Get data table instance to get the data through row
    var oTable = $('#dataTableOtherCorrespondanceList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
    var case_no = oTable.fnGetData(row)['case_no'];
    var oc_details = oTable.fnGetData(row)['details'];
    var oc_doc = oTable.fnGetData(row)['date_of_correspondance'];
    var oc_send_by = oTable.fnGetData(row)['send_by'];
    var oc_sent_to = oTable.fnGetData(row)['sent_to'];
    
    $('#hidden_oc_id').val(id);
    $('#oc_hidden_case_no').val(case_no);
    $('#oc_details').val(oc_details);
    $('#oc_doc').val(oc_doc); 
    $('#oc_send_by').val(oc_send_by); 
    $('#oc_sent_to').val(oc_sent_to); 

}

// Add case other correspondance form
$('#oc_form').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#oc_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("oc_form"));
		urls =base_url+"service/add_case_oc_operation";

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
	                	enable_submit_btn('oc_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('oc_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{
	                	//Reseting user form
						$('#oc_form').trigger('reset');
	                	toastr.success(obj.msg);

	                	// Redraw the datatable
	                	dataTableCauseList = $('#dataTableOtherCorrespondanceList').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#addOCListModal').modal("hide");
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
        oc_details: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
    	oc_send_by: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        oc_sent_to: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
    }
})


// Delete function for case other correpondance
function btnDeleteOCList(id){
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
					url: base_url+'service/delete_case_oc_list/DELETE_CASE_OTHER_CORRESPONDANCE',
					type: 'POST',
					data: {
						id: id,
						csrf_trans_token: $('#csrf_oc_trans_token').val()
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

			                	dataTableCauseList = $('#dataTableOtherCorrespondanceList').DataTable();
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