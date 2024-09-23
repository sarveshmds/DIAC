// Datatable initialization
var dataTable = $('#dataTableCauseList').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"responsive": true,
	"order": [],
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"ajax": {
		url: base_url+"service/get_all_cause_list/ALL_CAUSE_LIST",
		type: 'POST',
		data: function(d){
			d.case_no = $('#f_cl_case_no').val();
			d.date = $('#f_cl_date').val();
			d.room_no = $('#f_cl_room_no').val();
			d.csrf_trans_token = $('#csrf_trans_token').val();
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    }
			
		},
		{data: 'case_no'},
		{data: 'title_of_case'},
		{data: 'arbitrator_name'},
		{data: 'purpose_category_name'},
		{data: 'date'},
		{data: null,
			render: function(data, type, row, meta){
				var time_to = (data.time_to)? ' - '+data.time_to: '';
				return data.time_from+time_to;
			}
		},
		{data: 'room_no'},
		{data: null,
			render: function(data, type, row, meta){
				return (data.active_status == 2)? '<span class="badge badge-danger">'+data.active_status_desc+'</span>': '<span class="badge badge-success">'+data.active_status_desc+'</span>';
			}
		},
		{data: 'remarks'},
		{data: 'id',
			"sWidth": "15%",
			"sClass":"alignCenter",
			"render": function (data, type, row, meta) {
		        return '<button class="btn btn-primary btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Case"><span class="fa fa-times"></span></button> <button class="btn btn-info btn-sm" onclick="btnDuplicateForm(event)" data-tooltip="tooltip" title="Duplicate cause list"><span class="fa fa-copy"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditForm(event)" data-tooltip="tooltip" title="Edit cause list"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteCauseList('+data+')" data-tooltip="tooltip" title="Delete cause list"><span class="fa fa-trash"></span></button>'
		    }
		}
		
	],
	"columnDefs": [
		{
			"targets": ['_all'],
			"orderable": false,
			"sorting": false
		}
	],
    buttons: [
    	{
            text: '<span class="fa fa-plus"></span> Add',
            className: 'btn btn-custom',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
                add_cause_list_modal_open();
            }
        },
        {
            text: '<span class="fa fa-file-pdf-o"></span> PDF',
            className: 'btn btn-danger',
            init: function(api, node, config){
            	$(node).removeClass('dt-button');
            },
            action: function ( e, dt, node, config ) {
				window.open(base_url+'pdf/cause-list', '_BLANK');
            }
        }
    ],
	dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$('body').tooltip({ selector: '[data-tooltip="tooltip"]' });
	}
});

// Open the modal to add cause list
function add_cause_list_modal_open(){

	$('.cause-list-modal-title').html('<i class="fa fa-plus"></i> Add Cause List');
	$('#cause_list_op_type').val('ADD_CAUSE_LIST_FORM');

	$('#addCauseListModal').modal({
    	backdrop: 'static',
    	keyboard: false
    })
}

// On closing the modal reset the form
$('#addCauseListModal').on("hidden.bs.modal", function(e){
	$('.cause-list-modal-title').html('');
	$('#cause_list_op_type').val('');
	$('#cause_list_hidden_id').val('');

	$('#form_add_cause_list').trigger('reset');
	$("#cause_list_btn_submit").html("<i class='fa fa-paper-plane'></i> Save Details");
	$('#form_add_cause_list').data('bootstrapValidator').resetForm(true);
});

// Button edit form to open the modal with edit form
function btnEditForm(event){

	// Change the submit button to edit
	$('#cause_list_btn_submit').attr('disabled',false);
	$("#cause_list_btn_submit").html("<i class='fa fa-edit'></i> Update Details");

	// Reset the form
	$('#form_add_cause_list').trigger('reset');
    
    // Change the op type
    $("#cause_list_op_type").val("EDIT_CAUSE_LIST_FORM");

    // Get data table instance to get the data through row
    var oTable = $('#dataTableCauseList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
    var case_no = oTable.fnGetData(row)['case_no'];
    var title_of_case = oTable.fnGetData(row)['title_of_case'];
    var arbitrator_name = oTable.fnGetData(row)['arbitrator_name'];
    var purpose_cat_id = oTable.fnGetData(row)['purpose_cat_id'];
    var date = oTable.fnGetData(row)['date'];
    var time_from = oTable.fnGetData(row)['time_from'];
    var time_to = oTable.fnGetData(row)['time_to'];
    var room_no = oTable.fnGetData(row)['room_no'];
    var room_no_id = oTable.fnGetData(row)['room_no_id'];
    var remarks = oTable.fnGetData(row)['remarks'];
    
    $('#cause_list_hidden_id').val(id);
    $('#case_no').val(case_no);
    $('#title_of_case').val(title_of_case);
    $('#arbitrator_name').val(arbitrator_name);
	$('#purpose option[value="'+purpose_cat_id+'"]').prop('selected', true);
    $('#date').val(date); 
    $('#time_from').val(time_from); 
    $('#time_to').val(time_to); 
    
    $('#room_no option[value = "'+room_no_id+'"]').prop('selected', true);

    $('#remarks').val(remarks); 

    $('.cause-list-modal-title').html('<i class="fa fa-edit"></i> Edit Cause List');
	$('#addCauseListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);
}


// Button duplicate form to open the modal with duplicate form form
function btnDuplicateForm(event){

	// Change the submit button to edit
	$('#cause_list_btn_submit').attr('disabled',false);
	$("#cause_list_btn_submit").html("<i class='fa fa-paper-plane'></i> Save Details");

	// Reset the form
	$('#form_add_cause_list').trigger('reset');
    
    // Change the op type
    $("#form_add_cause_list input[name='op_type']").val("ADD_CAUSE_LIST_FORM");

    // Get data table instance to get the data through row
    var oTable = $('#dataTableCauseList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
    var case_no = oTable.fnGetData(row)['case_no'];
    var title_of_case = oTable.fnGetData(row)['title_of_case'];
    var arbitrator_name = oTable.fnGetData(row)['arbitrator_name'];
    var purpose_cat_id = oTable.fnGetData(row)['purpose_cat_id'];
    var date = oTable.fnGetData(row)['date'];
    var time_from = oTable.fnGetData(row)['time_from'];
    var time_to = oTable.fnGetData(row)['time_to'];
    var room_no = oTable.fnGetData(row)['room_no'];
    var room_no_id = oTable.fnGetData(row)['room_no_id'];
    var remarks = oTable.fnGetData(row)['remarks'];
    
    $('#case_no').val(case_no);
    $('#title_of_case').val(title_of_case);
    $('#arbitrator_name').val(arbitrator_name);

    $('#purpose option[value="'+purpose_cat_id+'"]').prop('selected', true);
    
    $('#date').val(date); 
    $('#time_from').val(time_from); 
    $('#time_to').val(time_to); 
    
    $('#room_no option[value = "'+room_no_id+'"]').prop('selected', true);

    $('#remarks').val(remarks); 

    $('.cause-list-modal-title').html('<i class="fa fa-plus"></i> Add Cause List');
	$('#addCauseListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);
}

// Add cause list form
$('#form_add_cause_list').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#cause_list_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("form_add_cause_list"));
		urls =base_url+"service/cause_list_operation";
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
	                	enable_submit_btn('cause_list_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('cause_list_btn_submit');

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

	                	// Enable the submit button
	                	enable_submit_btn('cause_list_btn_submit');

	                	// Redraw the datatable
	                	dataTableCauseList = $('#dataTableCauseList').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#addCauseListModal').modal("hide");
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
        case_no: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        title_of_case: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        arbitrator_name: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        purpose: {							
           validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
        
    }
})


// Delete function for cause list
function btnDeleteCauseList(id){
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
					url: base_url+'service/delete_cause_list',
					type: 'POST',
					data: {
						id: id,
						csrf_trans_token: $('#csrf_trans_token').val()
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

			                	dataTableCauseList = $('#dataTableCauseList').DataTable();
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

// Button duplicate form to open the modal with duplicate form form
function btnCancelCauseList(event){

	// Change the submit button to edit
	$('#cause_list_btn_submit').attr('disabled',false);
	$("#cause_list_btn_submit").html("<i class='fa fa-paper-plane'></i> Save Details");

	// Reset the form
	$('#form_add_cause_list').trigger('reset');
    
    // Change the op type
    $("#form_add_cause_list input[name='op_type']").val("ADD_CAUSE_LIST_FORM");

    // Get data table instance to get the data through row
    var oTable = $('#dataTableCauseList').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)['id'];
	var case_no = oTable.fnGetData(row)['case_no'];
    
	$('#cancel_cause_list_hidden_id').val(id);
	$('#cancel_case_number').html(case_no);

	$('#cancelCauseListModal').modal(
		{
			backdrop: 'static',keyboard: false
		}
	);
}


// Cancel cause list form
$('#form_cancel_cause_list').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#cause_list_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById("form_cancel_cause_list"));
		urls =base_url+"service/cancel_cause_list_operation";
		$.ajax({
			url : urls,
			method : 'POST',
			data:formData,
	        contentType: false,
			processData: false,
			complete: function(){
				// Enable the submit button
				enable_submit_btn('cause_list_cancel_btn_submit');
			},
			success : function(response){

				// try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
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
	                	dataTableCauseList = $('#dataTableCauseList').DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$('#cancelCauseListModal').modal("hide");
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
        cancel_remarks: {							
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        }
        
    }
})

// ==================================================================================
// Filter the cause list

$('#f_cl_btn_submit').on('click', function(){
	dataTable.ajax.reload();
});
