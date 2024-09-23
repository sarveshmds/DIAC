 //var urls =base_url+"service/get_admin_department/get_admin_department";
 var look_up = $('#dataTableLookUp').DataTable();
    
  //  $("div.btn_adminDeparstment_modal").html('<button class="btn btn-primary tooltipTable btn-circle" title="Add"  data-toggle="modal" data-target="#add_admin_department_modal" ><i class="fa fa-plus" aria-hidden="true"></i></button>');
	 var sl_no = 1;
	 $(".add_table_row").click(function(){
    	var table_content = '<tr>\
				<td class="serial_no" style="width:5%">'+sl_no+'</td>\
				<td><input type="text" class="form-control" name="txt_sequence" id="txt_sequence'+sl_no+'"/></td>\
				<td><input type="text" class="form-control" name="txt_value" id="txt_value'+sl_no+'"/></td>\
				<td style="width: 20%">\
					<input type="text" class="form-control" name="txt_status" id="txt_status'+sl_no+'"/>\
				</td>\
				<td style="width:5%"><button type="button" class="btn btn-danger" id="remove_tableRow" ><i class="fa fa-trash" ></i></button></td>\
			</tr>';
	    $(".table_row").append(table_content);
		sl_no++;			
    });
    
   	$('#table_id tbody').on( 'click', '#remove_tableRow', function () {
    	$(this).closest('tr').remove();
    });