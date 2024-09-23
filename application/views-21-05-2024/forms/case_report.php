	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Case Report</h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box">
            			<div class="box-body">
            				<div class="col-lg-12">
            					<div class="form-group col-md-3">
									<label class="control-label">District:</label>
									<select class="form-control" name="cmb_district" id="cmb_district">
										<option value="">Select</option>
										<?php foreach($district as $dist){?>
										<option value="<?=$dist['district_code']?>"><?=$dist['district_name']?></option>
									<?php } ?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="control-label">From Date:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="frm_date" name="frm_date" autocomplete="off" readonly="true" value=""/>
									</div>
								</div>
								
								<div class="form-group col-md-3">
									<label class="control-label">To Date:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="to_date" name="to_date" autocomplete="off" readonly="true" value=""/>
									</div>
								</div>
								
								<div class="box-tools" style="padding-top: 25px">
					          		<button type="button" class="btn btn-info" id="btn_search"> <i class="fa fa-filter"></i></button>
					          		<button type="reset" class="btn btn-default" id="btn_reset"> <i class="fa fa-refresh"></i></button>
					          	</div>
            				</div>
            				<div id="header" style="text-align:center;font-size: 18px;color:red;">
				
							</div>
							<div class="col-lg-12">
				              	<table id="dataTableCaseReport" class="table table-condensed table-striped table-bordered" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCaseReport'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:5%;">Sl No</th>
											<th style="width:10%;">Case No</th>
											<th style="width:5%;">Date of Institution</th>
											<th style="width:15%;">Name & Address of complaint</th>
											<th style="width:15%;">Name & Address of public servant complained against</th>
											<th style="width:15%;">Brief facts relating to the action complained of</th>
											<th style="width:5%;">Date of disposal</th>
											<th style="width:10%;">Result of the complaint</th>
											<th style="width:5%;">Document</th>
				                        </tr>
				                    </thead>
				                </table>
	            			</div>
            			</div>
            		</div>
          		</div>
			</div>
		</section>
	</div>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
	<script>
		var date = new Date();
		var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
		var tempDate = today;
		tempDate.setDate(tempDate.getDate());
		var today_date = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();	
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
		
		
		$(function(){
	 	getComplaintReport();	
		});
	 	function getComplaintReport(){
		//var urls =base_url+"service/get_all_complaint_report/ALL_COMPLAINT_REPORT_DATA";
		var urls =base_url+"service/get_all_case_report/ALL_CASE_REPORT_DATA";
		jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
	        if ( this.context.length ) {
	        	var dataArr = [];
	            var jsonResult = $.ajax({
	                url: urls,
	                type:'POST',
	                data:{page:"ALL",csrf_trans_token : $('#csrf_trans_token').val(),frm_date : $('#frm_date').val(),to_date : $('#to_date').val(),cmb_district : $('#cmb_district').val()},
	                success: function (result) {
	                    res = jQuery.parseJSON(result);
	                    $.each(res.aaData,function(i,arr){
	                    	var objArr = [i+1,arr.case_no,arr.institution_date,arr.complaint,arr.public,arr.brief_facts,arr.disposal_date,arr.complaint_result,arr.document];
	                    	dataArr.push(objArr);
	                    });
	                },
	                async: false
	            });
	            return {body: dataArr, header: ['#','Case No','Institution Date','Complaint Name','Public Servant','Brief Facts','Disposal Date','Complaint Result','Document Uploaded']};
	        }
	    });
		var dataTableCaseReport = $('#dataTableCaseReport').dataTable({
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
			    	data.csrf_trans_token = $('#csrf_trans_token').val();
			    	data.frm_date = $('#frm_date').val();
			    	data.to_date = $('#to_date').val();
			    	data.cmb_district = $('#cmb_district').val();
			    }
			},
			"sDom":"<'row'<'col-xs-2'B><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			//dom: 'Bfrtip',
	        buttons: [{
	        	text: '<i class="fa fa-lg fa-file-pdf-o" style="color:red">  pdf</i>',
                extend: 'pdfHtml5',
                orientation: 'portrait',
                title: 'Case Report',
                pageSize: 'LEGAL',
		        columns: [ 0, 1, 2, 3, 4, 5, 6, 7],
                customize: function(doc) {
				    doc.defaultStyle.alignment = 'center';
				    doc.styles.tableHeader.alignment = 'center';
            	}
	        }],
			
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter","sWidth":"5%"},
				{"sName": "case_no","sClass":"alignLeft","sWidth":"10%"},
				{"sName": "institution_date","sClass":"alignLeft","sWidth":"5%"},
				{"sName": "complaint","sClass":"alignLeft","sWidth":"15%"},
				{"sName": "public","sClass":"alignLeft","sWidth":"15%"},
				{"sName": "brief_facts","sClass":"alignLeft","sWidth":"15%"},
				{"sName": "disposal_date","sClass":"alignLeft","sWidth":"5%"},
				{"sName": "complaint_result","sClass":"alignLeft","sWidth":"10%"},
				{ "sName": "document","sWidth":"5%","sClass" : "alignCenter","mRender": function( data, type, full ){
	                return '<a href = '+base_url+'public/upload/document/'+full.document+'>'+full.document+'</a>' 
	            	}  
	        	},
			],
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
		$('#btn_search').click(function(){
			frm_date = $('#frm_date').val();
			to_date = $('#to_date').val();
			if(frm_date==""||to_date==""){
				$("#header").html('');
			}else{
				$("#header").html('Report From '+frm_date+' to '+to_date+'');
			}
			dataTableCaseReport = $('#dataTableCaseReport').DataTable();
			dataTableCaseReport.draw();
			dataTableCaseReport.clear();
		});
		
		$("#btn_reset").click(function(){
			$("#header").html('');
		$("#to_date").val('');
		$("#frm_date").val('');
		$("#cmb_district").val('');
		dataTableCaseReport = $('#dataTableCaseReport').DataTable();
		dataTableCaseReport.draw();
		dataTableCaseReport.clear();
	});
	</script>