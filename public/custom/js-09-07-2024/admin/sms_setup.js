	/*
	* Author:  Debashish Jyotish
	* Date: 18/02/2018
	* Description : Getting the Email setup data.
	* Tab :Role
	* 
	**/
	var url =base_url+"service/get_smsprovider_data/get_smsprovider_data";
	var dtblsmsmaster = $('#dtblsmsmaster').dataTable({
		"processing": true, //Feature control the processing indicator.
	    "serverSide": true, //Feature control DataTables' server-side processing mode.
	    "bDestroy": true,
	    "paging":   true,
	    "info":     true,
	    "autoWidth": false,
	    "responsive":true,
	   	"searching":true,
	   	"scrollX": true,
		"ajax": {
		     "url": url,
		     "type": "POST",
		     "data": function (data)
		     {
		    	data.hidsmsCsrfToken = $('#hidsmsCsrfToken').val();
		     },
		},
		"sDom":"<'row'<'col-xs-4 btn_add_provider_sms_setup'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	    "columns": [
	       { "sName": "sl_no","sClass":"alignCenter"},
	       { "sName": "provider_id","bVisible": false },
	       { "sName": "provider_name"},
	       { "sName": "sms_url"},
	       { "sName": "user_name"},
	       { "sName": "password"},
	       { "sName": "sender"},
	       { "sName": "status","sWidth": "5%","sClass" : "alignCenter",
			        "mRender": function( data, type, full ) {
			             return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
			        }  
		   },
	       { "sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable'  onclick='updateSMSProvider(event);' id='edit' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
	       		<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteSMSProvider(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"
	       }
	    ],
	    "fnDrawCallback": function(oSettings, json){
	     	$('.tooltipTable').tooltipster({
		        theme: 'tooltipster-punk',
		      	animation: 'grow',
		        delay: 200, 
		        touchDevices: false,
		        trigger: 'hover'
	      	});          
	  	}	             
	});
	$("div.btn_add_provider_sms_setup").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="add_smsprovidersetup"><i class="fa fa-plus" aria-hidden="true"></i></button>');

	$("#add_smsprovidersetup").click(function(){
		$("#btn_sms").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddprovider").html("Add Records");
		$('#frm_smsprovider').data('bootstrapValidator').resetForm(true);//Reseting user form
	    $('#modal_sms_provider_setup').modal('show');
	    $("#frm_smsprovider input[name='op_type']").val("add_smsprovider");
	});	
	// on click of add/update button it will validate then submit 	
	$('#frm_smsprovider').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) {
			var formData = new FormData(document.getElementById("frm_smsprovider"));
			urls =base_url+"/service/operation_smsproviderdata";
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
		                    sweetAlert("PROVIDER",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("PROVIDER",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		providermaster = $('#dtblsmsmaster').DataTable();
							providermaster.draw();
							providermaster.clear();
							$('#frm_smsprovider').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_sms").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanaddprovider").html("Add Records");
							$('#hidesms_provider_id').val('');
							$("#frm_smsprovider input[name='op_type']").val("add_smsprovider");
							$('#modal_sms_provider_setup').modal('hide');
							
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
	        txtProviderName: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtSMSUrl: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }, 
	        txt_UserName: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }, 
	        txt_password: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	       	txt_Sender: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmbSMSProviderstatus: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function updateSMSProvider(event) {
	    $('#btn_sms')[0].innerHTML = '<i class="fa fa-edit"></i> Update';
		$('#spanaddprovider')[0].innerHTML = 'Edit Provider'; 
		$('#op_type').val('edit_smsprovider');
		$('#modal_sms_provider_setup').modal('show');
		var oTable = $('#dtblsmsmaster').dataTable();
		var row;
		if(event.target.tagName == "BUTTON")
		   row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
		   row = event.target.parentNode.parentNode.parentNode;
		
		$('#hidesms_provider_id').val(oTable.fnGetData(row)['provider_id']);
		$('#txtProviderName').val( oTable.fnGetData(row)['provider_name']);
		$('#txtSMSUrl').val( oTable.fnGetData(row)['sms_url']);
		$('#txt_UserName').val( oTable.fnGetData(row)['user_name']);
		$('#txt_password').val( oTable.fnGetData(row)['password']);	
		$('#txt_Sender').val( oTable.fnGetData(row)['sender']);
		$('#cmbSMSProviderstatus').val( oTable.fnGetData(row)['record_status']);
	}

	function deleteSMSProvider(event){
		swal({
			title: "Are you sure?",
			text: "You want to Delete the User!",
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
			 	var oTable = $('#dtblsmsmaster').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_smsprovider";
			    var formData = {provider_id:oTable.fnGetData(row)['provider_id'],op_type:"delete_smsprovider"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("PROVIDER",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,'success');
			                	smsp_table = $('#dtblsmsmaster').DataTable();
								smsp_table.draw();
								smsp_table.clear();
								$('#frm_smsprovider').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_sms").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanaddprovider").html("Add Records");
								$('#hidesms_provider_id').val('');
								$("#frm_smsprovider input[name='op_type']").val("add_smsprovider");
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
	//for form reset button click to reset the form
	$("#btn_provider_reset").click(function(){
	   $('#hidesms_provider_id').val('');
		$('#frm_smsprovider').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_sms").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddprovider").html("Add Records");
		$("#frm_smsprovider input[name='op_type']").val("add_smsprovider");
	});

	
	function get_smssetup_data(){
		var url =base_url+"service/get_smssetup_data/get_smssetup";
		var dtblsmssetup = $('#dtblsmssetup').dataTable({
			"processing": true, //Feature control the processing indicator.
		    "serverSide": true, //Feature control DataTables' server-side processing mode.
		    "bDestroy": true,
		    "paging":   true,
		    "info":     true,
		    "autoWidth": false,
		    "responsive":true,
		   	"searching":true,
		   	"scrollX": true,
			"ajax": {
			     "url": url,
			     "type": "POST",
			     "data": function (data)
			     {
			    	data.hidsmsSetupCsrfToken = $('#hidsmsSetupCsrfToken').val();
			     },
			},
			"sDom":"<'row'<'col-xs-4 btn_add_sms_setup'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
		    "columns": [
		       { "sName": "sl_no","sClass":"alignCenter"},
		       { "sName": "sms_setup_id","bVisible": false },
		       { "sName": "sms_type"},
		       { "sName": "subject"},
		       { "sName": "content"},
		       { "sName": "provider_name"},
		       { "sName": "status","sWidth": "5%","sClass" : "alignCenter",
			        "mRender": function( data, type, full ) {
			             return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
			        }  
			    },
		       	{
		       		"sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable'  onclick='updateSMSSetup(event);' id='edit' title='Edit'><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
		       			<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteSMSSetup(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"
		       	}
		    ],
		    "fnDrawCallback": function(oSettings, json){
		     	$('.tooltipTable').tooltipster({
			        theme: 'tooltipster-punk',
			      	animation: 'grow',
			        delay: 200, 
			        touchDevices: false,
			        trigger: 'hover'
		      	});          
	  		}	             
		});
	}get_smssetup_data();
	$("div.btn_add_sms_setup").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="add_smssetup"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    	$($.fn.dataTable.tables(true)).DataTable()
       .columns.adjust()
 	});
 	
 	$("#add_smssetup").click(function(){
 		$("#btn_sms_setup").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddsms").html("Add Records");
		$('#frm_sms_setup').data('bootstrapValidator').resetForm(true);//Reseting user form
	    $('#modal_sms_setup').modal('show');
	    $("#frm_sms_setup input[name='op_type_sms']").val("add_smssetup");
	});	
	function updateSMSSetup(event) {
	    $('#btn_sms_setup')[0].innerHTML = '<i class="fa fa-edit"></i> Update';
		$('#spanaddsms')[0].innerHTML = 'Edit Sms Setup'; 
		$('#op_type_sms').val('edit_smssetup');
		$('#modal_sms_setup').modal('show');
		var oTable = $('#dtblsmssetup').dataTable();
		var row;
		if(event.target.tagName == "BUTTON")
		   row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
		   row = event.target.parentNode.parentNode.parentNode;
		
		$('#sms_setup_id').val(oTable.fnGetData(row)['sms_setup_id']);
		$('#txtSMSType').val( oTable.fnGetData(row)['sms_type']);
		$('#txtSubject').val( oTable.fnGetData(row)['subject']);
		$('#txtSMSContent').val( oTable.fnGetData(row)['content']);
		$('#txtSMSProvider').val( oTable.fnGetData(row)['provider_name']);	
		$('#cmbSMSstatus').val( oTable.fnGetData(row)['status']);
	}
	// on click of add/update button it will validate then submit 	
	$('#frm_sms_setup').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) {
			var formData = new FormData(document.getElementById("frm_sms_setup"));
			urls =base_url+"/service/operation_smsdata";
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
		                	$('#errorlogsms').html('');
		                	$('#errorlogsms').hide();
		                    sweetAlert("SMS",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlogsms').html(obj.msg);
		                	$('#errorlogsms').show();
		                } else {
		                	sweetAlert("SMS",obj.msg, "success");
		                	$('#errorlogsms').html('');
		                	$('#errorlogsms').hide();
		            		smssetup = $('#dtblsmssetup').DataTable();
							smssetup.draw();
							smssetup.clear();
							$('#frm_sms_setup').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_sms_setup").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanaddsms").html("Add Records");
							$('#sms_setup_id').val('');
							$("#frm_sms_setup input[name='op_type_sms']").val("add_smssetup");
							$('#modal_sms_setup').modal('hide');
							
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
	        txtSMSType: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtSubject: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }, 
	        txtSMSContent: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }, 
	        txtSMSProvider: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	$("#btn_sms_setup_reset").click(function(){
	   $('#sms_setup_id').val('');
		$('#frm_sms_setup').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_sms_setup").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddsms").html("Add Records");
		$("#frm_sms_setup input[name='op_type_sms']").val("add_smssetup");
	});
	function deleteSMSSetup(event){
		swal({
			title: "Are you sure?",
			text: "You want to Delete the User!",
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
			 	var oTable = $('#dtblsmssetup').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_smssetup";
			    var formData = {sms_setup_id:oTable.fnGetData(row)['sms_setup_id'],op_type:"delete_smssetup"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("Sms Setup",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,'success');
			                	smsp_table = $('#dtblsmssetup').DataTable();
								smsp_table.draw();
								smsp_table.clear();
								$('#frm_sms_setup').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_sms_setup").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanaddsms").html("Add Records");
								$('#sms_setup_id').val('');
								$("#frm_sms_setup input[name='op_type_sms']").val("add_smssetup");
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
	
	