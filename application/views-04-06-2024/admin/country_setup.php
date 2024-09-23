<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Country Master</h1>
      	<ol class="breadcrumb">
        	<li><i class="fa fa-cog"></i> Master Setup</li>
        	<li class="active"><i class="fa fa-flag"></i>Country Master</li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="UploadCountryTab" class="active"><a href="#tabUploadCountry" data-toggle='tab'>Upload Country</a></li>
						<li id="UploadCountryViewTab"><a href="#tabcountryView" data-toggle='tab'>Country View</a></li>
					</ul>
					<div class="tab-content no-padding">
						<!--1st tab-->
						<div class="chart tab-pane active" id="tabUploadCountry">
							<?php include_once(dirname(dirname(__FILE__)) . '/templates/alerts.php');?>
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<?php
							 		$output = '<p><b>Browse Excel</b></p>
							   				<p style="margin-left:20px;">
							   					Step 1. <a href="'.base_url().'public/excel_templates/country_master.xls">Download the template</a><br />
												Step 2. Fill the data in the downloaded excel file.<br />
												Step 3. Browse and upload.
											</p>';
									$output .= form_open_multipart('country-preview');
									$output .= '<div class="row" style="margin-left:20px;">';
									$output .= '<div class="col-lg-6 col-sm-6"><div class="form-group">';
									$data = array(
									    'name' => 'userfile',
									    'id' => 'userfile',
									    'class' => 'form-control filestyle',
									    'value' => '',
									    'data-icon' => 'false'
									);
									$output .= form_upload($data);
									$output .= '</div> </div>';
									$output .= '<div class="col-lg-1 col-sm-1"><div class="form-group">';
									$data = array(
									    'name' => 'importfile',
									    'id' => 'importfile-id',
									    'class' => 'btn btn-primary',
									    'value' => 'Preview',
									);
									$output .= form_submit($data, 'Preview');
									$output .= '</div></div></div>';
									$output .= form_close();
									echo $output;
								?>
								<?php
		            				if (isset($error) && !empty($error)) {
		            	 				foreach ($error as $key1 => $element1) {
		            	 	
		            	 				}
		            	 		?>
		            	 		<div id="feemsg"><h4><center><?=$element1['error_msg'];?></center></h4></div>   
		           				<?php
		            				}
		            			?>
								<div class="table-responsive">
									<?php
			            				if (isset($dataInfo) && !empty($dataInfo)) {
			            			?>
						    			<table class="table table-bordered table-hover">
						        			<thead>
						            			<tr>
						                			<th>Country Code</th>
						                			<th>Country Name</th>
						            			</tr>
						        			</thead>
						        			<tbody>
									       		<?php
						           					}
						           				?>
									            <?php
									            	$uploadFilePath='/upload/excel_files';
									           		if (isset($dataInfo) && !empty($dataInfo)) {
									            		$sl_no = 1;
									                		foreach ($dataInfo as $key => $element) {
									                			$uploadFilePath=$element['importFileName'];
									            ?>
									                    		<tr>
									                        		<td><?php echo $element['country_code']; ?></td> 
									                        		<td><?php echo $element['country_name']; ?></td> 
									                    		</tr>
									            <?php
									                    		$sl_no++;
									                		}
									            ?>
									         	<?php }else{
									                ?>
									              	<!--<tr>
									                    <td colspan="5">There is no data.</td>    
									                </tr>-->
									            <?php } ?>
						        			</tbody>
						    			</table>
								    <?php
								      	if (isset($dataInfo) && !empty($dataInfo)) {
								    ?>  	 
								    	<div class="box-footer with-border">
						          			<div class="box-tools pull-right">
						          	 			<input type="hidden" id="hideFile" value="<?=$uploadFilePath?>"/>
						              			<button type="submit" onclick=uploadcountrydata(); class="btn bg-olive btn-flat" id="btn_status"><i class='fa fa-paper-plane'></i> Upload</button>
						          			</div>
						          		</div>
		        	  				<?php 
		        	  					} 
		        	  				?> 
								</div>
							</div>
					    </div>
						<!--2nd tab-->
						<div class="chart tab-pane" id="tabcountryView">
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
				        			<div class="col-xs-12 col-sm-12 col-md-12">
						        		<div class="box box-primary">
						            		<div class="box-body">
												<div class="col-lg-12 col-xs-12">
									              	<table id="country_table" class="table table-condensed table-striped table-bordered  display nowrap">
									              		<input type="hidden" id="csrf_country_token" name="csrf_country_token" value="<?php echo generateToken('country_table'); ?>">
										                <thead>
											                <tr>
											                  	<th> # </th>
											                 	<th>Country Code</th>
											                 	<th>Country Name</th>
																<th>Status</th>
																<th style="width: 17%;">Action</th>
											                </tr>
										                </thead>
									              	</table>
						            			</div>
						            		</div>
						            	</div>
				          			</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
         	<div class="modal fade" id="manage_country_modal" role="dialog" >
				<div class="modal-dialog">
			      	<div class="modal-content">
			      		<div class='modal-header'>
				          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
				          	<h4 class='modal-title' style='text-align: center;'><span id="spanuser" >Add Country Code</span></h4>
				        </div>
	         			<?php echo form_open(null, array('id'=>'frm_country' ,'enctype'=>"multipart/form-data")); ?>
		            		<div class="box-body">
		            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
		            			<input type="hidden" id="op_type" name="op_type" value="add_country">
		            			<input type="hidden" name="csrf_op_country_token" value="<?php echo generateToken('frm_country'); ?>">
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >Country Code :</label>
										<input type="text" class="form-control" name="country_code" id="country_code" placeholder="Country Code" value="" required="" autocomplete="off">
									</div>
									<div class="form-group col-md-6">
										<label >Country Name :</label>
										<input type="text" class="form-control" name="country_name" id="country_name" placeholder="Country Name" value="" required="" autocomplete="off">
									</div>
		            			</div>
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >Status:</label>
										<select class="form-control" id="country_status" name="country_status">
						                 	<option value="">select</option>
						                  	<?php foreach ($this->config->item('status') as $key=>$status): ?>
												<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
											<?php endforeach; ?>
						                </select>
									</div>
		            			</div>
								
								<div class="box-footer with-border">
						          	<div class="box-tools pull-right">
						          		<button type="submit" class="btn bg-olive btn-flat" id="btn_submit"><i class='fa fa-paper-plane'></i> Add</button>
						          		<button type="button" class="btn btn-default" id="button_reset" onclick="form_reset();"><i class="fa fa-refresh"></i> Reset</button>
						          	</div>
						        </div>
							</div>
						<?php echo form_close(); ?>
					</div>
            	</div>
            </div>
      	</div>
	</section>
</div>
<script> base_url = "<?php echo base_url(); ?>"; </script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/country_setup.js"></script>