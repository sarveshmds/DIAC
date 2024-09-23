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
				              	<table id="dataTableDisplayBoard" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableDisplayBoard'); ?>">
				                    <thead>
				                        <tr>
											<th width="10%">S. No.</th>
											<th width="15%">Room No.</th>
											<th width="">Case No.</th>
                                            <th>Arbitrator Name</th>
                                            <th width="12%">Status</th>
											<th width="10%">Action</th>
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

    <!-- Modal -->
    <div id="editDisplayBoardModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: 	center;">Edit Details</h4>
            </div>
            <div class="modal-body">
                
                <?php echo form_open(null, array('class'=>'wfst','id'=>'form_display_board','enctype'=>"multipart/form-data")); ?>
                    <input type="hidden" id="db_hidden_id" name="db_hidden_id" value="">	
                    <input type="hidden" id="hidden_room_id" name="hidden_room_id" value="">	
                    <input type="hidden" name="csrf_case_form_token" id="csrf_case_form_token" value="<?php echo generateToken('form_display_board'); ?>">	
                        
                        <fieldset>
                            <legend>Cause List</legend>
                            <div class="col-md-12">

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                    <label class="control-label">Room No:</label>
                                    <input type="text" class="form-control" id="room_no" name="room_no"  autocomplete="off" maxlength="20" readonly />
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                    <label class="control-label">Case No:</label>
                                    <input type="text" class="form-control str_to_uppercase" id="case_no" name="case_no"  autocomplete="off" maxlength="50"/>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                    <label class="control-label">Arbitrator's Name:</label>
                                    <input type="text" class="form-control" id="arbitrator_name" name="arbitrator_name"  autocomplete="off" maxlength="150"/>
                                </div>

                            </div>
                        </fieldset>

                        <div class="box-footer with-border text-center">
                            <div class="box-tools">
                                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                                <button type="submit" class="btn btn-custom" id="db_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
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
	</script>
	<script type="text/javascript">
        // Datatable initialization
        var dataTable = $('#dataTableDisplayBoard').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "responsive": true,
            "order": [],
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "ajax": {
                url: base_url+"service/get_display_board_list",
                type: 'POST',
                data: function(d){
                    d.csrf_trans_token = $('#csrf_trans_token').val();
                }
            },
            "columns": [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                    
                },
                {data: 'room_no'},
                {data: 'case_no'},
                {data: 'arbitrator_name'},
                {data: null,
                    render: function(data, type, row, meta){
                        return (data.room_status == null)? '<span class="badge badge-danger">Not In Session</span>': '<span class="badge badge-success">'+data.room_status+'</span>';
                    }
                },
                {data: null,
                    render: function(data, type, row, meta){
                        return '<button class="btn btn-warning btn-sm" onclick="btnEditForm(event)" data-tooltip="tooltip" title="Edit Display Board Case"><span class="fa fa-edit"></span></button> <button class="btn btn-primary btn-empty-room btn-sm" data-tooltip="tooltip" title="Remove case from display board" data-room-id="'+data.rt_id+'"><span class="fa fa-times"></span></button>';
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
            buttons: [
                {
                    text: '<span class="fa fa-paper-plane-o"></span> Hearings Today',
                    className: 'btn btn-custom',
                    init: function(api, node, config){
                        $(node).removeClass('dt-button');
                    },
                    action: function ( e, dt, node, config ) {
                        window.location.href = base_url+'hearings-today';
                    }
                }
            ],
            dom: "<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            drawCallback: function () {
                $('body').tooltip({ selector: '[data-tooltip="tooltip"]' });
            }
        });

        // Remove case from display board
        $(document).on('click', '.btn-empty-room', function(){
            var room_id = $(this).data('room-id');
            if(room_id){
                swal({
                    type: 'error',
                    title: 'Are you sure?',
                    text: 'You want to remove case from display board?.',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Remove",
                    cancelButtonText: "Cancel"
                }, function(isConfirm){
                    if(isConfirm){
                        $.ajax({
                            url: base_url+'service/remove_display_board_case',
                            type: 'POST',
                            data: {
                                room_id: room_id,
                                csrf_trans_token: $('#csrf_trans_token').val()
                            },
                            success: function(response){
                                try{
                                    var obj = JSON.parse(response);
                                    if (obj.status == true) {
                                        toastr.success(obj.msg);
                                        dataTableDisplayBoard = $('#dataTableDisplayBoard').DataTable();
                                        dataTableDisplayBoard.draw();
                                        dataTableDisplayBoard.clear();
                                    }
                                    else if(obj.status === 'validationerror'){
                                        swal({
                                            title: 'Validation Error',
                                            text: obj.msg,
                                            type: 'error',
                                            html: true
                                        });
                                    }
                                    else if(obj.status == false){
                                        toastr.error(obj.msg);
                                    }
                                    else{
                                        toastr.error('Something went wrong. Please try again.');
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
        })

        
        // Button edit form to open the modal with edit form
        function btnEditForm(event){

            // Change the submit button to edit
            $('#db_btn_submit').attr('disabled',false);

            // Reset the form
            $('#form_display_board').trigger('reset');

            // Get data table instance to get the data through row
            var oTable = $('#dataTableDisplayBoard').dataTable();
            var row;
            if(event.target.tagName == "BUTTON")
                row = event.target.parentNode.parentNode;
            else if(event.target.tagName == "SPAN")
                row = event.target.parentNode.parentNode.parentNode;

            var case_no = oTable.fnGetData(row)['case_no'];
            var arbitrator_name = oTable.fnGetData(row)['arbitrator_name'];
            var rt_id = oTable.fnGetData(row)['rt_id'];
            var room_no = oTable.fnGetData(row)['room_no'];

            $('#case_no').val(case_no);
            $('#arbitrator_name').val(arbitrator_name);
            $('#room_no').val(room_no); 
            $('#hidden_room_id').val(rt_id); 

            $('#editDisplayBoardModal').modal(
                {
                    backdrop: 'static',keyboard: false
                }
            );
        }

        
        // Edit display board list form
        $('#form_display_board').bootstrapValidator({
            message: 'This value is not valid',
            submitButtons: 'button[type="submit"]',
            submitHandler: function(validator, form, submitButton) {
                $('#cause_list_btn_submit').attr('disabled','disabled');
                var formData = new FormData(document.getElementById("form_display_board"));
                urls =base_url+"service/update_display_board";
                $.ajax({
                    url : urls,
                    method : 'POST',
                    data:formData,
                    contentType: false,
                    processData: false,
                    success : function(response){
                        // Enable the submit button
                        $('#db_btn_submit').attr('disabled', false);
                        // try {
                            var obj = JSON.parse(response);
                            if (obj.status == false) {
                                swal({
                                    title: 'Error',
                                    text: obj.msg,
                                    type: 'error',
                                    html: true
                                });
                            }else if(obj.status === 'validationerror'){
                                swal({
                                    title: 'Validation Error',
                                    text: obj.msg,
                                    type: 'error',
                                    html: true
                                });
                            }else{
                                //Reseting user form
                                $('#update_display_board').trigger('reset');
                                toastr.success(obj.msg);

                                // Redraw the datatable
                                dataTableDisplayBoard = $('#dataTableDisplayBoard').DataTable();
                                dataTableDisplayBoard.draw();
                                dataTableDisplayBoard.clear();

                                $('#editDisplayBoardModal').modal("hide");
                            }
                        // } catch (e) {
                        //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
                        // }
                    },error: function(err){
                        $('#db_btn_submit').attr('disabled', false);
                        toastr.error("unable to save");
                    }
                });
            },
            fields: {
                case_no: {							
                    validators: {
                        notEmpty: {
                            message: 'Required'
                        }
                    }
                },
                arbitrator_name: {							
                    validators: {
                        notEmpty: {
                            message: 'Required'
                        }
                    }
                }
                
            }
        })
    </script>
	