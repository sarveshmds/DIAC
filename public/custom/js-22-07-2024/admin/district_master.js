	$('#UploadDistrictTab').click(function() {
	  	window.location.href = 'district-master';
	}); 
	$('#DistrictViewTab').click(function() {
	   	var district_table = $('#district_table').DataTable();
		district_table.draw();
		district_table.clear();
	});
	$( document ).ready(function() {
    	cmb_country();
    	var urls =base_url+"service/get_district_master/district_master";
		var district_table = $('#district_table').dataTable({
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
			    	data.csrf_district_token = $('#csrf_district_token').val();
			    }
			},
			"sDom":"<'row'<'col-xs-2 btn_district_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			//Set column definition initialisation properties.
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "dist_census_code","sClass":"alignLeft"},
				{"sName": "district_name","sClass":"alignLeft"},
				{"sName": "state_name","sClass":"alignLeft"},
				{"sName": "country_name","sClass":"alignLeft"},
				{"sName": "stats","sClass" : "alignCenter",
		            "mRender": function( data, type, full ) 
		            {
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{
					"sName": "button",data:null,"sWidth": "15%","sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editDistrictData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
					/*<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' align='center' onclick='deleteDistrictData(event);' title='Delete' ><i class='fa fa-trash'></i></button>"*/
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
		$("div.btn_district_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="form_reset()" data-toggle="modal" data-target="#manage_district_modal"><i class="fa fa-plus" aria-hidden="true"></i></button>');
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	    	$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
		});
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
	$("#cmb_country").change(function(){
    	get_state_dropdown();
	});
	function get_state_dropdown(x=''){
		$.ajax({
			url: base_url+"service/get_state_dropdown",
			type:"POST",
			data:{op_type:'get_state_name',country_code : $("#cmb_country").val()},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(x==res1[i].state_code){
						selected='selected';
					}else{
						selected='';
					}
					options = options + "<option value='"+res1[i].state_code+"' "+selected+">"+res1[i].state_name+"</option>";
				}
				$('#cmb_state').html("");
				$('#cmb_state').append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	// on click of add/update button it will validate then submit 	
	$('#form_district').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_district"));
			urls =base_url+"service/operation_districtdata";
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
		                    sweetAlert("District",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("District",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		district_table = $('#district_table').DataTable();
							district_table.draw();
							district_table.clear();
							$('#manage_district_modal').modal('hide');
							$('#district_code').prop('readonly', false);
							$('#form_district').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#span_district").html("Add District");
							$("#form_district input[name='op_type']").val("add_district_master");
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
	        district_code: {							
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
	        txtDistCensusCode: {							
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
	        district_name: {							
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
	        cmb_state: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        district_status: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function editDistrictData(event){
		$("#district_code").attr("readonly", true);
		$("#txtDistCensusCode").attr("readonly", true);
	    $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Update District");
		$("#span_district").html("Edit District");
		$('#form_district').data('bootstrapValidator').resetForm(true);
		$("#form_district input[name='op_type']").val("edit_district_master");
	    $('#manage_district_modal').modal({backdrop: 'static',keyboard: false});
	    var oTable = $('#district_table').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	   	
	    $('#district_code').val(oTable.fnGetData(row)['district_code']);
	    $('#txtDistCensusCode').val(oTable.fnGetData(row)['dist_census_code']);
	    $('#txtDistCensusCode').attr('readonly', 'readonly');
	    $('#district_name').val(oTable.fnGetData(row)['district_name']);
	    $('#cmb_country').val(oTable.fnGetData(row)['country_code']); 
	    get_state_dropdown(oTable.fnGetData(row)['state_code']);
	    $('#district_status').val(oTable.fnGetData(row)['record_status']); 
	}
	function deleteDistrictData(event){//delete user
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
			 	var oTable = $('#district_table').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_district";
			    var formData = {district_code:oTable.fnGetData(row)['district_code'],op_type:"delete_district"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("District",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,"success");
			                	district_table = $('#district_table').DataTable();
								district_table.draw();
								district_table.clear();
								$('#form_district').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#span_district").html("Add District");
								$("#form_district input[name='op_type']").val("add_district_master");
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
		$('#district_code').prop('readonly', false);
		$('#form_district').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add District");
		$("#span_district").html("Add District");
		$("#form_district input[name='op_type']").val("add_district_master");
	}
	/*Upload District Data*/
	
	function uploadDistrictdata(){
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
		        var urls = base_url + "service/import_districtdata";
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
							            window.location.href = 'district-master';
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