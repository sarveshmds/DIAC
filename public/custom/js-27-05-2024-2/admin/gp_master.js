	$('#UploadGpTab').click(function() {
	   	window.location.href = 'gp-master';;
	}); 
	$('#UploadGpViewTab').click(function() {
	    var dtblGp = $('#dtblGp').DataTable();
		dtblGp.draw();
		dtblGp.clear();
	});
	$('#btn_search').click(function() {
	    var dtblGp = $('#dtblGp').DataTable();
		dtblGp.draw();
		dtblGp.clear();
	});
		/********Modify By: Subhashree Date : 29-08-2019************/
	$( document ).ready(function() {
    	cmb_country();
    	gp_master();
		$("div.btn_circle_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="form_reset()" data-toggle="modal" data-target="#manage_gp_modal"><i class="fa fa-plus" aria-hidden="true"></i></button>');
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	    	$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
		});
	});
	
	$("#btn_filter_reset").click(function(){
		$("#cmb_district_filter").val('');
		$("#cmb_block_filter").val('');
		dtblGp = $('#dtblGp').DataTable();
		dtblGp.draw();
		dtblGp.clear();
	});
	
	function gp_master(){
		var urls =base_url+"service/get_gp_master/gp_master";
		var dtblGp = $('#dtblGp').dataTable({
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
			    	data.csrf_Gp_token = $('#csrf_Gp_token').val();
			    	data.cmb_district_filter = $('#cmb_district_filter').val();
			    	data.cmb_block_filter = $('#cmb_block_filter').val();
			    }
			},
			"sDom":"<'row'<'col-xs-2 btn_circle_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			//Set column definition initialisation properties.
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "gp_name","sClass":"alignLeft"},
				{"sName": "gp_code","sClass":"alignLeft"},
				{"sName": "block_name","sClass":"alignLeft"},
				{"sName": "fk_district_census_code","sClass":"alignLeft"},
				{"sName": "district_name","sClass":"alignLeft"},
				{"sName": "state_name","sClass":"alignLeft"},
				{"sName": "country_name","sClass":"alignLeft"},
				{"sName": "status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ) 
		            {
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{
					"sName": "button",data:null,"sWidth": "15%","sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editGpData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
					/*<button type='button' class='btn btn-danger btn-circle tooltipTable tooltipstered' align='center' onclick='deleteGpData(event);' title='Delete' ><i class='fa fa-trash'></i></button>"*/
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
	}
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
		$('#district_census_code').val($(this).find(':selected').attr('data-attr'));
    	get_block_dropdown(1);
	});
	$("#cmb_district_filter").change(function(){
		
    	get_block_dropdown(2);
	});
	
	function get_block_dropdown(x){
		
		if(x==1){
			dist_code = $("#cmb_dist").val();
		}else{
			dist_code = $("#cmb_district_filter").val();
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
				if(x==1){
				$('#cmb_block').html("");
				$('#cmb_block').append(options);
				}
				else{
					$('#cmb_block_filter').html("");
				$('#cmb_block_filter').append(options);
				}
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	// on click of add/update button it will validate then submit 	
	$('#form_gp').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_gp"));
			urls =base_url+"service/operation_gpdata";
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
		                    sweetAlert("Gram Panchayat Data",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("Gram Panchayat Data",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		dtblGp = $('#dtblGp').DataTable();
							dtblGp.draw();
							dtblGp.clear();
							$('#manage_gp_modal').modal('hide');
							$('#gp_code').prop('readonly', false);
							$('#form_gp').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spangp").html("Add Gram Panchayat");
							$("#form_gp input[name='op_type']").val("add_gp_master");
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
	        gp_code: {							
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
	        gp_name: {							
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
	        cmb_dist: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        district_census_code: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        gp_status: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	function editGpData(event){
		$("#gp_code").attr("readonly", true);
	    $("#btn_submit").html("<i class='fa fa-paper-plane'></i> Update");
		$("#spangp").html("Edit Gram Panchayat");
		$('#form_gp').data('bootstrapValidator').resetForm(true); 
		$("#form_gp input[name='op_type']").val("edit_gp_master");
	    $('#manage_gp_modal').modal({backdrop: 'static',keyboard: false});
	    var oTable = $('#dtblGp').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	   	
	    $('#gp_code').val(oTable.fnGetData(row)['pk_gp_code']);
	    $('#gp_name').val(oTable.fnGetData(row)['gp_name']);
	    $('#cmb_country').val(oTable.fnGetData(row)['fk_country_code']);
	    $('#district_census_code').val(oTable.fnGetData(row)['fk_district_census_code']);
	    get_state_dropdown(oTable.fnGetData(row)['fk_state_code']);
	    get_dist_dropdown(oTable.fnGetData(row)['fk_district_code'],oTable.fnGetData(row)['fk_state_code']);
	    get_block_dropdown(oTable.fnGetData(row)['fk_block_code'],oTable.fnGetData(row)['fk_district_code']);
	    $('#gp_status').val(oTable.fnGetData(row)['status']); 
	}
	function deleteGpData(event){//delete user
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
			 	var oTable = $('#dtblGp').dataTable();
		        var row;
			    if(event.target.tagName == "BUTTON")
					row = event.target.parentNode.parentNode;
				else if(event.target.tagName == "I")
					row = event.target.parentNode.parentNode.parentNode;
		        var urls = base_url + "service/delete_gp";
			    var formData = {gp_code:oTable.fnGetData(row)['pk_gp_code'],op_type:"delete_gp"};
				$.ajax({
					url: urls,
			        method: 'POST',
			        data: formData,
			        success: function (response) {
			            try {
			                var obj = JSON.parse(response);
			                if (!obj.status) {
			                    sweetAlert("Gram Panchayat",obj.msg, "error");
			                } else {
			                	swal('Deleted!',obj.msg,"success");
			                	dtblGp = $('#dtblGp').DataTable();
								dtblGp.draw();
								dtblGp.clear();
								$('#form_gp').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#spangp").html("Add Gram Panchayat");
								$("#form_gp input[name='op_type']").val("add_gp_master");
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
		$("#gp_code").attr("readonly", false);
		$('#form_gp').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add Gram Panchayat");
		$("#spangp").html("Add Gram Panchayat");
		$("#form_gp input[name='op_type']").val("add_gp_master");
	}
	/*Upload Country Data*/
	
	function uploadGpdata(){
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
		        var urls = base_url + "service/import_gpdata";
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
							            window.location.href = 'gp-master';
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