	/*
	 * Author: Debashish Jyotish
	 * Date: 17/07/2018
	 * Description : This is used for Login details for all user.
	 * 
	 **/
	$(document).ready(function() {
	 	getLoginDetails();
	});

	function getLoginDetails(){
		var urls =base_url+"service/get_logindetails_data/logindetails";
		jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
	        if ( this.context.length ) {
	        	var dataArr = [];
	            var jsonResult = $.ajax({
	                url: urls,
	                type:'POST',
	                data:{page:"ALL",csrf_login_token : $('#csrf_login_details_token').val()},
	                success: function (result) {
	                    res = jQuery.parseJSON(result);
	                    $.each(res.aaData,function(i,arr){
	                    	var objArr = [i+1,arr.user_display_name ,arr.role_name,arr.ip_address,arr.created_on, arr.message];
	                    	dataArr.push(objArr);
	                    });
	                },
	                async: false
	            });
	            return {body: dataArr, header: ['#','Display Name','Role','IP Address','Date', 'Message']};
	        }
	    });
		var dtblLoginDetails = $('#dtblLoginDetails').dataTable({
			"processing": false, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"destroy": true,
			"paging":   true,
			"info":     true,
			"autoWidth": false,
			"scrollX":false,
			"responsive":true,
			"searching":true,
			"bSort" : false,
			"bLengthChange": true,
			"sDom":"<'row'<'col-xs-4'B><'col-xs-4'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' <'col-xs-6'i>>><'col-xs-6'p>>",
	        "buttons": [
		        {
					text: '<i class="fa fa-lg fa-file-excel-o" style="color:green">  Excel</i>',
		            extend: 'excel',
		            filename:'Login Details',
		            header:true,
		            title: 'Login Details',
		            extension: '.xls'
				},
				{
					text: '<i class="fa fa-lg fa-file-pdf-o" style="color:red">  Pdf</i>',
		            extend: 'pdf',
		            filename:'Login Details',
		            title: 'Login Details',   
				}
	        ],	
			"ajax":
			{
				"url": urls,
				"type": "POST",
				"data": function (data){
		    		data.csrf_login_token = $('#csrf_login_details_token').val();
		    		data.page = '';
		    	}
			},
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				// {"sName": "user_display_name","sClass":"alignLeft"},
				{
					data: null,
					render: function(data, type, row, meta){
						var job_title = '';
						if(data.job_title){
							var job_title = ' ('+data.job_title+')';
						}
						return data.user_display_name+job_title;
					}
				},
				{"sName": "role_name","sClass":"alignLeft"},
				{"sName": "ip_address","sClass":"alignCenter"},
				{"sName": "created_on","sClass":"alignCenter"},
				{"sName": "message","sClass":"alignLeft"},
			],
		});
	}