	<style>
		.card {
		    border: 1px solid #732121;
		    border-radius: .25rem;
		    background-color: transparent;
		}
		.card .card-header {
		    padding: .5rem 1.5rem;
		    background: #732121;
		    color: #fff;
		    border-bottom: 0 solid;
		    border-color: #732121;
		    box-shadow: 0 2px 5px 1px #732121;
		    -webkit-box-shadow: 0 2px 5px 1px #732121;
		    -moz-box-shadow: 0 2px 5px 1px #732121;
		    -ms-box-shadow: 0 2px 5px 1px #732121;
		    -o-box-shadow: 0 2px 5px 1px #732121;
		}
		
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Dashboard<small>Control panel</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Dashboard</li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
			    <div class="col-xs-12 col-sm-12 col-md-12">
	        		<div class="box box-primary">
	            		<div class="box-body">
							<div class="col-lg-6">
				              	<table class="table table-bordered table-hover" id="dtblRoleContent">
					                <thead>
						                <tr>
						                  	<th> # </th>
						                 	<th>Role Name</th>
						                 	<th>No. of User</th>
						                </tr>
					                </thead>
					                <tbody>
					                	<?php 
					                		$i = 1;
					                		foreach($get_rolewise_user as $gru){
					                	?>
				                		<tr>
				                			<td><?php echo $i;?></td>
				                			<td><?php echo $gru['role_name'];?></td>
				                			<td><?php echo $gru['cnt'];?></td>
				                		</tr>
					                	<?php
					                		$i++;
					                		}
					                	?>
					                </tbody>
				              	</table>
	            			</div>
	            			<div class="col-lg-6">
	            				<div class="card">
	            					<div class="card-header">
		            					<div class="card-title">User Log History(<?php echo date('d-m-Y');?>) </div>
	            					</div>
	            					<div class="card-body" style="height: 460px;">
	            						<marquee direction="up" scrolldelay="200" style="height: 450px;">
	            							<?php 
						                		foreach($get_login_current_date as $glcd){
						                	?>
            								<div class="row" style="padding-top: 12px;">
        										<div class="col-md-2">
        											<span style="padding: 20px;">
        												<i class="fa fa-user-circle fa-2x user-icon"></i>
        											</span>
        										</div>
        										<div class="col-md-4" style="padding: 4px;">
        											<span><?php echo $glcd['user_display_name']; ?></span>
        										</div>
        										<div class="col-md-6" style="padding: 5px;">
    												<span><i aria-hidden="true" class="fa fa-clock-o"></i>
    												<?php echo $glcd['created_on']; ?></span>
        										</div>
            								</div>
            								<?php
					                			}
					                		?>
	            						</marquee>
	            					</div>
	            				</div>
	            			</div>
	            		</div>
	            	</div>
	          	</div> 
			</div>
		</section>
	</div>
	
	<script>
		/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE START */
		  history.pushState(null, null, document.URL);
		  window.addEventListener('popstate', function () {
		      history.pushState(null, null, document.URL);
		  });
		/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */
		$('#dtblRoleContent').DataTable();
	</script>
	
