	//after clicking of tab   the respective  table and the respective dropdown will be rendred
	$('#tabDepartmentClick').click(function() {
	    var dtblDepartmentMaster = $('#dtblDepartmentMaster').DataTable();
		dtblDepartmentMaster.draw();
		dtblDepartmentMaster.clear();
	}); 
	$('#tabVendorClick').click(function() {
	    var dtblVendorMaster = $('#dtblVendorMaster').DataTable();
		dtblVendorMaster.draw();
		dtblVendorMaster.clear();
	});
	$('#tabTechnicianClick').click(function() {
	    var dtblTechnicianMaster = $('#dtblTechnicianMaster').DataTable();
		dtblTechnicianMaster.draw();// ReLoad the Datatable
		dtblTechnicianMaster.clear();
	});
	
	$(document).ready(function(){
		var dtblDepartmentMaster = $('#dtblDepartmentMaster').DataTable({
		 	"processing": true, //Feature control the processing indicator.
		    "serverSide": true, //Feature control DataTables' server-side processing mode.
		    "bDestroy": true,
		    "paging":   true,
		    "info":     true,
		    "autoWidth": false,
		    "responsive":true,
		   	"searching":true,
		   	"bLengthChange": true,
			"ajax": {
			    "url": base_url+"service/get_all_department_list",
			    "type": "POST",
			    "data": function(data) {
			       	data.hidDeptCsrfToken = $("#hidDeptCsrfToken").val();
			       	data.op_type = 'get_all_department_list';
			    }
			},
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "dept_name","sClass":"alignLeft"},
				{"sName": "admin_dept_code","sClass":"alignCenter"},
				{ "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{ "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		            	if(full.login_status == 0){
		            		return "<button type='button' class='btn btn-info  btn-circle tooltipTable' onclick='btnDeparmentCreate(event)' title='Create Deparment' id='btnDeparmentPassword'><i class='fa fa-gear'></i></button>";
						}else{
							return "<button type='button' class='btn btn-info  btn-circle tooltipTable'  title='Already Create Deparment' disabled><i class='fa fa-gear'></i></button>";
						}
		            }  
		        }
			], 
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
		
		
		var dtblVendorMaster = $('#dtblVendorMaster').DataTable({
		 	"processing": true, //Feature control the processing indicator.
		    "serverSide": true, //Feature control DataTables' server-side processing mode.
		    "bDestroy": true,
		    "paging":   true,
		    "info":     true,
		    "autoWidth": false,
		    "responsive":true,
		   	"searching":true,
		   	"bLengthChange": true,
			"ajax": {
			    "url": base_url+"service/get_all_vendor_list",
			    "type": "POST",
			    "data": function(data) {
			       	data.hidVendorCsrfToken = $("#hidVendorCsrfToken").val();
			       	data.op_type = 'get_all_vendor_list';
			    }
			},
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "supplier_code","sClass":"alignCenter"},
				{"sName": "supplier_name","sClass":"alignLeft"},
				{ "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
				{ "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		            	if(full.login_status == 0){
		            		return "<button type='button' class='btn btn-info  btn-circle tooltipTable' onclick='btnVendorCreate(event)' title='Create Vendor' id='btnVendorPassword'><i class='fa fa-gear'></i></button>";
						}else{
							return "<button type='button' class='btn btn-info  btn-circle tooltipTable'  title='Already Create Vendor' disabled><i class='fa fa-gear'></i></button>";
						}
		            }  
		        }
			], 
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
		
		var dtblTechnicianMaster = $('#dtblTechnicianMaster').DataTable({
		 	"processing": true, //Feature control the processing indicator.
		    "serverSide": true, //Feature control DataTables' server-side processing mode.
		    "bDestroy": true,
		    "paging":   true,
		    "info":     true,
		    "autoWidth": false,
		    "responsive":true,
		   	"searching":true,
		   	"bLengthChange": true,
			"ajax": {
			    "url": base_url+"service/get_all_technician_list",
			    "type": "POST",
			    "data": function(data) {
			       	data.hidTechCsrfToken = $("#hidTechCsrfToken").val();
			       	data.op_type = 'get_all_technician_list';
			    }
			},
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "name","sClass":"alignLeft"},
				{"sName": "phone_no","sClass":"alignCenter"},
				{ "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		                return '<img src="'+base_url+'public/custom/photos/'+data+'.png" />';
		            }  
		        },
		        { "sName": "login_status","sClass" : "alignCenter",
		            "mRender": function( data, type, full ){
		            	if(full.login_status == 0){
		            		return "<button type='button' class='btn btn-info  btn-circle tooltipTable' onclick='btnTechCreate(event)' title='Create Technician' id='btnDeptPassword'><i class='fa fa-gear'></i></button>";
						}else{
							return "<button type='button' class='btn btn-info  btn-circle tooltipTable'  title='Already Create Technician' disabled><i class='fa fa-gear'></i></button>";
						}
		            }  
		        }
			], 
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
	});
	
	
	function btnDeparmentCreate(event){
		var oTable = $('#dtblDepartmentMaster').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
		var pk_dept_code = oTable.fnGetData(row)['pk_dept_code'];
		var dept_user_code = oTable.fnGetData(row)['st_email'];
		var DeptSaltSHAPass = encryptShaPassCode(dept_user_code,'password');
		$('#btnDeparmentPassword').html('<i class="fa fa-gear fa-spin"></i>');
		$.ajax({
			url : base_url+"service/ApprovalUserCreate/"+DeptSaltSHAPass,
			type : 'POST',
			data:{op_type:'department_create',pk_dept_code:pk_dept_code},
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if(obj.status == true){
	                	$('#btnDeparmentPassword').html("<i class='fa fa-gear'></i>");
	                	var dtblDepartmentMaster = $('#dtblDepartmentMaster').DataTable();
						dtblDepartmentMaster.draw();// ReLoad the Datatable
						dtblDepartmentMaster.clear();
	                	sweetAlert("Department",obj.msg, "success");
					}else{
						toastr.warning(obj.msg);
					}
	            } catch (e) {
	                toastr.error("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	}
	function btnVendorCreate(event){
		var oTable = $('#dtblVendorMaster').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
		var supplier_code = oTable.fnGetData(row)['supplier_code'];
		var user_code =oTable.fnGetData(row)['user_code'];
		var VendorSaltSHAPass = encryptShaPassCode(user_code,'password');
		$('#btnVendorPassword').html('<i class="fa fa-gear fa-spin"></i>');
		$.ajax({
			url : base_url+"service/ApprovalUserCreate/"+VendorSaltSHAPass,
			type : 'POST',
			data:{op_type:'vendor_create',supplier_code:supplier_code},
			success : function(resultData){
				try {
	                var obj = JSON.parse(resultData);
	                if(obj.status == true){
	                	$('#btnVendorPassword').html("<i class='fa fa-gear'></i>");
	                	var dtblVendorMaster = $('#dtblVendorMaster').DataTable();
						dtblVendorMaster.draw();// ReLoad the Datatable
						dtblVendorMaster.clear();
	                	sweetAlert("Vendor",obj.msg, "success");
					}else{
						toastr.warning(obj.msg);
					}
	            } catch (e) {
	                toastr.error("Unable to Save.Please Try Again !");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	}
	
	function btnTechCreate(event){
		var oTable = $('#dtblTechnicianMaster').dataTable();
	    var row;
	    if(event.target.tagName == "BUTTON")
			row = event.target.parentNode.parentNode;
		else if(event.target.tagName == "I")
			row = event.target.parentNode.parentNode.parentNode;
		phone_no =oTable.fnGetData(row)['phone_no'];
		var TechSaltSHAPass = encryptShaPassCode(phone_no,'password');
		$('#btnDeptPassword').html('<i class="fa fa-gear fa-spin"></i>');
		$.ajax({
			url : base_url+"service/ApprovalUserCreate/"+TechSaltSHAPass,
			type : 'POST',
			data:{op_type:'technician_create',phone_no:phone_no},
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if(obj.status == true){
	                	$('#btnDeptPassword').html("<i class='fa fa-gear'></i>");
	                	var dtblTechnicianMaster = $('#dtblTechnicianMaster').DataTable();
						dtblTechnicianMaster.draw();// ReLoad the Datatable
						dtblTechnicianMaster.clear();
	                	sweetAlert("Technician",obj.msg, "success");
					}else{
						toastr.warning(obj.msg);
					}
	            } catch (e) {
	                toastr.error("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	}