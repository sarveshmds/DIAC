	$('#UploadCountryTab').click(function() {
	   window.location.href = 'country-master';;
	}); 
	$('#UploadCountryViewTab').click(function() {
	    country_table = $('#country_table').DataTable();
		country_table.draw();
		country_table.clear();
	});
	
	var urls =base_url+"service/get_country_data/countrydata";
	var country_table = $('#country_table').dataTable({
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
		"ajax":{
			"url": urls,
			"type": "POST",
			"data": function (data){
		    	data.csrf_country_token = $('#csrf_country_token').val();
		    }
		},
		"sDom":"<'row'<'col-xs-2 btn_country_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
		//Set column definition initialisation properties.
		"columns": [
			{"sName": "sl_no","sClass":"alignCenter"},
			{"sName": "country_code","sClass":"alignCenter"},
			{"sName": "country_name","sClass":"alignLeft"},
			{"sName": "stats","sClass" : "alignCenter",
	            "mRender": function( data, type, full ) 
	            {
	                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
	            }  
	        },
			{
				"sName": "button",data:null,"sWidth": "15%","sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editCountryData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
				/*<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' align='center' onclick='deleteUserData(event);' title='Delete' ><i class='fa fa-trash'></i></button>"*/
	       	}
		],
		// to show tooltips in datatable
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
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
	});
	
	$("div.btn_country_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="form_reset()" data-toggle="modal" data-target="#manage_country_modal"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	// on click of add/update button it will validate then submit 	
	$('#frm_country').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) 
		{
			country_code = $('#country_code').val();
			country_name = $('#country_name').val();
			country_status = $('#country_status').val();
			var formData = new FormData(document.getElementById("frm_country"));
			urls =base_url+"service/operation_countrydata";
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
		                    sweetAlert("Country Data",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("Country Data",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		country_table = $('#country_table').DataTable();
							country_table.draw();
							country_table.clear();
							$('#manage_country_modal').modal('hide');
							$('#country_code').prop('readonly', false);
							$('#frm_country').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanuser").html("Add Country");
							$("#frm_country input[name='op_type']").val("add_country");
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
	        country_code: {							//form input type country_code
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                },
	                regexp: {
		                regexp: /^[^*+|\":<>[\]{}`\\!.#%';@&$]+$/,
		                message: 'Special Character Not Allowed'
		            }
	            }
	        },
	        country_name: {							//form input type country_name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                },
	                regexp: {
		                regexp: /^[^*+|\":<>[\]{}`\\!.#%';@&$]+$/,
		                message: 'Special Character Not Allowed'
		            }
	            }
	        },
	        country_status: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function editCountryData(event){//on edit click assign the value to text field
		$('#new_gc_grp_btn').attr("disabled",true);
		$('#manage_country_modal').modal({backdrop: 'static',keyboard: false});
		$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
		$("#spanuser")[0].innerHTML ="Edit Country";
		$('#frm_country').data('bootstrapValidator').resetForm(true);
	    $("#frm_country input[name='op_type']").val("edit_country");
	    $('#country_code').prop('readonly', true);
	    var oTable = $('#country_table').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	    var country_code = oTable.fnGetData(row)['country_code'];
	    var country_name = oTable.fnGetData(row)['country_name'];           
	    var status = oTable.fnGetData(row)['record_status'];
	    $('#country_code').val(country_code);
	    $('#country_name').val(country_name);
	    $('#country_status').val(status); 
	}
	function deleteUserData(event){//delete user
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
			 	var oTable = $('#country_table').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_data_country";
			    var formData = {country_code:oTable.fnGetData(row)['country_code'],op_type:"delete_country"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("COUNTRY",obj.msg, "error");
			                }else{
			                	swal('Deleted!',obj.msg,"success");
			                	country_table = $('#country_table').DataTable();
								country_table.draw();
								country_table.clear();
								$('#frm_country').data('bootstrapValidator').resetForm(true); //to reset the form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanuser").html("Add Country");
								$("#frm_country input[name='op_type']").val("add_country");
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
	function form_reset(){
		$('#country_code').prop('readonly', false);
		$('#frm_country').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add Country");
		$("#spanuser").html("Add Country");
		$("#frm_country input[name='op_type']").val("add_country");
	}
	
	/*Upload Country Data*/
	
	function uploadcountrydata(){
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
		        var urls = base_url + "service/import_countrydata";
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
							            window.location.href = 'country-master';
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