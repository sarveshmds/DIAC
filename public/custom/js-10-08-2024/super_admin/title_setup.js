	/*
	 * Author: Debashish Jyotish
	 * Date: 24/01/2018
	 * Description : This is used for user creation (manage_user.php).
	 * 
	 **/
	var urls =base_url+"service/get_title_setup/get_title";
	var user_table = $('#dtbl_title_setup').dataTable({
		"processing": false, //Feature control the processing indicator.
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
	    		data.csrf_title_token = $('#csrf_title_token').val();
	    	}
		},
		"sDom":"<'row'<'col-xs-4 btn_title_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
		"columns": [
			{"sName": "sl_no","sClass":"alignCenter"},
			{"sName": "title_name"},
			{"sName": "title_desc","sClass":"alignCenter"},
			{"sName": "title_image","sClass":"alignCenter","mRender": function( data, type, full ) {
	       		return "<img src='"+base_url+data+"' width='30'>";
	       	}},
			{ "sName": "status","sWidth": "5%","sClass" : "alignCenter",
	            "mRender": function( data, type, full ) 
	            {
	                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
	            }  
	        },
			{
				"sName": "button",data:null,"sWidth": "15%","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editTitleData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>"
	       	}
		],
		//"columnDefs": [{"targets": [ 5,7 ],"orderable": false}], 
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
	$("div.btn_title_modal").html('<button class="btn btn-info  btn-circle tooltipTable" title="Add" id="btn_titel_setup"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	
	$("#btn_titel_setup").click(function(){
		$("#span_title")[0].innerHTML ="Add Title Setup";
		$('#frm_title_setup').data('bootstrapValidator').resetForm(true); //to reset the form
	    $('#title_setup_modal').modal('show');
	});
	$('#frm_title_setup').bootstrapValidator({
			message: 'This value is not valid',
		    feedbackIcons: 
		    {
		        valid: 'glyphicon glyphicon-ok',
		        invalid: 'glyphicon glyphicon-remove',
		        validating: 'glyphicon glyphicon-refresh'
		    },
			submitButtons: 'button[type="submit"]',
			
			submitHandler: function(validator, form, submitButton) {
				$("#btn_submit").html('<i class="fa fa-gear fa-spin"></i> Loading...');
				var formData = new FormData(document.getElementById("frm_title_setup"));
				urls =base_url+"service/operation_titlesetup";
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
			                	$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
			                    sweetAlert("USER",obj.msg, "error");
			                }else if(obj.status === 'validationerror'){
			                	$('#errorlog').html(obj.msg);
			                	$('#errorlog').show();
			                	$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
			                } else {
			                	sweetAlert("USER",obj.msg, "success");
			                	$('#errorlog').html('');
			                	$('#errorlog').hide();
			            		title_setup = $('#dtbl_title_setup').DataTable();
								title_setup.draw();
								title_setup.clear();
								$('#frm_title_setup').data('bootstrapValidator').resetForm(true);//Reseting user form
								$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
								$("#span_title").html("Add Title Setup");
								$('#title_code').val('');
								$("#frm_title_setup input[name='op_title_type']").val("add_title_setup");
								$('#title_setup_modal').modal('hide'); 
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
		        txt_title_name: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                }
		            }
		        },
		        title_status: {							//form input type name
		            validators: {
		               	notEmpty: {
		                    message: 'Required'
		                }
		            }
		        },
		        txt_title_img: {
		            validators: {
		                file: {
		                    extension: 'png',
		                    type: 'image/png',
		                    maxSize: 2048 * 1024,
		                    message: 'The selected file is not valid'
		                }
		            }
		        }
			}	
		});
	
	function editTitleData(event){//on edit click assign the value to text field
		$('#title_setup_modal').modal('show');
		$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
		$("#span_title")[0].innerHTML ="Edit Title Setup";
	    $("#frm_title_setup input[name='op_title_type']").val("edit_title_setup");
	    var oTable = $('#dtbl_title_setup').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
	    var title_id = oTable.fnGetData(row)['title_id'];
	    var title_name = oTable.fnGetData(row)['title_name'];
	    var title_desc = oTable.fnGetData(row)['title_desc'];
	    var status = oTable.fnGetData(row)['status'];
	    $('#title_code').val(title_id);
	    $('#txt_title_name').val(title_name);
	    $('#txt_desc').val(title_desc);
	    $('#title_status').val(status); 
	}
	
	function form_reset(){
		$('#title_code').val('');
		$('#frm_title_setup').data('bootstrapValidator').resetForm(true); //to reset the form
		$("#btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
		$("#span_title").html("Add Title Setup");
		$("#frm_title_setup input[name='op_title_type']").val("add_title_setup");
		$("#txt_desc").val('');
	}