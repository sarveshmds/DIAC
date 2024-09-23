	var dtblSchemeWiseTrans = $('#dtblSchemeWiseTrans').dataTable();
		$(function(){
			$("#scheme_id").click(function(){
		 		$('#all_scheme_list').show();
		 		$('#dtabledashboardTwo').show();
		 		$('#total_scheme_head').show();
		 	
		 		$('#btn_download_comapny_pdf').hide();
		 		$('#btn_download_scheme_pdf').show();
		 		$('#btn_back_company').show();
		 	});
		 	$("#btn_back_schemeList").click(function(){
		 		$("#dtableCompanydashboardTwo").show();
		 		$(".hide_button").show();
		 		$('#all_scheme_list').show();
		 		$('#total_scheme_head').show();
		 		$('#display').show();
		 		$("#scheme_name").hide();
		 		$("#dtableVendordashboardTwo").hide();
		 		$(".show_button").hide();
		 	});
		 	
		 	$("#btn_back_company").click(function(){
		 		$("#dtabledashboardTwo").hide();
		 		$("#btn_download_scheme_pdf").hide();
		 		$("#btn_download_comapny_pdf").show();
		 		$('#btn_back_company').hide();
		 		$('#total_scheme_head').hide();
		 	});
		 	
		 	/*$('#datableVendorDetails').DataTable({
			    "serverSide": false, //Feature control DataTables' server-side processing mode.
			    "paging":   false,
			    "info":     false,
			    "responsive":false,
			   	"searching":false
		 	});*/
		});
	
	function get_scheme_vendor(scheme_code){
		var urls =base_url+"service/get_scheme_vendor_list/"+scheme_code;
		$.ajax({
			url : urls,
			method : 'POST',
			cache: false,
	        contentType: false,
	        processData: false,
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if(obj.status == true){
	                	$("#dtableCompanydashboardTwo").hide();
	                	$("#all_scheme_list").hide();
	                	$("#display").hide();
	                	$("#total_scheme_head").hide();
	                	$(".show_button").show();
	                	$(".hide_button").hide();
	                	$("#scheme_name").html("");
						$("#scheme_name").append(obj.data);
						$("#vendor_scheme_list").html("");
						$("#vendor_scheme_list").append(obj.data1);
						$("#total_result").html("");
						$("#total_result").append(obj.data2);
	                	$("#vendor_list").show();
	                	$("#scheme_name").show();
	                	$("#dtableVendordashboardTwo").show();
					}else{
						toastr.warning(obj.data);
					}
	            } catch (e) {
	                toastr.error("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	}
	
	/*function get_vendor_modal(vendor_name){
		$("#spanTechnician").html(vendor_name);
		$("#vendor_details_modal").modal('show');
	}*/
	
	function get_vendor_modal(supplier_code,scheme_code,suplier_name){
		
		var urls =base_url+"service/get_raise_ticket_cases";
		$.ajax({
			url : urls,
			method : 'POST',
			data :{supplier_code:supplier_code,scheme_code:scheme_code,op_type:"get_raise_ticket_cases"},
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if(obj.status == true){
	                	$("#hidden_supplier_code").val(supplier_code);
	                	$("#hidden_scheme_code").val(scheme_code);
	                	$("#hidden_suplier_name").val(suplier_name);
	                	$("#spanVendorName").html("");
	                	$("#spanVendorName").html(suplier_name);
	                	$("#total_ticket_result").html("");
	                	$("#total_ticket_result").append(obj.data1);
						$("#vendor_details_modal").modal({backdrop: 'static',keyboard: false});
					}else{
						toastr.error("No Data Found");
					}
	            } catch (e) {
	                toastr.error("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	}
	
	$("#btn_download_comapny_pdf").click(function(){
		window.open(base_url+"op-print/print_company_view_pdf");
	});
	
	$("#btn_download_scheme_pdf").click(function(){
		window.open(base_url+"op-print/print_total_scheme_view_pdf");
	});
	
	$("#btn_download_vendor_pdf").click(function(){
		var scheme_code = $('#scheme_code').val();
		window.open(base_url+"op-print/print_total_vendor_view_pdf/"+encodeURIComponent(btoa(scheme_code)));
	});
	$("#btn_download_ticket_pdf").click(function(){
		var supplier_code = $('#hidden_supplier_code').val();
		var scheme_code = $('#hidden_scheme_code').val();
		var suplier_name = $('#hidden_suplier_name').val();
		window.open(base_url+"op-print/print_vendor_ticket_pdf/"+encodeURIComponent(btoa(supplier_code+'#'+scheme_code+'#'+suplier_name)));
	});
	
	
	function btnTransactionReport(tkt_id){
	window.open(base_url+"transaction-report/"+encodeURIComponent(btoa(tkt_id)));
	}