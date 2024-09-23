<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
	<style>
		.pdf_button{
			background-color:red;
			color:white;
		}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?= $page_title ?></h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box wrapper-box">
            			<div class="box-body">
							<div>
				              	<table id="dataTablePanelCategoryList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
				                    <input type="hidden" name="csrf_trans_token" id="csrf_pc_form_token" value="<?php echo generateToken('dataTablePanelCategoryList'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:8%;">S. No.</th>
											<th>Category Name</th>
				                            <th style="width:15%;">Action</th>
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
	
	
	<div id="addPanelCategoryListModal" class="modal fade" role="dialog">
	  	<div class="modal-dialog">
	    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title pc-modal-title" style="text-align: center;"></h4>
		      </div>
		      <div class="modal-body">
				<?php echo form_open(null, array('class'=>'wfst','id'=>'form_panel_category','enctype'=>"multipart/form-data")); ?>
	    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
	    			<input type="hidden" id="pc_op_type" name="op_type" value="">
	    			<input type="hidden" id="pc_hidden_id" name="pc_hidden_id" value="">	
	    			<input type="hidden" name="csrf_case_form_token" id="csrf_pc_form_token" value="<?php echo generateToken('form_add_panel_category'); ?>">	
	    		    	
	    		    	<fieldset>
	    		    		<legend>Panel Category</legend>
	    		    		<div class="col-md-12">
	    		    			<div class="form-group col-md-12 col-xs-12 required">
									<label class="control-label">Category Name:</label>
									<input type="text" class="form-control" id="pc_category_name" name="pc_category_name"  autocomplete="off" maxlength="150"/>
								</div>
							</div>
	    		    	</fieldset>

						<div class="box-footer with-border text-center">
				          	<div class="box-tools">
								  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
								  <button type="submit" class="btn btn-custom" id="pc_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
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
		
		// Timepicker initialization
		$('.timepicker').timepicker({
			showInputs: false
		});
		
		// Datatable initialization
		var dataTable = $('#dataTablePanelCategoryList').DataTable({
			"processing": true,
			"serverSide": true,
			"autoWidth": false,
			"responsive": true,
			"order": [],
			"ajax": {
				url: base_url+"service/get_all_panel_category_list/ALL_PANEL_CATEGORY",
				type: 'POST',
				data: {
					csrf_trans_token: $('#csrf_pc_form_token').val()
				}
			},
			"columns": [
				{
					data: null,
					render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
				},
				{data: 'category_name'},
				{data: 'id',
					"sWidth": "20%",
					"sClass":"alignCenter",
					"render": function (data, type, row, meta) {
				        return '<button class="btn btn-warning btn-sm" onclick="btnEditPanelCategory(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeletePanelCategory('+data+')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				    }
				}
				
			],
			"columnDefs": [
				{
					"targets": [0, 2],
					"orderable": false,
					"sorting": false
				}
			],
			dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	        buttons: [
	        	{
	                text: '<span class="fa fa-plus"></span> Add',
	                className: 'btn btn-custom',
	                init: function(api, node, config){
	                	$(node).removeClass('dt-button');
	                },
	                action: function ( e, dt, node, config ) {
	                    add_panel_category_list_modal_open();
	                }
	            }
	        ],
			drawCallback: function () {
				$('body').tooltip({ selector: '[data-tooltip="tooltip"]' });
			}
		});

		 

		// Open the modal to add panel category list
		function add_panel_category_list_modal_open(){
			$('#pc_op_type').val('ADD_PANEL_CATEGORY');
			$('.pc-modal-title').html('<span class="fa fa-plus"></span> Add Panel Category');
			$('#addPanelCategoryListModal').modal({
            	backdrop: 'static',
            	keyboard: false
            })
		}

		// On closing the modal reset the form
		$('#addPanelCategoryListModal').on("hidden.bs.modal", function(e){
			$('.pc-modal-title').html('');
			$('#pc_op_type').val('');
			$('#pc_hidden_id').val('');
			$('#form_panel_category').trigger('reset');
			$("#pc_btn_submit").html("<i class='fa fa-paper-plane'></i> Add");
			$('#form_panel_category').data('bootstrapValidator').resetForm(true);
		});

		// Button edit form to open the modal with edit form
		function btnEditPanelCategory(event){

			// Change the submit button to edit
			$('#pc_btn_submit').attr('disabled',false);
			$("#pc_btn_submit").html('<span class="fa fa-edit"></span> Update');

			// Reset the form
			$('#form_panel_category').trigger('reset');
		    
		    // Change the op type
		    $("#pc_op_type").val("EDIT_PANEL_CATEGORY");

		    // Get data table instance to get the data through row
		    var oTable = $('#dataTablePanelCategoryList').dataTable();
		    var row;
		    if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "SPAN")
				row = event.target.parentNode.parentNode.parentNode;

			var id = oTable.fnGetData(row)['id'];
		    var category_name = oTable.fnGetData(row)['category_name'];
		    
		    $('#pc_hidden_id').val(id);
		    $('#pc_category_name').val(category_name);
		    
		    $('.pc-modal-title').html('<span class="fa fa-edit"></span> Edit Panel Category');
			$('#addPanelCategoryListModal').modal(
				{
					backdrop: 'static',keyboard: false
				}
			);
		}


		// Add panel category list form
		$('#form_panel_category').bootstrapValidator({
		    message: 'This value is not valid',
			submitButtons: 'button[type="submit"]',
			submitHandler: function(validator, form, submitButton) {
				$('#btn_submit').attr('disabled','disabled');
				var formData = new FormData(document.getElementById("form_panel_category"));
				urls =base_url+"service/panel_category_operation";
				$.ajax({
					url : urls,
					method : 'POST',
					data:formData,
			        contentType: false,
			        processData: false,
					success : function(response){
						// try {
			                var obj = JSON.parse(response);
			                if (obj.status == false) {
			                	// Enable the submit button
			                	enable_submit_btn('pc_btn_submit');

			                	swal({
			                		title: 'Error',
			                		text: obj.msg,
			                		type: 'error',
			                		html: true
			                	});
			                }else if(obj.status === 'validationerror'){
			                	// Enable the submit button
			                	enable_submit_btn('pc_btn_submit');

			                	swal({
			                		title: 'Validation Error',
			                		text: obj.msg,
			                		type: 'error',
			                		html: true
			                	});
			                }else{
			                	//Reseting form
								$('#form_panel_category').trigger('reset');
			                	toastr.success(obj.msg);

			                	// Redraw the datatable
			                	dataTableCauseList = $('#dataTablePanelCategoryList').DataTable();
								dataTableCauseList.draw();
								dataTableCauseList.clear();

								$('#addPanelCategoryListModal').modal("hide");
			                }
			            // } catch (e) {
			            //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
			            // }
					},error: function(err){
						toastr.error("unable to save");
					}
				});
	        },
			fields: {
		        pc_category_name: {							
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                },
		                regexp: {
		                    regexp: /^[a-zA-Z\s`\',.]+$/,
		                    message: "Only aplhabets, space, comma, single quote, dot, ` are allowed."
		        		}
		            }
		        }
		    }
	    })
	    

		// Delete function for panel category list
		function btnDeletePanelCategory(id){
			if(id){
				swal({
					type: 'error',
					title: 'Are you sure?',
					text: 'You want to delete the record.',
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Delete",
					cancelButtonText: "Cancel"
				}, function(isConfirm){
					if(isConfirm){
						$.ajax({
							url: base_url+'service/delete_panel_category/DELETE_PANEL_CATEGORY',
							type: 'POST',
							data: {
								id: id,
								csrf_trans_token: $('#csrf_pc_form_token').val()
							},
							success: function(response){
								
								try{
									var obj = JSON.parse(response);

					                if (obj.status == false) {
					                	$('#errorlog').html('');
					                	$('#errorlog').hide();
					                	toastr.error(obj.msg);
					                }else if(obj.status === 'validationerror'){
					                	swal({
					                		title: 'Validation Error',
					                		text: obj.msg,
					                		type: 'error',
					                		html: true
					                	});
					                }else{
					                	$('#errorlog').html('');
					                	$('#errorlog').hide();
					                	toastr.success(obj.msg);

					                	dataTableCauseList = $('#dataTablePanelCategoryList').DataTable();
										dataTableCauseList.draw();
										dataTableCauseList.clear();
					                }
								}
								catch(e){
									swal("Sorry",'Unable to Save.Please Try Again !', "error");
								}
							},
							error: function(error){
								toastr.error('Something went wrong.');
							}
						})
					}
					else{
						swal.close();
					}
				})
			}
		}

		// Function to enable the submit button
		function enable_submit_btn(id){
			$('#'+id).attr('disabled',false);
		}
	</script>