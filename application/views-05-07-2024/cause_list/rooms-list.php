<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
	<style>
		.pdf_button{
			background-color:red;
			color:white;
		}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?= $page_title ?>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box wrapper-box">
            			<div class="box-body">
							<div>
				              	<table id="dataTableRoomform" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableRoomform'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:8%;">S. No.</th>
											<th>Room Name</th>
											<th style="width: 15%;">Room Number</th>
				                            <th style="width:15%;">Action</th>
				                        </tr>
				                    </thead>

				                    <tbody>
				                    	<?php foreach($all_rooms_list as $room): ?>
				                    		<tr>
				                    			<td><?= $count ?></td>
				                    			<td><?= $room['room_name'] ?></td>
				                    			<td><?= $room['room_no'] ?></td>
				                    			<td>
				                    				<button class="btn btn-warning btn-sm edit_room_btn" id="<?= $room['id'] ?>" title="Edit"><i class="fa fa-edit"></i></button>
				                    				<button class="btn btn-danger btn-sm delete_room_btn" id="<?= $room['id'] ?>" title="Delete"><i class="fa fa-trash"></i></button>
				                    			</td>

				                    		</tr>
											<?php $count++; ?>
				                    	<?php endforeach; ?>
				                    </tbody>
				                </table>
	            			</div>
            			</div>
            		</div>
          		</div>
			</div>
		</section>
	</div>
	
	
	<div id="addRoomsModal" class="modal fade" role="dialog">
	  	<div class="modal-dialog">
	    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title" style="text-align: 	center;">Rooms Details</h4>
		      </div>
		      <div class="modal-body">
		        <?php include_once(dirname(dirname(__FILE__)) . '/templates/alerts.php');?>
				<?php echo form_open(null, array('class'=>'wfst','id'=>'form_add_rooms','enctype'=>"multipart/form-data")); ?>
	    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
	    			<input type="hidden" id="op_type" name="op_type" value="ADD_ROOM">
	    			<input type="hidden" id="hidden_id" name="hidden_id" value="">	
	    			<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_rooms'); ?>">	
	    		    	
	    		    	<fieldset>
	    		    		<legend><span class="fa fa-bank"></span> Add Room</legend>
	    		    		<div class="col-md-12">
	    		    			<div class="form-group col-md-6 col-sm-6 col-xs-12 required">
									<label class="control-label">Room Name</label>
									<input type="text" class="form-control" id="room_name" name="room_name"  autocomplete="off" maxlength="30"/>
								</div>

								<div class="form-group col-md-6 col-sm-6 col-xs-12 required">
									<label class="control-label">Room Number</label>
									<input type="text" class="form-control" id="room_no" name="room_no"  autocomplete="off" maxlength="10"/>
								</div>
							</div>
	    		    	</fieldset>

						<div class="box-footer with-border text-center">
				          	<div class="box-tools">
								  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
								  <button type="submit" class="btn btn-custom" id="btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
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
		
		$('#dataTableRoomform').DataTable({
			"searching": true,
			"sorting": true,
			"responsive": false,
			"autoWidth": true,
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
	                    addRoomsModalOpen();
	                }
	            }
	            
	        ],
			"columnDefs": [
				{
					"targets": ['_all'],
					"orderable": false,
					"sorting": false
				}
			],
		});

		// Add room
		function addRoomsModalOpen(){
			$('#addRoomsModal').modal({'backdrop': 'static', 'keyboard': 'false'});
			$('#form_add_rooms input[name="op_type"]').val("ADD_ROOM");
			$('#form_add_rooms').data('bootstrapValidator').resetForm(true);
		}

		// Edit Room
	    $(document).on('click', '.edit_room_btn',function(){
	    	var id = $(this).attr('id');

	    	$('#form_add_rooms').data('bootstrapValidator').resetForm(true);
	    	$('#form_add_rooms input[name="op_type"]').val("EDIT_ROOM");
	    	var oTable = $('#dataTableRoomform').dataTable();

	    	var row;
	    	if(event.target.tagName == "BUTTON")
				row = event.target.parentNode.parentNode;
			else if(event.target.tagName == "I")
				row = event.target.parentNode.parentNode.parentNode;


	    	var room_name = oTable.fnGetData(row)['1'];
	    	var room_no = oTable.fnGetData(row)['2'];

	    	// Set values in form
	    	$('#hidden_id').val(id);
	    	$('#room_name').val(room_name);
	    	$('#room_no').val(room_no);

	    	// Open modal
	    	$('#addRoomsModal').modal({'backdrop': 'static', 'keyboard': 'false'});
	    })

		// Add Room Form
		$('#form_add_rooms').bootstrapValidator({
			message: 'This value is not valid',
			submitButtons: 'button[type="submit"]',
			submitHandler: function(validator, form, submitButton) {
				$('#btn_submit').attr('disabled','disabled');
				var formData = $('#form_add_rooms').serialize();
				urls = base_url+"service/rooms_operation";
				console.log(formData);
				$.ajax({
					url : urls,
					type : 'POST',
					data: formData,
					cache: false,
					processData: false,
					success : function(response){
						try {
							
							var obj = JSON.parse(response);
							if (obj.status == false) {
								// Enable the submit button
								enable_submit_btn('btn_submit');

								swal({
									title: 'Error',
									text: obj.msg,
									type: 'error',
									html: true
								});
							}else if(obj.status === 'validationerror'){
								// Enable the submit button
								enable_submit_btn('btn_submit');

								swal({
									title: 'Validation Error',
									text: obj.msg,
									type: 'error',
									html: true
								});
							}else{
								//Reseting user form
								$('#form_add_rooms').trigger('reset');
								
								$('#form_add_rooms').data('bootstrapValidator').resetForm(true);//Reseting user form
								// Enable the submit button
								enable_submit_btn('btn_submit');

								toastr.success(obj.msg);
								window.location.href = base_url+"rooms-list";
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
				room_name: {							
					validators: {
						notEmpty: {
							message: 'Required'
						},
						regexp: {
							regexp: /^[a-zA-Z\s\']+$/,
							message: 'Only aplhabets, single quote and space are allowed.'
						}
					}
				},
				room_no: {							
					validators: {
						notEmpty: {
							message: 'Required'
						},
						regexp: {
							regexp: /^[0-9']+$/,
							message: 'Only numbers are allowed.'
						}
					}
				}
			}
	    });


		// Delete room
		$(document).on('click', '.delete_room_btn' ,function(){
			var id = $(this).attr('id');
			
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
						url: base_url+'service/delete_room',
						type: 'POST',
						data: {
							id: id,
							csrf_trans_token: $('#csrf_trans_token').val()
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
									$('#form_add_rooms').data('bootstrapValidator').resetForm(true);//Reseting user form
				                	toastr.success(obj.msg);
				                	window.location.href = base_url+"rooms-list";
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
		})

		// Function to enable the submit button
		function enable_submit_btn(button){
			$('#'+button).attr('disabled',false);
		}
		
	</script>