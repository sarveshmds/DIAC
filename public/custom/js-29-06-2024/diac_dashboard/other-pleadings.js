
// Datatable initialization
var dataTable = $('#dataTableOtherPleadingList').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"responsive": true,
	"order": [],
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"ajax": {
		url: base_url+"service/get_all_other_pleading_list/CASE_OTHER_PLEADING_LIST",
		type: 'POST',
		data: {
			csrf_trans_token: $('#csrf_op_trans_token').val(),
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
		{data: 'date_of_filing'},
		{data: 'filed_by'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-success btn-sm" onclick="viewOPForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye" ></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditOPForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteOPList('+data+')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
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
	dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
    	{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
                add_op_list_modal_open();
            }
        },
        {
            text: '<span class="fa fa-file-pdf-o"></span> PDF',
            className: 'btn btn-danger',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
				window.open(base_url+'pdf/other-pleadings/'+$('#hidden_case_no').val(), '_BLANK');
            }
        }
    ],
	drawCallback: function () {
		$('body').tooltip({ selector: '[data-tooltip="tooltip"]' });
   	},
});

// Open the modal to add cause list
function add_op_list_modal_open(){
	$('.op-modal-title').html('<span class="fa fa-plus"></span> Add Miscellaneous');
	$('#addOPListModal').modal({
    	backdrop: 'static',
    	keyboard: false
    })
}

// On closing the modal reset the form
$('#addOPListModal').on("hidden.bs.modal", function(e){
	$('#op_form').trigger('reset');
	$('#hidden_op_id').val('');
	$('.op-modal-title').html();
	$('#op_op_type').val('ADD_CASE_OTHER_PLEADINGS');
	$("#op_btn_submit")[0].innerHTML ="<i class='fa fa-paper-plane'></i> Save Details";
	$('#op_form').data('bootstrapValidator').resetForm(true);
});



// View the other pleadings data
function viewOPForm(event){
	
	// Get data table instance to get the data through row
    var oTable = $('#dataTableOtherPleadingList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var details = oTable.fnGetData(row)['details'];
	var date_of_filing = oTable.fnGetData(row)['date_of_filing'];
	var filed_by = oTable.fnGetData(row)['filed_by'];

	// Set data into table
	var table = "<table class='table table-responsive table-bordered table-striped'>";
	table += "<tr><th width='30%'>Details: </th> <td>"+details+"</td></tr>";
	table += "<tr><th>Date of filing: </th> <td>"+date_of_filing+"</td></tr>";
	table += "<tr><th>Filed By: </th> <td>"+filed_by+"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$('.common-modal-title').html('<span class="fa fa-file"></span> Miscellaneous Detail');
	$('.common-modal-body').html(table);
	$('#common-modal').modal('show');
}

// Button edit form to open the modal with edit form
function btnEditOPForm(event){

	$('.op-modal-title').html('<span class="fa fa-edit"></span> Edit Miscellaneous');
	$('#addOPListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);

	// Change the submit button to edit
	$('#op_btn_submit').attr('disabled',false);
	$("#op_btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$('#op_form').trigger('reset');
    
    // Change the op type
    $("#op_form input[name='op_type']").val("EDIT_CASE_OTHER_PLEADINGS");

    // Get data table instance to get the data through row
    var oTable = $('#dataTableOtherPleadingList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
    var case_no = oTable.fnGetData(row)['case_no'];
    var op_details = oTable.fnGetData(row)['details'];
    var op_dof = oTable.fnGetData(row)['date_of_filing'];
    var op_filed_by = oTable.fnGetData(row)['filed_by'];
    
    $('#hidden_op_id').val(id);
    $('#op_hidden_case_no').val(case_no);
    $('#op_details').val(op_details);
    $('#op_dof').val(op_dof); 
    $('#op_filed_by').val(op_filed_by); 

}

// Add case other pleadings form
$('#op_form').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#op_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("op_form"));
		urls =base_url+"service/add_case_op_operation";

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
	                	enable_submit_btn('op_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('op_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{
	                	//Reseting user form
						$('#op_form').trigger('reset');
	                	toastr.success(obj.msg);

	                	// Redraw the datatable
	                	dataTableCauseList = $('#dataTableOtherPleadingList').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#addOPListModal').modal("hide");
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
		op_details: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        
    	op_filed_by: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
    }
})


// Delete function for case other pleadings
function btnDeleteOPList(id){
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
					url: base_url+'service/delete_case_op_list/DELETE_CASE_OTHER_PLEADINGS',
					type: 'POST',
					data: {
						id: id,
						csrf_trans_token: $('#csrf_op_trans_token').val()
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

			                	dataTableCauseList = $('#dataTableOtherPleadingList').DataTable();
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