	<div class="content-wrapper">
		<section class="content-header">
			<h1>Detail Statement<small>Of Participants</small></h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box">
            			<div class="box-body">
							<div class="col-lg-12">
				              	<table id="dataTableEntryform" class="table table-condensed table-striped table-bordered  display nowrap" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableTransaction'); ?>">
				                    <thead>
				                        <tr>
											<th>Sl No</th>
											<th>State</th>
											<th>Event</th>
											<th>Name of the participant</th>
											<th>Date of registration</th>
				                            <th>Action</th>
				                        </tr>
				                    </thead>
				                    <tbody>
				                    	<tr>
				                    		<td>1</td>
				                    		<td>Arunachal Pradesh</td>
				                    		<td>Instrumental</td>
				                    		<td>Siddhartha Mishra</td>
				                    		<td>25-10-2019</td>
				                    		<td>
				                    		<button type='button' class='btn bg-maroon btn-circle tooltipTable tooltipstered' align='center' onclick='' title='View' ><i class='fa fa-eye'></i></button>
				                    		<button type='button' class='btn bg-primary btn-circle tooltipTable tooltipstered' align='center' onclick='' title='Edit' ><i class='fa fa-pencil'></i></button>
				                    		<button type='button' class='btn bg-purple btn-circle tooltipTable tooltipstered' align='center' onclick='' title='Download' ><i class='fa fa-download'></i></button>
				                    		</td>
				                    	</tr>
				                    	<?php
					                    $i=1;
					                     foreach ($all_entry_form as $data) : ?>
					                    <tr>
					                      <td><?php echo $i ?> </td>
					                      <td><?php echo $data->state_name ?> </td>
										  <td><?php echo $data->event_name ?></td>
					                      <td><?php echo $data->participants_name ?></td>
					                      <td><?php echo $data->applyed_date ?></td>
					                      <td>
					                      	<button type='button' class='btn bg-maroon btn-circle tooltipTable tooltipstered' align='center' onclick='' title='View' ><i class='fa fa-eye'></i></button>
				                    		<button type='button' class='btn bg-primary btn-circle tooltipTable tooltipstered' align='center' onclick='' title='Edit' ><i class='fa fa-pencil'></i></button>
				                    		<button type='button' class='btn bg-purple btn-circle tooltipTable tooltipstered' align='center' onclick='' title='Download' ><i class='fa fa-download'></i></button>
				                    		</td>
					                    </tr>

					                    <?php $i++; endforeach;
					                     ?>
				                    </tbody>
				                </table>
	            			</div>
            			</div>
            		</div>
          		</div>
			</div>
		</section>
	</div>
	<script>
		var base_url = "<?php echo base_url(); ?>";
		var dataTableTransaction = $('#dataTableEntryform').dataTable({
			"sDom":"<'row'<'col-xs-2 btn_add'><'col-xs-4'i><'col-xs-2'l><'col-xs-4'f>r>t<'row'<'col-xs-6' <'row' >><'col-xs-6'p>>",
		});
		$("div.btn_add").html('<button class="btn btn-info tooltipTable btn-circle" title="Add" onclick="btnEnryForm()" ><i class="fa fa-plus" aria-hidden="true"></i></button>');
		function btnEnryForm(){
			window.location.href = base_url+"AddEntryFrom";
		}
	</script>