<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Gram Panchayat Master</h1>
      	<ol class="breadcrumb">
        	<li><i class="fa fa-cog"></i> Master Setup</li>
        	<li class="active"><i class="fa fa-info"></i>Gp Master</li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="UploadGpTab" class="active"><a href="#tabUploadGp" data-toggle='tab'>Upload Gram Panchayat List</a></li>
						<li id="UploadGpViewTab"><a href="#tabGpView" data-toggle='tab'>Gram Panchayat View</a></li>
					</ul>
					<div class="tab-content no-padding">
						<!--1st tab-->
						<div class="chart tab-pane active" id="tabUploadGp">
							<?php include_once(dirname(dirname(__FILE__)) . '/templates/alerts.php');?>
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<?php
							 		$output = '<p><b>Browse Excel</b></p>
							   				<p style="margin-left:20px;">
							   					Step 1. <a href="'.base_url().'public/excel_templates/gp_master.xls">Download the template</a><br />
												Step 2. Fill the data in the downloaded excel file.<br />
												Step 3. Browse and upload.
											</p>';
									$output .= form_open_multipart('gp-preview');
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
						                			<th>Sl No</th>
						                			<th>Gp Code</th>
						                			<th>Gp Name</th>
						                			<th>Block Code</th>
						                			<th>District Census Code</th>
						                			<th>District Code</th>
						                			<th>State Code</th>
						                			<th>Country Code</th>
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
									                        		<td><?php echo $sl_no; ?></td> 
									                        		<td><?php echo $element['gp_code']; ?></td> 
									                        		<td><?php echo $element['gp_name']; ?></td> 
									                        		<td><?php echo $element['block_code']; ?></td> 
									                        		<td><?php echo $element['district_census_code']; ?></td> 
									                        		<td><?php echo $element['district_code']; ?></td> 
									                        		<td><?php echo $element['state_code']; ?></td> 
									                        		<td><?php echo $element['country_code']; ?></td> 
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
						              			<button type="submit" onclick=uploadGpdata(); class="btn bg-olive btn-flat" id="btn_status"><i class='fa fa-paper-plane'></i> Upload</button>
						          			</div>
						          		</div>
		        	  				<?php 
		        	  					} 
		        	  				?> 
								</div>
							</div>
					    </div>
						<!--2nd tab-->
						<div class="chart tab-pane" id="tabGpView">
				        	<div class="col-xs-12 col-sm-12 col-md-12">
				        		<div class="box box-primary">
				            		<div class="box-body">
				            			<div class="col-md-3" style="padding-bottom: 2%">
					            			<label class="control-label">District:</label>
											<select class="form-control cmb_dist" name="cmb_district_filter" id="cmb_district_filter" >
												<option value="">Select</option>
												<?php if(!empty($all_dist_list)):
													foreach($all_dist_list as $adl):?>
													<option value="<?php echo $adl['district_code']?>"><?php echo $adl['district_name']?></option>	
												<?php endforeach;endif; ?>
											</select>
					            		</div>
					            		<div class="col-md-3" style="padding-bottom: 2%">
					            			<label class="control-label">Block:</label>
											<select class="form-control cmb_block" name="cmb_block_filter" id="cmb_block_filter" >
												<option value="">Select</option>
											</select>
					            		</div>
					            		<div class="box-tools" style="padding-top: 25px;">
							          		<button type="button" class="btn btn-info" id="btn_search"><i class="fa fa-filter"></i></button>
							          		<button type="reset" class="btn btn-default" id="btn_filter_reset"> <i class="fa fa-refresh"></i></button>
							          	</div>
										<div class="col-lg-12">
							              	<table id="dtblGp" class="table table-bordered table-hover">
							              	<input type="hidden" id="csrf_Gp_token"  value="<?php echo generateToken('dtblGp'); ?>">
								                <thead>
									                <tr>
									                  	<th> # </th>
									                 	<th>Gp Name</th>
									                 	<th>Gp Code</th>
														<th>Block Name</th>
														<th>District Census Code</th>
														<th>District Name</th>
														<th>State Name</th>
														<th>Country Name</th>
														<th>Status</th>
														<th>Action</th>
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
          	
         	<div class="modal fade" id="manage_gp_modal" role="dialog" >
				<div class="modal-dialog">
			      	<div class="modal-content">
			      		<div class='modal-header'>
				          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
				          	<h4 class='modal-title' style='text-align: center;'><span id="spangp" >Add Gram Panchayat</span></h4>
				        </div>
	         			<?php echo form_open(null, array('id'=>'form_gp','name'=>'form_gp','enctype'=>"multipart/form-data")); ?>
		            		<div class="box-body">
		            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
		            			<input type="hidden" id="op_type" name="op_type" value="add_gp_master">
		            			<input type="hidden" name="csrf_op_circle_token" value="<?php echo generateToken('form_circle'); ?>">
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >Gram Panchayat Code :</label>
										<input type="text" class="form-control" name="gp_code" id="gp_code" placeholder="Gp Code" value="" required="" autocomplete="off">
									</div>
									<div class="form-group col-md-6">
										<label >Gram Panchayat Name :</label>
										<input type="text" class="form-control" name="gp_name" id="gp_name" placeholder="Gp Name" value="" required="" autocomplete="off">
									</div>
		            			</div>
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >Country Name :</label>
										<select class="form-control" id="cmb_country" name="cmb_country"></select>
									</div>
		            				<div class="form-group col-md-6">
										<label >State Name:</label>
										<select class="form-control" id="cmb_state" name="cmb_state">
											<option value=''>Select</option>
										</select>
									</div>
		            			</div>
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >District Name:</label>
										<select class="form-control cmb_dist" id="cmb_dist" name="cmb_dist">
											<option value=''>Select</option>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label >District Census Code :</label>
										<input type="text" class="form-control" id="district_census_code" name="district_census_code" readonly/>
									</div>
		            			</div>
		            			<div class="col-md-12">
		            				<div class="form-group col-md-6">
										<label >Block Name:</label>
										<select class="form-control cmb_block" id="cmb_block" name="cmb_block">
											<option value=''>Select</option>
										</select>
									</div>
		            				<div class="form-group col-md-6">
										<label >Status:</label>
										<select class="form-control" id="gp_status" name="gp_status">
											<option value="">Select</option>
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
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/gp_master.js"></script>