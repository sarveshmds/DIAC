	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
	<style>
		.pdf_button{
			background-color:red;
			color:white;
		}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Register Cases</h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box">
            			<div class="box-body">
							<div class="col-lg-12">
				              	<table id="dataTableCaseform" class="table table-condensed table-striped table-bordered" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCaseform'); ?>">
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
				                            <th style="width:10%;">Action</th>
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
	
	
	<div id="AddCaseModal" class="modal fade" role="dialog">
	  	<div class="modal-dialog modal-lg">
	    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title" style="text-align: 	center;">Case Details</h4>
		      </div>
		      <div class="modal-body">
		        <?php include_once(dirname(dirname(__FILE__)) . '/templates/alerts.php');?>
				<?php echo form_open(null, array('class'=>'wfst','id'=>'form_add_caseform','enctype'=>"multipart/form-data")); ?>
	    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
	    			<input type="hidden" id="op_type" name="op_type" value="ADD_CASE_FORM">
	    			<input type="hidden" id="hidden_document_upload" name="hidden_document_upload" value="">
	    			<input type="hidden" id="hidden_id" name="hidden_id" value="">	
	    			<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_caseform'); ?>">	
	    		    	
	    		    	<fieldset>
	    		    		<legend>Case Details</legend>
	    		    		<div class="col-md-12">
	    		    			<div class="form-group col-md-3 required">
									<label class="control-label">Case No:</label>
									<input type="text" class="form-control" id="txtCaseNo" name="txtCaseNo"  autocomplete="off" maxlength="50"/>
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">Date of Institution:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="txtinstitutiondate" name="txtinstitutiondate" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y');?>"/>
									</div>
								</div>
							</div>
	    		    	</fieldset>
	    		    	<fieldset>
	    		    		<legend>Name and Address of Complaintant</legend>
	    		    		<div class="col-md-12">
	    		    			<div class="form-group col-md-3 required">
									<label class="control-label">Complaint Name:</label>
									<input type="text" class="form-control" id="txtcomplaintname" name="txtcomplaintname"  autocomplete="off" maxlength="20"/>
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">Address(At-):</label>
									<input type="text" class="form-control" id="txtcomplaintat" name="txtcomplaintat"  autocomplete="off" maxlength="10">
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">Address(Po-):</label>
									<input type="text" class="form-control" id="txtcomplaintpo" name="txtcomplaintpo"  autocomplete="off" maxlength="10">
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">Address(Police Station-):</label>
									<input type="text" class="form-control" id="txtcomplaintps" name="txtcomplaintps"  autocomplete="off" maxlength="10">
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">District:</label>
									<select class="form-control" name="cmb_complaint_district" id="cmb_complaint_district">
										<option value="">Select</option>
										<?php foreach($district as $dist){?>
										<option value="<?=$dist['district_code']?>"><?=$dist['district_name']?></option>
									<?php } ?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="control-label">Pin:</label>
									<input type="text" class="form-control" id="txtcomplaintpin" name="txtcomplaintpin"  autocomplete="off" maxlength="10">
								</div>
							</div>
	    		    	</fieldset>
	    		    	<fieldset>
	    		    		<legend>Name and Address of Public Servant Complained Against</legend>
	    		    		<div class="col-md-12">
	    		    			<div class="form-group col-md-3 required">
									<label class="control-label">Public Servant Name:</label>
									<input type="text" class="form-control" id="txtpublicname" name="txtpublicname"  autocomplete="off" maxlength="20"/>
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">Address:</label>
									<input type="text" class="form-control" id="txtpublicaddress" name="txtpublicaddress"  autocomplete="off" maxlength="20">
								</div>
								<div class="form-group col-md-3 required">
									<label class="control-label">District:</label>
									<select class="form-control" name="cmb_public_district" id="cmb_public_district">
										<option value="">Select</option>
										<?php foreach($district as $dist){?>
										<option value="<?=$dist['district_code']?>"><?=$dist['district_name']?></option>
									<?php } ?>
									</select>
								</div>
							</div>
	    		    	</fieldset>
	    		    	<fieldset>
	    		    		<legend>Other Details</legend>	    		    		
								<div class="col-md-12">
									<div class="form-group col-md-6 required">
										<label class="control-label">Brief Facts Relating to the action complained of:</label>
										<textarea type="text" class="form-control" rows="5" id="txtbrieffacts" name="txtbrieffacts"  autocomplete="off" maxlength="200"></textarea>
									</div>
									<div class="form-group col-md-6">
										<label class="control-label">Date of Disposal:</label>
										<div class="input-group date">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
											<input type="text" class="form-control custom-all-date" id="txtdisposaldate" name="txtdisposaldate" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y');?>"/>
										</div>
									</div>
									
								</div>									
								<div class="col-md-12">									
									<div class="form-group col-md-6">
										<label class="control-label">Result of Complaint:</label>
										<textarea type="text" class="form-control" rows="5" id="txtcomplaintresult" name="txtcomplaintresult"  autocomplete="off" maxlength="200"></textarea>
									</div>
									<div class="form-group col-md-6">
										<label class="control-label">Document Upload:</label>
										<input type="file" class="filestyle" name="txtdocument"  accept="application/pdf"/>
										<div id="file_selector"></div>
										<span style="color: #ff0000;font-style: italic;font-size:11px">
										File-Type: PDF &nbsp; File-Size: 500 KB Max </span>
									</div>								
								</div>							
	    		    	</fieldset>	    		    	
						<div class="box-footer with-border">
				          	<div class="box-tools pull-right">
				          		<button type="submit" class="btn btn-success" id="btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
				          		<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
				          	</div>
		        		</div>
		      	<?php echo form_close();?>
		      </div>
		    </div>
	  	</div>
	</div>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
	<script>
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
		
		var urls =base_url+"service/get_all_case_data/ALL_CASE_DATA";
		jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
	        if ( this.context.length ) {
	        	var dataArr = [];
	            var jsonResult = $.ajax({
	                url: urls,
	                type:'POST',
	                data:{page:"ALL",csrf_trans_token : $('#csrf_trans_token').val()},
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
		var dataTableCaseform = $('#dataTableCaseform').dataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"destroy":    true,
			"paging":     true,
			"info":       true,
			"autoWidth":  true,
			"scrollX":    true,
			"responsive": false,
			"searching":  true,
			"ajax":{
				"url": urls,
				"type": "POST",
				"data": function (data){
			    	data.csrf_trans_token = $('#csrf_trans_token').val();
			    }
			},
			
			//dom: 'Bfrtip',
	        buttons: [{
	        	text: '<i class="fa fa-lg fa-file-pdf-o" style="color:red">  pdf</i>',
                extend: 'pdfHtml5',
                className: 'pdf_button',
                orientation: 'landscape',
                title: 'Register Cases',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ],
                },
                customize: function(doc) {
				    doc.defaultStyle.alignment = 'left';
				    doc.styles.tableHeader.alignment = 'left';
            	}
	        }],
			"sDom":"<'row'<'col-md-1 btn_add'><'col-xs-1'B><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
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
				
				{
					"sName": "button",data:null,"sClass" : "alignCenter","sWidth":"10%","sDefaultContent":"<button type='button' class='btn btn-sm bg-maroon btn-circle tooltipTable tooltipstered' onclick='btnFormPdf(event)' align='center' title='Case Order Download' ><i class='fa fa-download'></i></button>\
					<button type='button' class='btn btn-sm bg-primary btn-circle tooltipTable tooltipstered' align='center' onclick='btnEditForm(event)' title='Edit' ><i class='fa fa-pencil'></i></button>"
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
		$("div.btn_add").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="btnAddForm()" ><i class="fa fa-plus" aria-hidden="true"></i></button>');
		function btnAddForm(){
			$('#form_add_caseform').data('bootstrapValidator').resetForm(true);
			$('#file_selector').html('');
			$("#btn_submit")[0].innerHTML ="<i class='fa fa-paper-plane'></i> Submit"
			$('#AddCaseModal').modal('show');
		}
		function btnEditForm(event){
			$('#AddCaseModal').modal({backdrop: 'static',keyboard: false});
			$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
			$('#form_add_caseform').data('bootstrapValidator').resetForm(true);
		    $("#form_add_caseform input[name='op_type']").val("EDIT_CASE_FORM");
		    var oTable = $('#dataTableCaseform').dataTable();
		    var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
		    var Case_no = oTable.fnGetData(row)['case_no'];
		    var institution_date = oTable.fnGetData(row)['institution_date'];
		    var complaint_name = oTable.fnGetData(row)['complaint_name'];
		    var complaint_address_at = oTable.fnGetData(row)['complaint_address_at'];
		    var complaint_address_po = oTable.fnGetData(row)['complaint_address_po'];
		    var complaint_address_ps = oTable.fnGetData(row)['complaint_address_ps'];
		    var complaint_pin = oTable.fnGetData(row)['complaint_pin'];
		    var complaint_district = oTable.fnGetData(row)['complaint_district_code'];
		    var Public_servant_name = oTable.fnGetData(row)['public_name'];
		    var Public_servant_address = oTable.fnGetData(row)['public_address'];
		    var Public_servant_district = oTable.fnGetData(row)['public_district_code'];
		    var brief_facts = oTable.fnGetData(row)['brief_facts'];
		    var disposal_date = oTable.fnGetData(row)['disposal_date'];
		    var complaint_result = oTable.fnGetData(row)['complaint_result'];
		    var id = oTable.fnGetData(row)['id'];
		    var file_doc= '';
			var base_file_doc = '';
			var path = oTable.fnGetData(row)['document']
		    $('#hidden_document_upload').val(path);
		    $('#hidden_id').val(id);
		    $('#txtCaseNo').val(Case_no).prop('readonly', true);
		    $('#txtinstitutiondate').val(institution_date);
		    $('#txtcomplaintname').val(complaint_name).prop('readonly', true); 
		    $('#txtcomplaintat').val(complaint_address_at).prop('readonly', true); 
		    $('#txtcomplaintpo').val(complaint_address_po).prop('readonly', true); 
		    $('#txtcomplaintps').val(complaint_address_ps).prop('readonly', true); 
		    $('#txtcomplaintpin').val(complaint_pin).prop('readonly', true); 
		    $('#cmb_complaint_district').val(complaint_district); 
		    $('#txtpublicname').val(Public_servant_name).prop('readonly', true); 
		    $('#txtpublicaddress').val(Public_servant_address).prop('readonly', true); 
		    $('#cmb_public_district').val(Public_servant_district); 
		    $('#txtdisposaldate').val(disposal_date); 
		    $('#txtbrieffacts').val(brief_facts); 
		    $('#txtcomplaintresult').val(complaint_result); 
		   	$('#file_selector').html(path); 
		}
		$('#form_add_caseform').bootstrapValidator({
	    message: 'This value is not valid',
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) {
			$('#btn_submit').attr('disabled','disabled');
			var formData = new FormData(document.getElementById("form_add_caseform"));
			urls =base_url+"service/operation_add_case_form";
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
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
		                	toastr.error(obj.msg);
		                }else if(obj.status === 'validationerror'){
		                	$('#errorlog').html(obj.msg);
		                	$('#errorlog').show();
		                }else{
		                	$('#errorlog').html('');
		                	$('#errorlog').hide();
							$('#form_add_caseform').data('bootstrapValidator').resetForm(true);//Reseting user form
		                	toastr.success(obj.msg);
		                	window.location.href = base_url+"register-cases";
		                }
		            } catch (e) {
		                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
		            }
				},error: function(err){
					toastr.error("unable to save");
				}
			});
        },
		fields: {
	        txtCaseNo: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtinstitutiondate: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtcomplaintname: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtcomplaintat: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtcomplaintpo: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtcomplaintps: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_complaint_district: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtpublicname: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtpublicaddress: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        cmb_public_district: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtbrieffacts: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	    }
	    })
	    
	    function btnFormPdf(event){
			var oTable = $('#dataTableCaseform').dataTable();
		    var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
		    var id = oTable.fnGetData(row)['id'];
			window.open(base_url+"op-print/print_complaint_form_pdf/"+id);
		};
	</script>