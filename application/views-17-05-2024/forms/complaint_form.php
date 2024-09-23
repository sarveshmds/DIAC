	<div class="content-wrapper">
		<section class="content-header">
			<h1>Order Sheet</h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box">
            			<div class="box-body">
							<div class="col-lg-12">
				              	<table id="dataTableComplaintform" class="table table-condensed table-striped table-bordered  display nowrap" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableComplaintform'); ?>">
				                    <thead>
				                        <tr>
											<th>Sl No</th>
											<th>Complaint Case No</th>
											<th>Complaint Name</th>
											<th>Opposite Party Name</th>
											<th>Date of registration</th>
											<th>Document</th>
				                            <th>Action</th>
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
	
	
	<div id="AddComplaintModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title" style="text-align: 	center;">Complaint Details</h4>
	      </div>
	      <div class="modal-body">
	        <?php include_once(dirname(dirname(__FILE__)) . '/templates/alerts.php');?>
			<?php echo form_open(null, array('class'=>'wfst','id'=>'form_add_complaintform','enctype'=>"multipart/form-data")); ?>
    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
    			<input type="hidden" id="op_type" name="op_type" value="ADD_COMPLAINT_FORM">
    			<input type="hidden" id="hidden_document_upload" name="hidden_document_upload" value="">
    			<input type="hidden" id="hidden_id" name="hidden_id" value="">
    			<input type="hidden" name="csrf_complaint_form_token" value="<?php echo generateToken('form_add_complaintform'); ?>">	
    		    	<div class="col-md-12">
    		    		<div class="col-md-12">
							<div class="form-group col-md-6 required">
								<label class="control-label">Complaint Case No:</label>
								<input type="text" class="form-control" id="txtcomplaintcaseno" name="txtcomplaintcaseno"  autocomplete="off" maxlength="50"/>
							</div>
							<div class="form-group col-md-6 required">
								<label class="control-label">Complaint Name:</label>
								<input type="text" class="form-control" id="txtcomplaintname" name="txtcomplaintname"  autocomplete="off" maxlength="50"/>
							</div>
							<div class="form-group col-md-6 required">
								<label class="control-label">Opposite Party Name:</label>
								<input type="text" class="form-control" id="txtoppositename" name="txtoppositename"  autocomplete="off" maxlength="50"/>
							</div>
							<div class="form-group col-md-6 required">
								<label class="control-label">Registration Date:</label>
								<div class="input-group date">
									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									<input type="text" class="form-control custom-all-date" id="txtregdate" name="txtregdate" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y');?>"/>
								</div>
							</div>
							<div class="form-group col-md-6 required">
								<label class="control-label">Petition Date:</label>
								<div class="input-group date">
									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									<input type="text" class="form-control custom-all-date" id="txtpetitiondate" name="txtpetitiondate" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y');?>"/>
								</div>
							</div>
							<div class="form-group col-md-6 required">
								<label class="control-label">Action Taken:</label>
								<textarea type="text" class="form-control" id="txtaction" name="txtaction"  autocomplete="off" maxlength="50"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label>Document Upload:</label>
								<input type="file" class="filestyle" name="txtdocument"  accept="application/pdf"/>
								<div id="file_selector"></div>
							    <span style="color: #ff0000;font-style: italic;font-size:11px">
								File-Type: PDF &nbsp; File-Size: 500 KB Max </span>
							</div>
						</div>
					</div>
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
	<script>
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
		var urls =base_url+"service/get_all_complaint_data/ALL_COMPLAINT_DATA";
		var dataTableComplaintform = $('#dataTableComplaintform').dataTable({
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
			    }
			},
			"sDom":"<'row'<'col-xs-2 btn_add'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
			"columns": [
				{"sName": "sl_no","sClass":"alignCenter"},
				{"sName": "complaint_case_no","sClass":"alignLeft"},
				{"sName": "complaint_name","sClass":"alignLeft"},
				{"sName": "opposite_party_name","sClass":"alignLeft"},
				{"sName": "registration_date","sClass":"alignLeft"},
				{ "sName": "document","sWidth": "5%","sClass" : "alignCenter","mRender": function( data, type, full ){
	                return '<a href = '+base_url+'public/upload/document/'+full.document+'>'+full.document+'</a>' 
	            	}  
	        	},
				{
					"sName": "button",data:null,"sClass" : "alignCenter","sDefaultContent":"<button type='button' class='btn bg-maroon btn-circle tooltipTable tooltipstered' onclick='btnFormPdf(event)' align='center' title='View' ><i class='fa fa-eye'></i></button>\
					<button type='button' class='btn bg-primary btn-circle tooltipTable tooltipstered' align='center' onclick='btnEditForm(event)' title='Edit' ><i class='fa fa-pencil'></i></button>"
		       	}
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
		$("div.btn_add").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="btnAddForm()" ><i class="fa fa-plus" aria-hidden="true"></i></button>');
		function btnAddForm(){
			$('#form_add_complaintform').data('bootstrapValidator').resetForm(true);
			$('#file_selector').html(''); 
			$("#btn_submit")[0].innerHTML ="<i class='fa fa-paper-plane'></i> Submit"
			$('#AddComplaintModal').modal('show');
		}
		function btnEditForm(event){
			$('#AddComplaintModal').modal({backdrop: 'static',keyboard: false});
			$("#btn_submit")[0].innerHTML ="<i class='fa fa-edit'></i> Update";
			$('#form_add_complaintform').data('bootstrapValidator').resetForm(true);
		    $("#form_add_complaintform input[name='op_type']").val("EDIT_COMPLAINT_FORM");
		    var oTable = $('#dataTableComplaintform').dataTable();
		    var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
		    var complaint_case_no = oTable.fnGetData(row)['complaint_case_no'];
		    var complaint_name = oTable.fnGetData(row)['complaint_name'];
		    var opposite_party_name = oTable.fnGetData(row)['opposite_party_name'];
		    var registration_date = oTable.fnGetData(row)['registration_date'];
		    var petition_date = oTable.fnGetData(row)['petition_date'];
		    var action_taken = oTable.fnGetData(row)['action_taken'];
		    var id = oTable.fnGetData(row)['id'];
		    var file_doc= '';
			var base_file_doc = '';
			var path = oTable.fnGetData(row)['document_path']
			if(path){
				file_doc = oTable.fnGetData(row)['document_path'];
				var split_file_doc= file_doc.split('/');
				base_file_doc = split_file_doc.pop();
			}else{
				base_file_doc = "";
			}
		    $('#hidden_document_upload').val(path);
		    $('#hidden_id').val(id);
		    $('#txtcomplaintcaseno').val(complaint_case_no);
		    $('#txtcomplaintname').val(complaint_name);
		    $('#txtoppositename').val(opposite_party_name); 
		    $('#txtregdate').val(registration_date); 
		    $('#txtpetitiondate').val(petition_date); 
		    $('#txtaction').val(action_taken); 
		   	$('#file_selector').html(base_file_doc); 
		}
		function btnFormPdf(event){
			var oTable = $('#dataTableComplaintform').dataTable();
		    var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;
		    var id = oTable.fnGetData(row)['id'];
			window.open(base_url+"op-print/print_complaint_form_pdf/"+id);
		};
		
		$('#form_add_complaintform').bootstrapValidator({
	    message: 'This value is not valid',
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) {
			$('#btn_submit').attr('disabled','disabled');
			var formData = new FormData(document.getElementById("form_add_complaintform"));
			urls =base_url+"service/operation_add_complaint_form";
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
							$('#form_add_complaintform').data('bootstrapValidator').resetForm(true);//Reseting user form
		                	toastr.success(obj.msg);
		                	window.location.href = base_url+"complaint-form";
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
	        txtcomplaintcaseno: {							
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
	        txtoppositename: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtregdate: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtpetitiondate: {							
	           validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        },
	        txtaction: {							
	            validators: {
	                notEmpty: {
	                    message: 'Required'
	                }
	            }
	        }
	    }
	    })
	</script>