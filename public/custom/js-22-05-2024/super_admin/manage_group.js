/*
 * Author: Rahul Patro
 * Date: 08/09/2017
 * Description : This is used for group creation (manage_group.php).
 * 
 **/
$(document).ready(function(){
	$('#cmbtablevalue').multiselect({
        enableFiltering: true,
        includeSelectAllOption:true,
		enableCaseInsensitiveFiltering:true,
		numberDisplayed: 1,
		buttonWidth:"200px",
		minHeight:100,
		nonSelectedText : '-- ALL --',
		maxHeight:200
	});
	
	//after clicking of tab page will be reload-this is used because after creating a role that role is not showing in menu master with out page reload
	$('#GroupTab').click(function() {
	    dtblgroupmaster = $('#dtblgroupmaster').DataTable();
		dtblgroupmaster.draw();
		dtblgroupmaster.clear();
	}); 
	$('#RoleGroupTab').click(function() {
	    dtblrolegroupmapping = $('#dtblrolegroupmapping').DataTable();
		dtblrolegroupmapping.draw();
		dtblrolegroupmapping.clear();
		dropdownGroup();
	});
	$('#UserRolegroupTab').click(function() {
		dropdownRoleGroup();
	    dtblUser_Role_group_mapping = $('#dtblUser_Role_group_mapping').DataTable();
		dtblUser_Role_group_mapping.draw();
		dtblUser_Role_group_mapping.clear();
		
	});

	/*$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

	    localStorage.setItem('activeTab', $(e.target).attr('href'));
	    

	});

	var activeTab = localStorage.getItem('activeTab');

	if(activeTab){
		
	    $('#myTab a[href="' + activeTab + '"]').tab('show');

	}*/
	
	
	var urls =base_url+"service/get_group_setup/get_groupdata";
	var dtblgroupmaster = $('#dtblgroupmaster').dataTable({
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
		"ajax":
		{
			"url": urls,
			"type": "POST",
			"data": function (data){
		    	data.csrf_group_setup_token = $('#csrf_group_setup_token').val();
		    }
		},
		"sDom":"<'row'<'col-xs-4 btn_gpsetup_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
		"columns": [
			{"sName": "sl_no","sWidth": "5%","sClass":"alignCenter"},
			{"sName": "group_code","bVisible":false},
			{"sName": "group_name","sWidth": "15%"},
			{"sName": "table_name","sWidth": "15%"},
			{"sName": "table_code","bVisible":false,"sWidth": "35%"},
			{"sName": "oper_table","sWidth": "15%"},
			{"sName": "oper_column","sWidth": "15%"},
			{"sName": "excu_column","sWidth": "15%"},
			{"sName": "button",data:null,"sClass":"alignCenter","sWidth": "20%","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable' align='center' onclick='editGroupData(event);' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>\
				<button type='button' class='btn btn-danger btn-circle tooltipTable' align='center' onclick='deleteGroupData(event);' title='Delete' ><i class='fa fa-trash-o'></i></button>\
				<button type='button' class='btn btn-success btn-circle tooltipTable' align='center' onclick='Mapgroupdata(event);' title='Map' ><i class='fa fa-sitemap'></i></button>"
	       	}
		],
		"columnDefs": [{"targets": [ 4 ],"orderable": false}], 
		// to show tooltips in datatable
		"fnDrawCallback": function(oSettings, json) 
		{
     		$('.tooltipTable').tooltipster({
	         	theme: 'tooltipster-punk',
	      		animation: 'grow',
	        	delay: 200, 
	         	touchDevices: false,
	         	trigger: 'hover'
      		} );          
  		}
	});
	$("div.btn_gpsetup_modal").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" id="add_groupsetup"><i class="fa fa-plus" aria-hidden="true"></i></button>');
		
    //for form reset button click to reset the form
	
	$('#btn_groupreset').on('click', function(){
	   	//alert(1);
		$('#cmbtablevalue').multiselect("refresh");
		//$('#cmbtablevalue').removeAttr('checked',true);
		$('#frm_group').data('bootstrapValidator').resetForm(true); //to reset the form
		$('#cmbtable').attr('readonly', false);
		//$('#cmbtablevalue').multiselect('deselectAll', true);	
		
		$("#btn_groupsubmit").html("<i class='fa fa-paper-plane'></i> Add");
		
		$("#spangroup").html("Add Group");
		$("#frm_group input[name='op_type_group']").val("add_group");
	});
	$('#cmbtable').change(function(){
		var cmbtable = $('#cmbtable').val();
		var group_code = $('#hidgroupcode').val();
		
		$.ajax({
			url:base_url+"service/get_tablevalue",
			type:"POST",
			data:{
				table_name:cmbtable,
				group_code:group_code
			},
			  async:false,
			  success:function(response)
			  {
				   // alert( response);
				   var options = "";
				   options = "";   
				   var res1 = JSON.parse(response); 
				   //alert('ok');  
				   $.each(res1.optiondata,function(i,data){
				    options = options + "<option value='"+data.code+"' >"+data.name+"</option>";
				   });     
				   $('#cmbtablevalue').html("");   //classname from studentmanage
				   $('#cmbtablevalue').append(options);
				   $('#cmbtablevalue').multiselect('rebuild');
			   },
			  error:function()
			  {
			   toastr.error('Unable to process please contact support');
			  }
 		});
		
		
		
	});
	
	$("#add_groupsetup").click(function(){
		$("#spangroup")[0].innerHTML ="Add Group";
		$('#frm_group').data('bootstrapValidator').resetForm(true); //to reset the form
	    $('#group_setup_modal').modal('show');
	});	
	// on click of add/update button it will validate then submit 	
	$('#frm_group').bootstrapValidator({
		message: 'This value is not valid',
	    feedbackIcons: 
	    {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
		submitButtons: 'button[type="submit"]',
		
		submitHandler: function(validator, form, submitButton) 
		{
			var formData = new FormData(document.getElementById("frm_group"));
			urls =base_url+"service/operation_groupdata";
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
		                    sweetAlert("USER",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                } else {
		                	sweetAlert("USER",obj.msg, "success");
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		            		dtblgroupmaster = $('#dtblgroupmaster').DataTable();
							dtblgroupmaster.draw();
							dtblgroupmaster.clear();
							$('#cmbtable').attr('readonly', false);
							$('#frm_group').data('bootstrapValidator').resetForm(true);//Reseting user form
							$("#btn_groupsubmit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spangroup").html("Add Group");
							$("#frm_group input[name='op_type_group']").val("add_group");
							$('#group_setup_modal').modal('hide');
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
	        txtGroupName: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmbtable: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtoperTable: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtoperColumn: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtExColumn: {							//form input type name
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        'cmbtablevalue[]': {
             validators: {
                 notEmpty: {
       				message: 'Subject must be  required and can\'t be empty',
      
                     },
                     callback: function(value, validator, $field) {
                         // Get the selected options
                         message: 'Please choose atleast 1 Discipline'
                         var options = validator.getFieldElements('cmbtablevalue[]').val();
                         return (options != null
                                 && options.length >= 1);
                     
                 }
             
             }
         },
		}	
	});	
	/*
	* Author:  Lina Mohapatra
	* Date: 13/09/2017
	* Description : This pice of code is for creating updating and deleting a role_group mapping.
	* Tab :role_group
	* 
 	**/
	
   	
	var url =base_url+"service/get_rolegroupmapping_data/get_role_group_data";
	var dtblrolegroupmapping = $('#dtblrolegroupmapping').dataTable({
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
		    "data": function (data){
		    	data.csrf_role_group_mapping_token = $('#csrf_role_group_mapping_token').val();
		    }
		},
		"sDom":"<'row'<'col-xs-4 btn_rlgp_modal'><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-9' <'row'<'col-xs-5' i>>><'col-xs-3'p>>",
        "columns": [
           { "sName": "sl_no","sClass":"alignCenter"},
           { "sName": "name" },
           { "sName": "role_name"},
           { "sName": "group_name"},
           { "sName": "role_code" },
           { "sName": "group_code" },
           { "sName": "role_group_code" },
           { "sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-info btn-circle tooltipTable'  onclick='updateRoleGroup(event);' id='edit' title='Edit' ><i class='fa fa-pencil-square-o'></i></button>&nbsp;\
           		<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteRoleGroup(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"
	       },
        ],
        "aoColumnDefs": [{ "bVisible": false, "aTargets": [ 4,5,6 ] }],
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
	$("div.btn_rlgp_modal").html('<button class="btn btn-info btn-circle tooltipTable" title="Add" id="role_group_mapping"><i class="fa fa-plus" aria-hidden="true"></i></button>');
	
		
	/*
	* bootstrap validation for the  role-group form 
	*/	
	
	$("#role_group_mapping").click(function(){
		$("#span_addrolegroup")[0].innerHTML ="Add Role-Group";
		$('#frm_role_group').data('bootstrapValidator').resetForm(true); //to reset the form
	    $('#role_group_mapping_modal').modal('show');
	});		
	$('#frm_role_group').bootstrapValidator({
		excluded: [':disabled'],
		message: 'This value is not valid',
		feedbackIcons: 
        {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("frm_role_group"));
			$.ajax({
		        type: "POST",
		        url: base_url + "service/operation_role_group_data",
				data:formData,
				cache: false,
		        contentType: false,
		        processData: false,
		        success: function(response){
					try {
		                var obj = JSON.parse(response);
		                if (obj.status == false) {
		                	$('#errorlogrolegroup').html('');
		                	$('#errorlogrolegroup').hide();
		                    sweetAlert("Role-Group",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlogrolegroup').html(obj.msg);
		                	$('#errorlogrolegroup').show();
		                } else {
		                	sweetAlert("Role-Group",obj.msg, "success");
		                	$('#errorlogrolegroup').html('');
		                	$('#errorlogrolegroup').hide();
		            		dtblrolegroupmapping = $('#dtblrolegroupmapping').DataTable();
							dtblrolegroupmapping.draw();
							dtblrolegroupmapping.clear();
							$('#frm_role_group').data('bootstrapValidator').resetForm(true);
							$("#role_group_btn").html("<i class='fa fa-paper-plane'></i> Add");
							$("#span_addrolegroup").html("Add Role-Group");
							$("#frm_role_group input[name='op_type_role_group']").val("add_role_group");
							 $('#role_group_mapping_modal').modal('hide');
		                }
		            } catch (e) {
		                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
		            }
	            },
	            error: function(){
		           alert("unable to save")
		        }
		    });
		},
		fields:{
			cmbrolecode: {
				validators: {
					notEmpty: {
						message: 'This field can\'t left blank'
					}
				}
			},
			cmbgroupcode: {
				validators: {
					notEmpty: {
						message: 'This field can\'t left blank'
					}
				}
			},
			txtRoleGroup: {
				validators: {
					notEmpty: {
						message: 'This field can\'t left blank'
					}
				}
			},
			
		}
	});
	
	/*
	* Reset button click 
	*/
	
	$("#role_group_reset").click(function()
    {  	
    	$("#role_group_btn").html("<i class='fa fa-paper-plane'></i> Add");
		$("#span_addrolegroup").html("Add User");
		$("#frm_role_group input[name='op_type_role_group']").val("add_role_group");
		$('#cmbtablevalue').multiselect('refresh');
	    $('#frm_role_group').data('bootstrapValidator').resetForm(true);
	   
    	//$('#cmbtablevalue').multiselect('refresh');
 	});
 	
	 	
	 	
	/*
	* This pice of code is for creating updating and deleting a role-group  for the user access control (ends)
	*/ 
	
	/*
	* This pice of code is for creating updating and deleting a user role-group mapping  for the user access control (bigins)
	*/ 
	
	
	var url =base_url+"service/get_user_rolegroup_mapping_data/get_user_role_group_data";
	var dtblUser_Role_group_mapping = $('#dtblUser_Role_group_mapping').dataTable({
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
		    "data": function(d) {
		        d.cmbrolegroup = $('#cmbrolegroup').val();
		        d.csrf_userrole_group_mapping_token = $('#csrf_userrole_group_mapping_token').val();
		    }
		},
        "columns": [
           { "sName": "sl_no","sClass":"alignCenter"},
           { "sName": "user_code"},
           { "sName": "role_group_code"},
           { "sName": "user_name" },
           { "sName": "name" },
           { "sName": "user_rolegroup_code" },
           { "sName": "button","sClass":"alignCenter","sDefaultContent":"<button type='button' class='btn btn-danger btn-circle tooltipTable' onclick='deleteUserRoleGroup(event);'  id='delete' title='Delete'><i class='fa fa-trash'></i></button>"
	       },
        ],
        "aoColumnDefs": [{ "bVisible": false, "aTargets": [ 1,2,5 ] }],
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
	//on page load assign the value
	$("#frm_userrolegroup input[name='txtrole_group']").val($('#cmbrolegroup').val());
	$("#frm_userrolegroup input[name='txtrole_name']").val($("#cmbrolegroup option:selected").html());
	$('#cmbrolegroup').change(function(){
		dtblUser_Role_group_mapping = $('#dtblUser_Role_group_mapping').DataTable();
		dtblUser_Role_group_mapping.draw();
		dtblUser_Role_group_mapping.clear();
		$("#frm_userrolegroup input[name='txtrole_group']").val($('#cmbrolegroup').val());
		$("#frm_userrolegroup input[name='txtrole_name']").val($("#cmbrolegroup option:selected").html());
		
		$.ajax({
			url:base_url+"service/get_mapping_dept",
			type:"POST",
			data:{role_group_code:$(this).val()},
			async:false,
			success:function(response){
				var options = "";
				var res1 = JSON.parse(response); 
				 	options = options + "<option value=''>select</option>";
				$.each(res1.res_data,function(i,data){
				   
				    options = options + "<option value='"+data.res_code+"'>"+data.res_name+"</option>";
				});     
				$('#cmb_department').html(""); 
				$('#cmb_department').append(options);
			},error:function(){
			   toastr.error('Unable to process please contact support');
			}
 		});
	});
	
	$('#cmbuser_name').multiselect({
        enableFiltering: true,
        includeSelectAllOption:true,
		enableCaseInsensitiveFiltering:true,
		numberDisplayed: 1,
		buttonWidth:"200px",
		minHeight:100,
		nonSelectedText : '-- ALL --',
		maxHeight:200,
		
	});
	
	$('#cmbuser_name').on('change', function(){
	    var selected = $(this).find("option:selected");
	    var arrSelected = [];
	    selected.each(function(){
	       arrSelected.push($(this).val());
	    });
	});
	
	
	
	
	/*
	* bootstrap validation for the user- role-group form 
	*/	
	
		
	$('#frm_userrolegroup').bootstrapValidator({
		excluded: [':disabled'],
		message: 'This value is not valid',
		feedbackIcons: 
        {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton){
			var formData = new FormData(document.getElementById("frm_userrolegroup"));
			$.ajax({
		        type: "POST",
		        url: base_url + "service/operation_user_rolegroup_data",
				data:formData,
				cache: false,
		        contentType: false,
		        processData: false,
		        success: function(response){
					try {
		                var obj = JSON.parse(response);
		                if (obj.status == false) {
		                	$('#errorloguser_rolegroup').html('');
		                	$('#errorloguser_rolegroup').hide();
		                    sweetAlert("Role-Group",obj.msg, "error");
		                }else if(obj.status === 'validationerror'){
		                	$('#errorloguser_rolegroup').html(obj.msg);
		                	$('#errorloguser_rolegroup').show();
		                } else {
		                	sweetAlert("Role-Group",obj.msg, "success");
		                	$('#errorloguser_rolegroup').html('');
		                	$('#errorloguser_rolegroup').hide();
		            		dtblUser_Role_group_mapping = $('#dtblUser_Role_group_mapping').DataTable();
							dtblUser_Role_group_mapping.draw();
							dtblUser_Role_group_mapping.clear();
							$('#frm_userrolegroup').data('bootstrapValidator').resetForm(true);
							$("#btnuser_rolegroup").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spanuser_rolegroup").html("Add User Role-Group");
							$("#frm_userrolegroup input[name='op_type_user_role_group']").val("add_user_role_group");
		                }
		            } catch (e) {
		                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
		            }
	            },
	            error: function(){
		           alert("unable to save")
		        }
		    });
		},
		fields:{
			txtrole_group: {
				validators: {
					notEmpty: {
						message: 'This field can\'t left blank'
					}
				}
			},
			cmbuser_name: {
				validators: {
					notEmpty: {
						message: 'This field can\'t left blank'
					}
				}
			}
		}
	});
	
});

function dropdownGroup(){
	$.ajax({
		url:base_url+"service/get_dropdown_data",
		type:"POST",
		data:{
			op_type:'get_group',
		},
		async:false,
		success:function(response)
		{
		   // alert( response);
		   	var options = "<option value=''>Select Group</option>";   
		   	var res1 = JSON.parse(response); 
		   	for(var i=0;i<res1.length;i++){
		    	options = options + "<option value='"+res1[i].group_code+"' >"+res1[i].group_name+"</option>";
		   	}    
		   	$('#cmbgroupcode').html("");   //classname from studentmanage
		   	$('#cmbgroupcode').append(options);
		},
		error:function()
		{
		toastr.error('Unable to process please contact support');
		}
	});
}
function dropdownRoleGroup(){
	$.ajax({
		url:base_url+"service/get_dropdown_data",
		type:"POST",
		data:{
			op_type:'get_rolegroup_code',
		},
		async:false,
		success:function(response)
		{
		   // alert( response);
		   	var options = "";   
		   	var res1 = JSON.parse(response); 
		   	for(var i=0;i<res1.length;i++){
		    	options = options + "<option value='"+res1[i].role_group_code+"' >"+res1[i].name+"</option>";
		   	}    
		   	$('#cmbrolegroup').html("");   //classname from studentmanage
		   	$('#cmbrolegroup').append(options);
		},
		error:function()
		{
		toastr.error('Unable to process please contact support');
		}
	});
}
function editGroupData(event){
	$('#group_setup_modal').modal('show');
	$("#btn_groupsubmit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
	$("#spangroup")[0].innerHTML ="Edit Group";
    $("#frm_group input[name='op_type_group']").val("edit_group");
    var oTable = $('#dtblgroupmaster').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
    var group_name = oTable.fnGetData(row)['group_name'];
    var group_code = oTable.fnGetData(row)['group_code'];
    var table_code = oTable.fnGetData(row)['table_code'];
    var operation_tbl = oTable.fnGetData(row)['operation_tbl'];
    var operation_col = oTable.fnGetData(row)['operation_col'];
    var exicution_col = oTable.fnGetData(row)['exicution_col'];
    $('#cmbtable').val(table_code);
    $('#hidgroupcode').val(group_code);
    $('#txtGroupName').val(group_name);
    $('#txtoperTable').val(operation_tbl);
    $('#txtoperColumn').val(operation_col);
    $('#txtExColumn').val(exicution_col);
    $('#cmbtable').trigger("change");
    $('#cmbtable').attr('readonly', true);
    $('#dtblgroupmapping').show();
    /*var urls =base_url+"service/get_editgroup_data/get_tablemapvalue";
	var dtblgroupmapping = $('#dtblgroupmapping').dataTable({
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
		"ajax":
		{
			"url": urls,
			"type": "POST",
			"data":function(data){
				data.group_code = group_code
			}
		},
		//Set column definition initialisation properties.
		"columns": [
			{"sName": "sl_no","sWidth": "5%","sClass":"alignCenter"},
			{"sName": "id","bVisible":false,"sClass":"alignCenter"},
			{"sName": "group_name","sWidth": "35%"},
			{"sName": "map_val","sWidth": "35%"},
			{"sName": "button",data:null,"sWidth": "25%","sDefaultContent":"<button type='button' class='btn btn-danger btn-circle tooltipTable' align='center' onclick='deleteGroupMapData(event);' title='Delete' ><i class='fa fa-trash-o'></i></button>"
	       	}
		],
		"columnDefs": [{"targets": [ 4 ],"orderable": false}], 
		// to show tooltips in datatable
		"fnDrawCallback": function(oSettings, json) 
		{
     		$('.tooltipTable').tooltipster({
	         	theme: 'tooltipster-punk',
	      		animation: 'grow',
	        	delay: 200, 
	         	touchDevices: false,
	         	trigger: 'hover'
      		} );          
	  	}
	});*/
	
}

function deleteGroupData(event){//delete user
	swal({
		title: "Are you sure?",
		text: "You want to Delete the Group!",
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
		 	var oTable = $('#dtblgroupmaster').dataTable();
	        var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
	        var urls = base_url + "service/delete_data";
		    var formData = {
		    				group_code:oTable.fnGetData(row)['group_code'],
		    				op_type:"delete_group"};
			$.ajax({
				url: urls,
		        method: 'POST',
		        data: formData,
		        success: function (response) {
		            try {
		                var obj = JSON.parse(response);
		                if (!obj.status) {
		                    sweetAlert("Group",obj.msg, "error");
		                } else {
		                	swal('Deleted!',obj.msg,'success');
		                	dtblgroupmaster = $('#dtblgroupmaster').DataTable();
							dtblgroupmaster.draw();
							dtblgroupmaster.clear();
							$('#dtblgroupmapping').hide();
							//$('#frm_group').data('bootstrapValidator').resetForm(true); //to reset the form
							$('#cmbtable').attr('readonly', false);
							$("#btn_groupsubmit").html("<i class='fa fa-paper-plane'></i> Add");
							$("#spangroup").html("Add Group");
							$("#frm_group input[name='op_type_group']").val("add_group");
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
function Mapgroupdata(event){
	$('#mapdata').modal('show');
	var oTable = $('#dtblgroupmaster').dataTable();
	var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
    var group_code = oTable.fnGetData(row)['group_code'];
    var urls =base_url+"service/get_mapgroupdata_data/get_tablemapvalue";
	var dtblgroupmapping = $('#dtblgroupmapping').dataTable({
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"bDestroy": true,
		"paging":   true,
		"info":     true,
		"autoWidth": false,
		"scrollX":false,
		"responsive":false,
		"searching":true,
		// Load data for the table's content from an ajax source
		"ajax":
		{
			"url": urls,
			"type": "POST",
			"data":function(data){
				data.group_code = group_code
				data.csrf_group_mapping_token = $('#csrf_group_mapping_token').val();
			}
		},
		//Set column definition initialisation properties.
		"columns": [
			{"sName": "sl_no","sClass":"alignCenter"},
			{"sName": "group_name","sClass":"alignCenter"},
			{"sName": "map_val","sClass":"alignCenter"},
			{"sName": "map_val","bVisible":false,"sClass":"alignCenter"},
			{"sName": "button",data:null,"sDefaultContent":"<button type='button' class='btn btn-danger btn-circle tooltipTable' align='center' onclick='deleteGroupMapData(event);' title='Delete' ><i class='fa fa-trash-o'></i></button>"
	       	}
		],
		"columnDefs": [{"targets": [ 3 ],"orderable": false}], 
		// to show tooltips in datatable
		"fnDrawCallback": function(oSettings, json) 
		{
     		$('.tooltipTable').tooltipster({
	         	theme: 'tooltipster-punk',
	      		animation: 'grow',
	        	delay: 200, 
	         	touchDevices: false,
	         	trigger: 'hover'
      		});          
	  
	  	}
	});
	//datatable to show header properly after adding scrollX 
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	    $($.fn.dataTable.tables(true)).DataTable()
	       .columns.adjust()
	       .responsive.recalc();
	});
}
function deleteGroupMapData(event){
 	var oTable = $('#dtblgroupmapping').dataTable();
    var row;
    if(event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
    var urls = base_url + "service/delete_data";
    var formData = {
    				id:oTable.fnGetData(row)['id'],
    				op_type:"delete_groupmapdata"};
	$.ajax({
		url: urls,
        method: 'POST',
        data: formData,
        success: function (response) {
            try {
                var obj = JSON.parse(response);
                if (!obj.status) {
                    sweetAlert("Group",obj.msg, "error");
                } else {
                	dtblgroupmaster = $('#dtblgroupmapping').DataTable();
					dtblgroupmaster.draw();
					dtblgroupmaster.clear();
                }
            } catch (e) {
                sweetAlert("Sorry","We are unable to Process !", "error");
            }
        }, error: function (err) {
            toastr.error(err);
        }
	});
		
}

/*
* edit in role_group mapping table 
*/	
	
function updateRoleGroup(event)
{
	 $('#role_group_mapping_modal').modal('show');
	$('#role_group_btn')[0].innerHTML = '<i class="fa fa-edit"></i> Update';
	$('#span_addrolegroup')[0].innerHTML = 'Edit Role-Group';
	$('#op_type_role_group').val('edit_role_group');
	
	var oTable = $('#dtblrolegroupmapping').dataTable();
	var row;
	if(event.target.tagName == "BUTTON")
	   row = event.target.parentNode.parentNode;
	else if(event.target.tagName == "I")
	   row = event.target.parentNode.parentNode.parentNode;
	$('#cmbrolecode').val(oTable.fnGetData(row)['role_code']);
	$('#hidtxtrolecode').val(oTable.fnGetData(row)['role_code']);
	$('#cmbgroupcode').val( oTable.fnGetData(row)['group_code']);
	$('#hidtxtgroupcode').val( oTable.fnGetData(row)['group_code']);
	$('#hidtxtRole_group_code').val( oTable.fnGetData(row)['role_group_code']);
	$('#txtRoleGroup').val( oTable.fnGetData(row)['name']);
}

/*
* Delete in User_role_group table 
*/	
	
function deleteUserRoleGroup(event)
{   
	swal({
		title: "Are you sure?",
		text: "You want to Delete the User Role-Group !",
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
			var oTable = $('#dtblUser_Role_group_mapping').dataTable();
			var row;
			//alert(event.target.tagName);
			if(event.target.tagName == "BUTTON")
			   row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
			   row = event.target.parentNode.parentNode.parentNode;
			var formData = {
    				userrolegroupcode:oTable.fnGetData(row)['user_rolegroup_code'],
    				op_type:"delete_user_rolegroup"
    			};
			$.ajax({
		        type: "POST",
		        url: base_url + "service/delete_data", 
				data: formData,
		        success: function(response){
					 try {
		                var obj = JSON.parse(response);
		                if (!obj.status) {
		                    sweetAlert("Role-Group",obj.msg, "error");
		                } else {
		                	swal('Deleted!',obj.msg,'success');
		                	dtblUser_Role_group_mapping = $('#dtblUser_Role_group_mapping').DataTable();
							dtblUser_Role_group_mapping.draw();
							dtblUser_Role_group_mapping.clear();
		                }
		            } catch (e) {
		                sweetAlert("Sorry","We are unable to Process !", "error");
		            }
	            },
	            error: function(){
		           alert("Fail")
		        }
		    });
		}
    });
}
/*
* Delete in role_group table 
*/	
	
function deleteRoleGroup(event)
{   
	swal({
		title: "Are you sure?",
		text: "You want to Delete the Role-Group !",
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
			var oTable = $('#dtblrolegroupmapping').dataTable();
			var row;
			//alert(event.target.tagName);
			if(event.target.tagName == "BUTTON")
			   row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
			   row = event.target.parentNode.parentNode.parentNode;
			var formData = {
    				rolegroupcode:oTable.fnGetData(row)['role_group_code'],
    				op_type:"delete_rolegroup"
    			};
			$.ajax({
		        type: "POST",
		        url: base_url + "service/delete_data", 
				data: formData,
		        success: function(response){
					 try {
		                var obj = JSON.parse(response);
		                if (!obj.status) {
		                    sweetAlert("Role-Group",obj.msg, "error");
		                } else {
		                	swal('Deleted!',obj.msg,'success');
		                	dtblrolegroupmapping = $('#dtblrolegroupmapping').DataTable();
							dtblrolegroupmapping.draw();
							dtblrolegroupmapping.clear();
		                }
		            } catch (e) {
		                sweetAlert("Sorry","We are unable to Process !", "error");
		            }
	            },
	            error: function(){
		           alert("Fail")
		        }
		    });
		}
    });
}
