	//after clicking of tab   the respective  table and the respective dropdown will be rendred
	$('#ControllerTab').click(function() {
	    var dtblControllerMaster = $('#dtblControllerMaster').DataTable();
		dtblControllerMaster.draw();// ReLoad the Datatable
		dtblControllerMaster.clear();
	}); 
	$('#ModelTab').click(function() {
	    var dtblmodelmaster = $('#dtblmodelmaster').DataTable();
		dtblmodelmaster.draw();// ReLoad the Datatable
		dtblmodelmaster.clear();
	});
	$('#ViewTab').click(function() {
	    var dtblviewmaster = $('#dtblviewmaster').DataTable();
		dtblviewmaster.draw();// ReLoad the Datatable
		dtblviewmaster.clear();
		
	});
	$(document).ready(function(){
		dtblControllerMaster();
		dtblmodelmaster();
		dtblViewMaster();
	});
	
	function dtblControllerMaster(){
		var url =base_url+"service/get_controller_data/get_controller";
		var dtblControllerMaster = $('#dtblControllerMaster').dataTable({
			"processing": true, //Feature control the processing indicator.
	        "serverSide": true, //Feature control DataTables' server-side processing mode.
	        "bDestroy": true,
	        "paging":   true,
	        "info":     true,
	        "autoWidth": false,
	        "responsive":true,
	       	"searching":true,
			"ajax": {
			     "url": url,
			     "type": "POST",
			     "data": function(data) {
			       data.hidControllerCsrfToken = $("#hidControllerCsrfToken").val();
			    }
			},
			"sDom":"<'row'<'col-xs-4 btn_controller_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	        "columns": [
	           { "sName": "sl_no","sClass":"alignCenter"},
	           { "sName": "controller_code"},
	           { "sName": "controller_name"},
	           { "sName": "created_on" }
	        ] , 
	        "fnDrawCallback": function(oSettings, json) {
		     	$('.tooltipTable').tooltipster({
		         	theme: 'tooltipster-punk',
		      		animation: 'grow',
		        	delay: 200, 
		         	touchDevices: false,
		         	trigger: 'hover'
	      		});          
		  	}	           
		});
		$("div.btn_controller_modal").html('<button class="btn btn-info btn-circle tooltipTable" title="Add" data-toggle="modal" data-target="#controller_modal" onclick="Controllerreset();"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	}
	$('#form_controller').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_controller"));
			urls =base_url+"service/operation_controller";
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
		                	$('#errorlog_controller').html('');
		                	$('#errorlog_controller').hide();
		                    sweetAlert("Controller",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog_controller').html(obj.msg);
		                	$('#errorlog_controller').show();
		                }else if(obj.status === 'exists'){
		                	sweetAlert("Controller",obj.msg, "error");
		                }else{
		                	sweetAlert("Controller",obj.msg, "success");
		                	$('#errorlog_controller').html('');
		                	$('#errorlog_controller').hide();
		            		var dtblControllerMaster = $('#dtblControllerMaster').DataTable();
							dtblControllerMaster.draw();// ReLoad the Datatable
							dtblControllerMaster.clear();
							$('#controller_modal').modal('hide');
							$('#txt_controller_code').prop('readonly', false);
							$('#form_controller').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#span_controller").html("Add Controller");
							$("#form_controller input[name='op_controller']").val("add_controller");
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
	        txt_controller_code: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txt_controller_name: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function Controllerreset(){
		$('#form_controller').data('bootstrapValidator').resetForm(true);//Reseting user form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#span_controller").html("Add Controller");
		$("#form_controller input[name='op_controller']").val("add_controller");
	}
	
	function dtblmodelmaster(){
		var url =base_url+"service/get_model_data/model_data";
		var dtblmodelmaster = $('#dtblmodelmaster').dataTable({
			"processing": true, //Feature control the processing indicator.
	        "serverSide": true, //Feature control DataTables' server-side processing mode.
	        "bDestroy": true,
	        "paging":   true,
	        "info":     true,
	        "autoWidth": false,
	        "responsive":true,
	       	"searching":true,
			"ajax": {
			     "url": url,
			     "type": "POST",
			     "data": function(data) {
			       data.hidmodelCsrfToken = $("#hidmodelCsrfToken").val();
			    }
			},
			"sDom":"<'row'<'col-xs-4 btn_model_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	        "columns": [
	           { "sName": "sl_no","sClass":"alignCenter"},
	           { "sName": "model_code"},
	           { "sName": "model_name"},
	           { "sName": "created_on" }
	        ] , 
	        "fnDrawCallback": function(oSettings, json) {
		     	$('.tooltipTable').tooltipster({
		         	theme: 'tooltipster-punk',
		      		animation: 'grow',
		        	delay: 200, 
		         	touchDevices: false,
		         	trigger: 'hover'
	      		});          
		  	}	           
		});
		$("div.btn_model_modal").html('<button class="btn btn-info btn-circle tooltipTable" title="Add" data-toggle="modal" data-target="#model_modal" onclick="ModelReset();"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	}
	$('#form_model').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_model"));
			urls =base_url+"service/operation_model";
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
		                	$('#errorlogmodel').html('');
		                	$('#errorlogmodel').hide();
		                    sweetAlert("Model",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlogmodel').html(obj.msg);
		                	$('#errorlogmodel').show();
		                }else if(obj.status === 'exists'){
		                	sweetAlert("Model",obj.msg, "error");
		                }else{
		                	sweetAlert("Model",obj.msg, "success");
		                	$('#errorlogmodel').html('');
		                	$('#errorlogmodel').hide();
		            		var dtblmodelmaster = $('#dtblmodelmaster').DataTable();
							dtblmodelmaster.draw();// ReLoad the Datatable
							dtblmodelmaster.clear();
							$('#model_modal').modal('hide');
							$('#form_model').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#span_addmodel").html("Add Model");
							$("#form_model input[name='op_type_model']").val("add_model");
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
	        txt_model_code: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtModelName: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function ModelReset(){
		$('#form_model').data('bootstrapValidator').resetForm(true);//Reseting user form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#span_addmodel").html("Add Model");
		$("#form_model input[name='op_type_model']").val("add_model");
	}
	function dtblViewMaster(){
		var url =base_url+"service/get_view_data/view_data";
		var dtblviewmaster = $('#dtblviewmaster').dataTable({
			"processing": true, //Feature control the processing indicator.
	        "serverSide": true, //Feature control DataTables' server-side processing mode.
	        "bDestroy": true,
	        "paging":   true,
	        "info":     true,
	        "autoWidth": false,
	        "responsive":true,
	       	"searching":true,
			"ajax": {
			     "url": url,
			     "type": "POST",
			     "data": function(data) {
			       data.hidViewCsrfToken = $("#hidViewCsrfToken").val();
			    }
			},
			"sDom":"<'row'<'col-xs-4 btn_view_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	        "columns": [
	           { "sName": "sl_no","sClass":"alignCenter"},
	           { "sName": "view_code"},
	           { "sName": "view_name"},
	           { "sName": "view_path"},
	           { "sName": "created_on" }
	        ] , 
	        "fnDrawCallback": function(oSettings, json) {
		     	$('.tooltipTable').tooltipster({
		         	theme: 'tooltipster-punk',
		      		animation: 'grow',
		        	delay: 200, 
		         	touchDevices: false,
		         	trigger: 'hover'
	      		});          
		  	}	           
		});
		$("div.btn_view_modal").html('<button class="btn btn-info btn-circle tooltipTable" title="Add" data-toggle="modal" data-target="#view_modal" onclick="ViewReset();"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	}
	$("#new_view_folder_btn").click(function(){
	    $('#add_view_folder').modal('show');
	});	
	//add new gen code group
	$('#add_new_view_path').click(function(event){
    	event.preventDefault();
    	$('#add_view_folder').modal("hide");
    	var new_val = $("#new_view_path").val();
    	var select = document.getElementById("cmb_view_path");
    	if(new_val == '')
    		toastr.error("Blank Entry!");
    	else{
    		for (var i = 0; i < select.length; i++){
    			if(select.options[i].value == new_val){
    				swal("view path already Exists!")
    				new_val = '';
    				break;
    			}
    		}
	    	if(new_val != ''){
				$('#cmb_view_path').append("<option value='"+new_val+"' selected>"+new_val+"</option>");
	    		$('#cmb_view_path').val(new_val);
			}
    	}
    	$('#new_gc_grp').val('');
	});
	$('#form_view').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_view"));
			urls =base_url+"service/operation_view";
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
		                	$('#errorlog_view').html('');
		                	$('#errorlog_view').hide();
		                    sweetAlert("View",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog_view').html(obj.msg);
		                	$('#errorlog_view').show();
		                }else if(obj.status === 'exists'){
		                	sweetAlert("View",obj.msg, "error");
		                }else{
		                	sweetAlert("View",obj.msg, "success");
		                	$('#errorlog_view').html('');
		                	$('#errorlog_view').hide();
		            		var dtblviewmaster = $('#dtblviewmaster').DataTable();
							dtblviewmaster.draw();// ReLoad the Datatable
							dtblviewmaster.clear();
							$('#view_modal').modal('hide');
							$('#form_view').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#span_view").html("Add View");
							$("#form_view input[name='op_view']").val("add_view");
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
	        cmb_view_path: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txt_view_code: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	         txt_view_name: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
	        
		}	
	});
	function ViewReset(){
		$('#form_view').data('bootstrapValidator').resetForm(true);//Reseting user form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#span_view").html("Add View");
		$("#form_view input[name='op_view']").val("add_view");
	}
	
