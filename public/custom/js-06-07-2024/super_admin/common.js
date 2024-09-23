	// A $( document ).ready() block.
	$( document ).ready(function() {
		$(".timepicker").timepicker({
			placement: 'top',
			align: 'left',
	      	showInputs: false
	    });
		$('.custom-all-date').datepicker({
		    format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true
		});
		$('.custom-date').datepicker({
		    format: 'dd-mm-yyyy',
    		startDate: '-0d',
			todayHighlight: true,
			autoclose: true,
	  		maxDate: 0
		});
		$('.tooltipTable').tooltipster({
         	theme: 'tooltipster-punk',
      		animation: 'grow',
        	delay: 200, 
         	touchDevices: false,
         	trigger: 'hover'
  		});
  		$(".select2").select2(); 
  		$('.multiselect').multiselect({
	        enableFiltering: true,
	        includeSelectAllOption:true,
			enableCaseInsensitiveFiltering:true,
			numberDisplayed: 1,
			buttonWidth:"250px",
			minHeight:100,
			nonSelectedText : '-- ALL --',
			maxHeight:250,
			
		});
		$(".yearpicker").datepicker({
		    format: "yyyy",
		    viewMode: "years", 
		    minViewMode: "years"
		});
		$(".monthpicker").datepicker( {
		    format: "MM",
		    viewMode: "months", 
		    minViewMode: "months"
		});
       	$('.clockpicker').clockpicker({
		    placement: 'top',
		    align: 'left',
		    donetext: 'Done',
		    twelvehour: true
		}); 
	});
	function get_district(state_code,appendDistId,selected_dist=''){		
		$.ajax({
			url: base_url+"service/get_dist_dropdown",
			type:"POST",
			data:{op_type:'get_dist_name',state_code : state_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					
					if(selected_dist == res1[i].district_code){
						selected_dist_check ='selected';
						
					}else{
						selected_dist_check='';
					}
					options = options + "<option value='"+res1[i].district_code+"' "+selected_dist_check+">"+res1[i].district_name+"</option>";
				}
				$('#'+appendDistId).html("");
				$('#'+appendDistId).append(options);
				
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	function get_block(dist_code,appendBlockId,selected_block=''){
		$.ajax({
			url: base_url+"service/get_block_dropdown",
			type:"POST",
			data:{op_type:'get_block_name',dist_code : dist_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(selected_block == res1[i].block_code){
						selected_block ='selected';
					}else{
						selected_block='';
					}
					options = options + "<option value='"+res1[i].block_code+"' "+selected_block+">"+res1[i].block_name+"</option>";
				}
				$('#'+appendBlockId).html("");
				$('#'+appendBlockId).append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	
	function get_circle(block_code,appendCircleId,selected_circle=''){
		$.ajax({
			url: base_url+"service/get_circle_dropdown",
			type:"POST",
			data:{op_type:'get_circle_name',block_code : block_code},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					if(selected_circle == res1[i].circle_code){
						selected_circle ='selected';
					}else{
						selected_circle='';
					}
					options = options + "<option value='"+res1[i].circle_code+"' "+selected_circle+">"+res1[i].circle_name+"</option>";
				}
				$('#'+appendCircleId).html("");
				$('#'+appendCircleId).append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	function get_approval_status(appendStatusId){
		$.ajax({
			url: base_url+"service/get_approval_status",
			type:"POST",
			data:{op_type:'get_approval_status'},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					options = options + "<option value='"+res1[i].gen_code+"'>"+res1[i].description+"</option>";
				}
				$('#'+appendStatusId).html("");
				$('#'+appendStatusId).append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	function get_area_type(appendStatusId){
		$.ajax({
			url: base_url+"service/get_area_type",
			type:"POST",
			data:{op_type:'get_area_type'},
			success:function(response){
				var options = "<option value=''>Select</option>";
				var res1 = JSON.parse(response); 
				for (var i = 0; i < res1.length; i++) {
					options = options + "<option value='"+res1[i].gen_code+"'>"+res1[i].description+"</option>";
				}
				$('#'+appendStatusId).html("");
				$('#'+appendStatusId).append(options);
			},error:function(){
				toastr.error('Unable to process please contact support');
			}
 		});
	}
	function upload_image(formData){
		urls = base_url+"service/applicant_image_upload";
		$.ajax({
			url : urls,
			method : 'POST',
			data:formData,
			cache: false,
	        contentType: false,
	        processData: false,
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	toastr.error(obj.msg);
	                }else if(obj.status == 101){
	                	toastr.warning(obj.msg);
	                }else if(obj.status == 102){
	                	toastr.warning(obj.msg);
	                }else if(obj.status == 103){
						toastr.info(obj.msg);
					}else{
						toastr.success(obj.msg);
					}
	            } catch (e) {
	                toastr.error(e);
	            }
			},error: function(err){
				toastr.error(err);
			}
		});
	}
	
	
	