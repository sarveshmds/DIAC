	
	var urls =base_url+"service/get_gencode_data/gencode_data";
	var gen_code_table = $('#gen_code_table').dataTable({
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"destroy": true,
		"paging":   true,
		"info":     true,
		"autoWidth": false,
		"scrollX":true,
		"responsive":false,
		"searching":true,
		// Load data for the table's content from an ajax source
		"ajax":
		{
			"url": urls,
			"type": "POST",
			"data": function (data){
		    	data.csrf_gencode_token = $('#csrf_gencode_token').val();
		    }
		},
		"sDom":"<'row'<'col-xs-4 btn_gencode_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row'<'col-xs-5' i>>><'col-xs-6'p>>",
		//Set column definition initialisation properties.
		"columns": [
			{"sName": "sl_no","sWidth": "5%","sClass":"alignCenter"},
			{"sName": "id","bVisible":false,"sWidth": "15%","sClass":"alignCenter"},
			{"sName": "gen_code_group","sWidth": "20%","sClass":"alignCenter"},
			{"sName": "gen_code","sWidth": "20%","sClass":"alignCenter"},
			{"sName": "description","sWidth": "20%","sClass":"alignCenter"},
			{"sName": "serial","sWidth": "20%","sClass":"alignCenter"},
			{ "sName": "status","sWidth": "5%","sClass" : "alignCenter",
	            "mRender": function( data, type, full ){
	                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
	            }  
	        },
			{"sName": "button",data:null,"sClass" : "alignCenter","sWidth": "15%","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editGenCodeData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
			<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteGencode(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"}
		],
		// to show tooltips in datatable
		"fnDrawCallback": function(oSettings, json){
	     		$('.tooltipTable').tooltipster({
		         	theme: 'tooltipster-punk',
		      		animation: 'grow',
		        	delay: 200, 
		         	touchDevices: false,
		         	trigger: 'hover'
	      		} );          
	  		}
	});
	$("div.btn_gencode_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="gencode_modal" ><i class="fa fa-plus" aria-hidden="true"></i></button>');
	
	$("#gencode_modal").click(function(){
		form_reset();
		$('#new_gc_grp_btn').show();
	    $('#manage_gencode_modal').modal('show');
	});	
	$("#new_gc_grp_btn").click(function(){
	    $('#addGenCodeGrp').modal('show');
	});	
	// on click of add/update button it will validate then submit 	
	$('#frm_gen_code').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) 
		{
			var formData = new FormData(document.getElementById("frm_gen_code"));
			urls =base_url+"service/operation_gencode_data";
			$.ajax({
				url : urls,
				method : 'POST',
				data:formData,
				cache: false,
		        contentType: false,
		        processData: false,
				success : function(response)
				{
					try {
		                var obj = JSON.parse(response);
		                if (obj.status == false) {
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		                    sweetAlert("GenCode",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("GenCode",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		gen_code_table = $('#gen_code_table').DataTable();
							gen_code_table.draw();
							gen_code_table.clear();
							$('#manage_gencode_modal').modal('hide');
							$('#new_gc_grp_btn').attr("disabled",false);
							$('#frm_gen_code').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanuser").html("Add Gen Code");
							$('#hid_id').val('');
							$('#gen_code').prop('readonly', false);
							$('#gen_code_group').attr("disabled", false);
							$('#hid_gen_code_group').val('');
							$("#frm_gen_code input[name='op_type']").val("add_gen_code");
		                }
		            } catch (e) {
		                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
		            }
				},error: function(err){
					toastr.error("unable to save");
				}
			});
		},
		//live: 'enabled',
	    fields:
	    {
	        gen_code_group: {							//form input select
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        gen_code: {							//form input type text
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        description: {							//form input type text
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        sl_no: {							//form input type number
	            validators: {
	                notEmpty: {
	                    message: 'Serial Number Required'
	                },
	                regexp: {
                        regexp: /^[1-9]{0,9}$/,
                        message: "Invalid Number Number"
            		}
	            }
	        },
	        status: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	//add new gen code group
	$('#add_new_gc').click(function(event){
    	event.preventDefault();
    	$('#addGenCodeGrp').modal("hide");
    	var new_val = $("#new_gc_grp").val().toUpperCase();
    	var select = document.getElementById("gen_code_group");
    	if(new_val == '')
    		toastr.error("Blank Entry!");
    	else{
    		for (var i = 0; i < select.length; i++){
    			if(select.options[i].value == new_val){
    				toastr.error("Value Already Exists!");
    				new_val = '';
    				break;
    			}
    		}
	    	if(new_val != ''){
				$('#gen_code_group').append("<option value='"+new_val+"' selected>"+new_val+"</option>");
	    		$('#gen_code_group').val(new_val);
			}
    	}
    	$('#new_gc_grp').val('');
    	form_reset();
	});
//on edit click assign the value to text field
function editGenCodeData(event){
	$('#new_gc_grp_btn').hide();
	$('#manage_gencode_modal').modal('show');
	$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
	$("#spanuser")[0].innerHTML ="Edit Gen Code";
    $("#frm_gen_code input[name='op_type']").val("edit_gen_code");
    var oTable = $('#gen_code_table').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
	var hid_id = oTable.fnGetData(row)['id'];
	var gen_code_group = oTable.fnGetData(row)['gen_code_group'];
	var gen_code = oTable.fnGetData(row)['gen_code'];
	var description = oTable.fnGetData(row)['description'];
	var sl_no = oTable.fnGetData(row)['sl_no'];
    var status = oTable.fnGetData(row)['status'];
    $('#hid_id').val(hid_id);
    $('#hid_gen_code_group').val(gen_code_group);
	$('#gen_code_group').attr("disabled", true);
	$('#gen_code_group').val(gen_code_group);
	$('#gen_code').prop('readonly', true);
    $('#gen_code').val(gen_code);
    $('#sl_no').val(sl_no);
    $('#description').val(description);  
    $('#status').val(status); 
}
//for form reset button click to reset the form
function form_reset(){
	$('#new_gc_grp_btn').attr("disabled",false);
	$('#hid_id').val('');
	$('#gen_code').prop('readonly', false);
	$('#gen_code_group').attr("disabled", false);
	$('#hid_gen_code_group').val('');
	$('#frm_gen_code').data('bootstrapValidator').resetForm(true); //to reset the form
	$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
	$("#spanuser").html("Add Gen Code");
	$("#frm_gen_code input[name='op_type']").val("add_gen_code");
}
function deleteGencode(event){
	swal({
		title: "Are you sure?",
		text: "You want to Delete the Gen Code!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",
		cancelButtonText: "No, cancel",
		closeOnConfirm: false,
		closeOnCancel: true
	},
	function(isConfirm){
		if(isConfirm)
		{
		 	var oTable = $('#gen_code_table').dataTable();
	        var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
	        var urls = base_url + "service/delete_genCode";
		    var formData = {gen_code_group:oTable.fnGetData(row)['gen_code_group'],gen_code:oTable.fnGetData(row)['gen_code'],op_type:"delete_genCodeDesc"};
			$.ajax({
				url: urls,
		        method: 'POST',
		        data: formData,
		        success: function (response) {
		            try {
		                var obj = JSON.parse(response);
		                if (!obj.status) {
		                    sweetAlert("Gen Code",obj.msg, "error");
		                } else {
		                	swal('Gen Code!',obj.msg,'success');
		                	gencodemaster = $('#gen_code_table').DataTable();
							gencodemaster.draw();
							gencodemaster.clear();
							$('#frm_gen_code').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanuser").html("Add Gen Code");
							$('#hid_id').val('');
							$("#frm_gen_code input[name='op_type']").val("add_gen_code");
		                }
		            } catch (e) {
		                sweetAlert("Sorry","We are unable to Process !", "error");
		            }
		        }, error: function (err) {
		            toastr.error(err);
		        }
			});
		}
	});
}
	
