
	var url =base_url+"service/get_emailsetup_data/get_emailprovider_data";
	var dtblprovidermaster = $('#dtblprovidermaster').dataTable({
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
		    "data": function (data){
		    	data.hidemailCsrfToken = $('#hidemailCsrfToken').val();
		    }
		},
		"sDom":"<'row'<'col-xs-4 btn_add_provider'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	    "columns": [
	       { "sName": "sl_no","sClass":"alignCenter"},
	       { "sName": "provider_id","bVisible": false },
	       { "sName": "provider_name"},
	       { "sName": "host_name"},
	       { "sName": "port_no"},
	       { "sName": "email_id"},
	       { "sName": "PASSWORD"},
	       { "sName": "smtp_auth"},
	       { "sName": "smtp_secure"},
	       { "sName": "record_status","sWidth": "5%","sClass" : "alignCenter",
		        "mRender": function( data, type, full ) {
		             return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		        }  
		    },
	       { "sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable'  onclick='updateProvider(event);' id='edit' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
	       		<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteProvider(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"
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
	 $("div.btn_add_provider").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="add_provider"><i class="fa fa-plus" aria-hidden="true"></i></button>');	       
	$("#add_provider").click(function(){
		$("#spanaddprovider")[0].innerHTML ="Add Provider";
		$('#frm_provider').data('bootstrapValidator').resetForm(true); //to reset the form
	    $('#provider_modal').modal('show');
	});	

	// on click of add/update button it will validate then submit 	
	$('#frm_provider').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) {
			var formData = new FormData(document.getElementById("frm_provider"));
			urls =base_url+"/service/operation_providerdata";
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
		                	$('#frm_provider').data('bootstrapValidator').resetForm(true);
		                	$('#provider_modal').modal('hide');
		            		providermaster = $('#dtblprovidermaster').DataTable();
							providermaster.draw();
							providermaster.clear();
							
							$('#frm_provider').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_provider").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanaddprovider").html("Add Provider");
							$('#hideemail_provider_id').val('');
							$("#frm_provider input[name='op_type']").val("add_provider");
							
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
	        txtProvidername: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtHostName: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }, 
	        txtPort: {							//form input type name
	            validators: {
	                integer: {
	                    message: 'The value is not an integer'
	                }
	            }
	        },
	        txt_Email: {							//form input type name
	            validators: {
	                emailAddress: {
	                    message: 'The value is not a valid email address'
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
	       	cmb_smptauth: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_smptsecure: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmbStatus: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function updateProvider(event) {
		$('#provider_modal').modal('show');
	    $('#btn_provider')[0].innerHTML = '<i class="fa fa-edit"></i> Update';
		$('#spanaddprovider')[0].innerHTML = 'Edit Provider'; 
		
		$('#op_type').val('edit_provider');
		
		var oTable = $('#dtblprovidermaster').dataTable();
		var row;
		if(event.target.tagName == "BUTTON")
		   row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
		   row = event.target.parentNode.parentNode.parentNode;
		   
		//alert(oTable.fnGetData(row)['provider_name']);
		$('#hideemail_provider_id').val(oTable.fnGetData(row)['provider_id']);
		$('#txtProvidername').val( oTable.fnGetData(row)['provider_name']);
		$('#txtHostName').val( oTable.fnGetData(row)['host_name']);
		$('#txtPort').val( oTable.fnGetData(row)['port_no']);
		$('#txt_Email').val( oTable.fnGetData(row)['email_id']);
		$('#txt_password').val( oTable.fnGetData(row)['password']);	
		$('#cmb_smptauth').val( oTable.fnGetData(row)['smtp_auth']);
		$('#cmb_smptsecure').val( oTable.fnGetData(row)['smtp_secure']);
		$('#cmbStatus').val(oTable.fnGetData(row)['record_status']);
	}

	function deleteProvider(event){
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
		 	var oTable = $('#dtblprovidermaster').dataTable();
	        var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
	        var urls = base_url + "/service/delete_provider";
		    var formData = {provider_id:oTable.fnGetData(row)['provider_id'],op_type:"delete_emailprovider"};
			$.ajax({
				url: urls,
		        method: 'POST',
		        data: formData,
		        success: function (response) {
		            try {
		                var obj = JSON.parse(response);
		                if (!obj.status) {
		                    sweetAlert("USER",obj.msg, "error");
		                } else {
		                	swal('Deleted!',obj.msg,'success');
		                	providermaster = $('#dtblprovidermaster').DataTable();
							providermaster.draw();
							providermaster.clear();
							$('#frm_provider').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_provider").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanaddprovider").html("Add Provider");
							$('#hideemail_provider_id').val('');
							$("#frm_provider input[name='op_type']").val("add_provider");
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
	$("#btn_providerreset").click(function(){
	   $('#hideemail_provider_id').val('');
		$('#frm_provider').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_provider").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddprovider").html("Add Provider");
		$("#frm_provider input[name='op_type']").val("add_provider");
	});
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(){
	    $($.fn.dataTable.tables(true)).DataTable()
	       .columns.adjust()
 	});

	/*
	* Author:  		Ashutosh Mishra
	* Date:    		17/07/2018
	* Description : Getting the Email setup data.
	* Tab :			Role
	* 
	**/
	var url =base_url+"service/get_setupemail_data/get_emailsetup_data";
	var dtblemailsetup = $('#dtblemailsetup').dataTable({
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
		    "data": function (data){
		    	data.emailSetupCsrfToken = $('#hidemailsetupCsrfToken').val();
		    }
		},
		"sDom":"<'row'<'col-xs-4 btn_add_email'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
	    "columns": [
	       { "sName": "sl_no","sClass":"alignCenter"},
	       { "sName": "email_setup_id","bVisible": false },
	       { "sName": "email_type"},
	       { "sName": "subject"},
	       { "sName": "content"},
	       { "sName": "provider_name"},
	       { "sName": "institute_code"},
	       { "sName": "status","sWidth": "5%","sClass" : "alignCenter",
		        "mRender": function( data, type, full ) {
		             return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		        }  
		    },
	       { 
	       		"sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' title='Edit'  onclick='updateEmailSetup(event);' id='edit' ><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
	       		<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' title='Delete' onclick='deleteEmailSetup(event);'  id='delete'><i class='fa fa-trash'></i></button>"
	       },
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
	 $("div.btn_add_email").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="add_email"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	

	$("#add_email").click(function(){
		$("#spanaddemail")[0].innerHTML ="Add Email";
		$('#frm_email').data('bootstrapValidator').resetForm(true); //to reset the form
	    $('#email_modal').modal('show');
	});
	
	// on click of add/update button it will validate then submit 	
	$('#frm_email').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) {
			var formData = new FormData(document.getElementById("frm_email"));
			urls =base_url+"/service/operation_emaildata";
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
		                    sweetAlert("Email",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("Email",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		                	$('#frm_email').data('bootstrapValidator').resetForm(true);
		                	$('#email_modal').modal('hide');
		            		emailmaster = $('#dtblemailsetup').DataTable();
							emailmaster.draw();
							emailmaster.clear();
							
							$('#frm_email').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit_email").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanaddemail").html("Add Email");
							$('#hideemail_provider_id').val('');
							$("#frm_email input[name='op_type']").val("add_email");
							
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
	        txtMailType: {							//form input type name
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
	        txtContent: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtProvider: {							//form input type name
	            validators: {
	               notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmbEmailStatus: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function updateEmailSetup(event) {
		$('#email_modal').modal('show');
	    $('#btn_submit_email')[0].innerHTML = '<i class="fa fa-edit"></i> Update';
		$('#spanaddemail')[0].innerHTML = 'Edit Email'; 
		
		$('#op_type_email').val('edit_email');
		
		var oTable = $('#dtblemailsetup').dataTable();
		var row;
		if(event.target.tagName == "BUTTON")
		   row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
		   row = event.target.parentNode.parentNode.parentNode;
		   
		//alert(oTable.fnGetData(row)['provider_name']);
		$('#hidemail_setup_id').val(oTable.fnGetData(row)['email_setup_id']);
		$('#txtMailType').val( oTable.fnGetData(row)['email_type']);
		$('#txtSubject').val( oTable.fnGetData(row)['subject']);
		$('#txtContent').val( oTable.fnGetData(row)['content']);
		$('#txtProvider').val( oTable.fnGetData(row)['provider_name']);
		//$('#txt_password').val( oTable.fnGetData(row)['institute_code']);	
		$('#cmbEmailStatus').val( oTable.fnGetData(row)['status']);
	}
	function deleteEmailSetup(event){
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
			 	var oTable = $('#dtblemailsetup').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_emailsetup";
			    var formData = {
			    				email_setup_id:oTable.fnGetData(row)['email_setup_id'],
			    				op_type:"delete_EmailSetup"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("Email Setup",obj.msg, "error");
			                } else {
			                	swal('Email Setup!',obj.msg,'success');
			                	emailSetupmaster = $('#dtblemailsetup').DataTable();
								emailSetupmaster.draw();
								emailSetupmaster.clear();
								$('#frm_email').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit_email").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanaddemail").html("Add Email");
								$('#hidemail_setup_id').val('');
								$("#frm_email input[name='op_type_email']").val("add_email");
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
	$("#btn_emailreset").click(function(){
	   $('#hidemail_setup_id').val('');
		$('#frm_email').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit_email").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanaddemail").html("Add Email");
		$("#frm_email input[name='op_type_email']").val("add_email");
	});