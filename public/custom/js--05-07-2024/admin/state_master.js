	$('#UploadCountryTab').click(function() {
	   window.location.href = 'state-master';
	}); 
	$('#UploadCountryViewTab').click(function() {
	   	state_table = $('#dtblState').DataTable();
		state_table.draw();
		state_table.clear();
	});
	$(document).ready(function() {
	    cmb_country();
	    
	    var urls =base_url+"service/get_statemaster_data/get_state_master";
		var dtblState = $('#dtblState').dataTable({
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
			    	data.csrf_state_token = $('#csrf_state_token').val();
			    }
			},
			"sDom":"<'row'<'col-xs-2 btn_state_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			//Set column definition initialisation properties.
			"columns": [
				{"sName": "sl_no","sWidth": "5%","sClass":"alignCenter"},
				{"sName": "State Code","sWidth": "20%","sClass":"alignLeft"},
				{"sName": "State Name","sWidth": "20%","sClass":"alignLeft"},
				{"sName": "Country Name","sWidth": "20%","sClass":"alignLeft"},
				{ "sName": "Record Status","sWidth": "5%","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{
					"sName": "button",data:null,"sWidth": "15%","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editStateData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
					/*<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' align='center' onclick='deleteStateData(event);' title='Delete' ><i class='fa fa-trash'></i></button>"*/
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
		$("div.btn_state_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" data-toggle="modal" onclick="form_reset()" data-target="#manage_state_modal"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	});
	
	function cmb_country(){
		$.ajax({
			url: base_url+"service/get_country_dropdown",
			type:"POST",
			data:{op_type:'get_county_name'},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					options = options + "<option value='"+res1[i].country_code+"' >"+res1[i].country_name+"</option>";
				}
				$('#cmb_country').html("");  
				$('#cmb_country').append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	// on click of add/update button it will validate then submit 	
	$('#form_state').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_state"));
			urls =base_url+"service/op_state_master";
			$.ajax({
				url : urls,
				method : 'POST',
				data:formData,
				cache: false,
		        contentType: false,
		        processData: false,
				success : function(response){
					try {
		                var obj = JSON.parse(response);
		                if (obj.status == false) {
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		                    sweetAlert("State Data",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("State",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		dtblState = $('#dtblState').DataTable();
							dtblState.draw();
							dtblState.clear();
							$('#manage_state_modal').modal('hide');
							$('#state_code').prop('readonly', false);
							$('#form_state').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanstate").html("Add State");
							$("#form_state input[name='op_type']").val("add_state_master");
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
	        state_code: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            },
	            regexp: {
	                regexp: /^[^*+|\":<>[\]{}`\\!.#%';@&$]+$/,
	                message: 'Special Character Not Allowed'
		        }
	        },
	        state_name: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            },
	            regexp: {
	                regexp: /^[^*+|\":<>[\]{}`\\!.#%';@&$]+$/,
	                message: 'Special Character Not Allowed'
		        }
	        },
	        cmb_country: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        state_status: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	
	function editStateData(event){
		$('#state_code').prop('readonly', true);
		$('#manage_state_modal').modal({backdrop: 'static',keyboard: false});
		$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
		$("#spanstate")[0].innerHTML ="Edit State";
		$('#form_state').data('bootstrapValidator').resetForm(true);
	    $("#form_state input[name='op_type']").val("state_master_edit");
	    var oTable = $('#dtblState').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	   
	    var state_code = oTable.fnGetData(row)['state_code'];           
	    var state_name = oTable.fnGetData(row)['state_name'];
	    var country_code = oTable.fnGetData(row)['country_code'];
	    var record_status = oTable.fnGetData(row)['record_status'];
	    $('#state_code').val(state_code);
	    $('#state_name').val(state_name);
	    $('#cmb_country').val(country_code);
	    $('#state_status').val(record_status);
	}
	
	function form_reset(){
		$('#state_code').prop('readonly', false);
		$('#form_state').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanstate").html("Add State");
		$("#form_state input[name='op_type']").val("add_state_master");
	}
	function deleteStateData(event){//delete user
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
			 	var oTable = $('#dtblState').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_state_data";
			    var formData = {state_code:oTable.fnGetData(row)['state_code'],op_type:"delete_state"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("State",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,"success");
			                	dtblState = $('#dtblState').DataTable();
								dtblState.draw();
								dtblState.clear();
								$('#form_state').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanstate").html("Add State");
								$("#form_state input[name='op_type']").val("add_state_master");
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
	/*Upload State Data*/
	
	function uploadstatedata(){
		swal({
			title: "Are you sure?",
			text: "You want to Upload the data!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, Upload it!",
			cancelButtonText: "No, cancel",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function(isConfirm){
			if(isConfirm){
			 	var formData = {importfile:$('#hideFile').val()};
		        var urls = base_url + "service/import_statedata";
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try{
			            	var obj = JSON.parse(response);
			              	if (obj.status == true) {
			              		setTimeout(function() {
							        swal({
							            title: 'Updated!',
							            text: "Successfully!",
							            type: "success"
							        }, function() {
							            window.location.href = 'state-master';
							        });
							    }, 1000);
			                }else{
			                	swal("Upload Failed",obj.msg, "error");
			                }
			            }catch(e){
			            	swal("Sorry","We are unable to Process !", "error");
			            }
			        }, error: function (err) {
			        	swal("Sorry",err, "error");
			        }
				});
			}
		});
	}
	