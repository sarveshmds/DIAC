	$('#UploadVillageTab').click(function() {
	   	window.location.href = 'village-master';;
	}); 
	$('#UploadVillageViewTab').click(function() {
	   // var dtblVillage = $('#dtblVillage').DataTable();
		//dtblVillage.draw();
		//dtblVillage.clear();
		get_village_data();
	});
	
	/********Modify By: Subhashree Date : 29-08-2019************/
	$('#btn_search').click(function() {
	    var dtblVillage = $('#dtblVillage').DataTable();
		dtblVillage.draw();
		dtblVillage.clear();
	});
	$( document ).ready(function() {
		get_state_dropdown();
	});
	
	$("#btn_filter_reset").click(function(){
		$("#cmb_district_filter").val('');
		$("#cmb_block_filter").val('');
		$("#cmb_gp_filter").val('');
		dtblVillage = $('#dtblVillage').DataTable();
		dtblVillage.draw();
		dtblVillage.clear();
	});
	
	function get_village_data()
	{
		var urls =base_url+"service/get_village_master/village_master";
		var dtblVillage = $('#dtblVillage').dataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"destroy": true,
			"paging":   true,
			"info":     true,
			"autoWidth": false,
			"scrollX":false,
			"responsive":false,
			"searching":true,
			// Load data for the table's content from an ajax source
			"ajax":{
				"url": urls,
				"type": "POST",
				"data": function (data){
			    	data.csrf_village_token = $('#csrf_Village_token').val();
			    	data.cmb_district_filter = $('#cmb_district_filter').val();
			    	data.cmb_block_filter = $('#cmb_block_filter').val();
			    	data.cmb_gp_filter = $('#cmb_gp_filter').val();
			    }
			},
			"sDom":"<'row'<'col-xs-2 btn_circle_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			//Set column definition initialisation properties.
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "pk_village_code","sClass":"alignCenter"},
				{"sName": "village_name","sClass":"alignLeft"},
				{"sName": "gp_name","sClass":"alignLeft"},
				{"sName": "block_name","sClass":"alignLeft"},
				{"sName": "district_name","sClass":"alignLeft"},
				{"sName": "status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ) 
		            {
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{
					"sName": "button",data:null,"sWidth": "15%","sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editVillageData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
					/*<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' align='center' onclick='deleteVillageData(event);' title='Delete' ><i class='fa fa-trash'></i></button>"*/
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
		$("div.btn_circle_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="form_reset()" data-toggle="modal" data-target="#manage_village_modal" data-backdrop= "static" data-keyboard= "false"><i class="fa fa-plus" aria-hidden="true"></i></button>');
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	    	$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
		});
	}get_village_data();
	function get_state_dropdown(x=''){
		$.ajax({
			url: base_url+"service/get_state_dropdown",
			type:"POST",
			data:{op_type:'get_state_name',country_code : 'IND'},
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
	$("#cmb_state").change(function(){
    	get_dist_dropdown();
	});
	function get_dist_dropdown(x='',y=''){
		if(y==''){
			state_code = $("#cmb_state").val();
		}else{
			state_code =y;
		}
		
		$.ajax({
			url: base_url+"service/get_dist_dropdown",
			type:"POST",
			data:{op_type:'get_dist_name',state_code : state_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(x==res1[i].district_code){
						selected='selected';
					}else{
						selected='';
					}
					options = options + "<option value='"+res1[i].district_code+"' data-attr='"+res1[i].dist_census_code+"' "+selected+">"+res1[i].district_name+"</option>";
				}
				$('#cmb_dist').html("");
				$('#cmb_dist').append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	$("#cmb_dist").change(function(){
    	get_block_dropdown(1);
	});
	
	$("#cmb_district_filter").change(function(){
    	get_block_dropdown(2);
	});
	function get_block_dropdown(x){
		if(x==1){
			dist_code = $("#cmb_dist").val();
		}else{
			dist_code =$("#cmb_district_filter").val();
		}
		$.ajax({
			url: base_url+"service/get_block_dropdown",
			type:"POST",
			data:{op_type:'get_block_name',dist_code : dist_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(x==res1[i].block_code){
						selected='selected';
					}else{
						selected='';
					}
					options = options + "<option value='"+res1[i].block_code+"' "+selected+">"+res1[i].block_name+"</option>";
				}
				if(x == 1){
					$('#cmb_block').html("");
					$('#cmb_block').append(options);
				}else{
					$('#cmb_block_filter').html("");
					$('#cmb_block_filter').append(options);
				}
				
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	$("#cmb_block").change(function(){
    	get_gp_dropdown(1);
	});
	$("#cmb_block_filter").change(function(){
    	get_gp_dropdown(2);
	});
	function get_gp_dropdown(a){
		if(a== 1){
			block_code = $("#cmb_block").val();
		}else{
			block_code = $("#cmb_block_filter").val();
		}
		$.ajax({
			url: base_url+"service/get_gp_dropdown",
			type:"POST",
			data:{op_type:'get_gp_name',block_code : block_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(a==res1[i].pk_gp_code){
						selected='selected';
					}else{
						selected='';
					}
					options = options + "<option value='"+res1[i].pk_gp_code+"' "+selected+">"+res1[i].gp_name+"</option>";
				}
				if(a == 1){
					$('#cmb_gp').html("");
					$('#cmb_gp').append(options);
				}else{
					$('#cmb_gp_filter').html("");
					$('#cmb_gp_filter').append(options);
				}
				
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	
	// on click of add/update button it will validate then submit 	
	$('#form_village').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_village"));
			urls =base_url+"service/operation_villagedata";
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
		                    sweetAlert("Village Data",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("Village Data",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		dtblVillage = $('#dtblVillage').DataTable();
							dtblVillage.draw();
							dtblVillage.clear();
							$('#manage_village_modal').modal('hide');
							$('#gp_code').prop('readonly', false);
							$('#form_village').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanVillage").html("Add Village");
							$("#form_village input[name='op_type']").val("add_village_master");
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
	    fields:{
	        txtCensusCode: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                },
	                stringLength: {
                        min: 6,
                        max: 8,
                        message: 'The Census Code Length has to be 6 to 8 Character'
                    }
	            },
	            regexp: {
	                regexp: /^[^*+|\":<>[\]{}`\\!.#%';@&$]+$/,
	                message: 'Special Character Not Allowed'
		        }
	        },
	        txtVillageName: {							
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
	        cmb_state: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_dist: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_block: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_gp: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        village_status: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function editVillageData(event){
		$("#txtCensusCode").attr("readonly", true);
	    $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Update");
		$("#spanVillage").html("Edit Village");
		$('#form_village').data('bootstrapValidator').resetForm(true);
		$("#form_village input[name='op_type']").val("edit_village_master");
	    $('#manage_village_modal').modal({backdrop: 'static',keyboard: false});
	    var oTable = $('#dtblVillage').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	    $('#txtCensusCode').val(oTable.fnGetData(row)['pk_village_code']);
	    $('#txtVillageName').val(oTable.fnGetData(row)['village_name']);
	    get_state_dropdown(oTable.fnGetData(row)['state_code']);
	    get_dist_dropdown(oTable.fnGetData(row)['district_code'],oTable.fnGetData(row)['state_code']);
	    get_block_dropdown(oTable.fnGetData(row)['block_code'],oTable.fnGetData(row)['district_code']);
	    get_gp_dropdown(oTable.fnGetData(row)['gp_code'],oTable.fnGetData(row)['block_code']);
	    $('#village_status').val(oTable.fnGetData(row)['status']); 
	}
	function deleteVillageData(event){//delete user
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
			 	var oTable = $('#dtblVillage').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_village";
			    var formData = {village_code:oTable.fnGetData(row)['pk_village_code'],op_type:"delete_village"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("Village",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,"success");
			                	dtblVillage = $('#dtblVillage').DataTable();
								dtblVillage.draw();
								dtblVillage.clear();
								$('#form_village').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spanVillage").html("Add Village");
								$("#form_village input[name='op_type']").val("add_village_master");
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
		$("#txtCensusCode").attr("readonly", false);
		$('#form_village').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#spanVillage").html("Add Village");
		$("#form_village input[name='op_type']").val("add_village_master");
	}
	/*Upload Village Data*/
	
	function uploadVillagedata(){
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
		        var urls = base_url + "service/import_villagedata";
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
							            window.location.href = 'village-master';
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