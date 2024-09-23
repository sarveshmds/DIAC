 var urls =base_url+"service/get_imei_setup/get_imei_setup";
 var dataTableIMEI = $("#dataTableIMEI").DataTable({
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
	    	data.csrf_imeiToken = $('#csrf_imeiToken').val();
	    }
	},
	"sDom":"<'row'<'col-xs-2 btn_imei_modal'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
	//Set column definition initialisation properties.
	"columns": [
		{"sName": "sl_no","sWidth":"10%","sClass":"alignCenter"},
		{"sName": "user_name","sWidth":"40%","sClass":"alignLeft"},
		{"sName": "role","sWidth":"10%","sClass":"alignLeft"},
		{"sName": "imei_one","sWidth":"10%","sClass":"alignLeft"},
		{"sName": "imei_two","sWidth":"10%","sClass":"alignLeft"},
		{"sName": "record_status","sWidth":"10%","sClass" : "alignCenter",
            "mRender": function( data, type, full ) 
            {
                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
            }  
        },
		{
			"sName": "button",data:null,"sWidth": "10%","sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editIMeiData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
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
 $("div.btn_imei_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="form_reset()" data-toggle="modal" data-target="#add_imeiSetup_modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus" aria-hidden="true"></i></button>');
 
 	$('#form_imei_setup').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("form_imei_setup"));
			urls =base_url+"service/operation_imei_setup";
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
		                	$("#btn_submit_imei").html("<i class='fa fa-paper-plane'></i> Submit");
		                   	toastr.warning(obj.msg);
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                	$("#btn_submit_imei").html("<i class='fa fa-paper-plane'></i> Submit");
		                } else {
		                	dataTableIMEI = $('#dataTableIMEI').DataTable();
							dataTableIMEI.draw();
							dataTableIMEI.clear();
							$('#add_imeiSetup_modal').modal('hide');
		                	$("#btn_submit_imei").html("<i class='fa fa-paper-plane'></i> Submit");
							$('#form_imei_setup').data('bootstrapValidator').resetForm(true);//Reseting user form
							toastr.success(obj.msg);
		                }
		            } catch (e) {
		                toastr.error("Sorry",'Unable to Save.Please Try Again !', "error");
		            }
				},error: function(err){
					toastr.error("unable to save");
				}
			});
		},
		//live: 'enabled',
	    fields:
	    {
	        cmb_username: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txt_role: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txt_imeiOne: {
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
		}	
	});
	
	$("#cmb_role").on('change' ,function (){
		get_username_dropdown();
	});
	function get_username_dropdown(x=''){
		$.ajax({
			url: base_url+"service/get_username_details",
			type:"POST",
			data:{role:$("#cmb_role").val(),op_type:'get_user_role'},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(x == res1[i].user_code){
						selected='selected';
					}else{
						selected='';
					}
					options = options + "<option value='"+res1[i].user_code+"' "+selected+">"+res1[i].user_display_name+"("+res1[i].user_name+")"+"</option>";
				}
				$('#cmb_username').html("");  
				$('#cmb_username').append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	
	function editIMeiData(event){
		$("#rec_status").show();
	    $("#btn_submit_imei").html("<i class='fa fa-paper-plane'></i> Update");
		$("#spanIMEI").html("Edit IMEI Details");
		$('#form_imei_setup').data('bootstrapValidator').resetForm(true);
		$("#form_imei_setup input[name='op_type']").val("UPDATE_IMEI_SETUP");
	    $('#add_imeiSetup_modal').modal({backdrop: 'static',keyboard: false});
	    var oTable = $('#dataTableIMEI').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	    $('#cmb_role').prop('disabled', true);
	    $('#cmb_username').prop('disabled', true);
	    $('#hidden_userid').val(oTable.fnGetData(row)['user_id']);
	    $('#cmb_role').val(oTable.fnGetData(row)['role_code']); 
	    $('#cmb_recStatus').val(oTable.fnGetData(row)['rec_status']); 
	    get_username_dropdown(oTable.fnGetData(row)['user_code']);
	    $('#txt_imeiOne').val(oTable.fnGetData(row)['imei1']); 
	    $('#txt_imeiTwo').val(oTable.fnGetData(row)['imei2']); 
	}
	
	
	
	function form_reset(){
		$("#btn_submit_imei").html("<i class='fa fa-paper-plane'></i> Submit");
		$("#spanIMEI").html("Add IMEI Details");
		$('#cmb_role').val(); 
	    $('#cmb_username').val(); 
	    $('#txt_imeiOne').val(); 
	    $('#txt_imeiTwo').val(''); 
	    $("#rec_status").hide();
	    $('#cmb_role').prop('disabled', false);
	    $('#cmb_username').prop('disabled', false);
		$('#form_imei_setup').data('bootstrapValidator').resetForm(true);
	}