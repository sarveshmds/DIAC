		<div class="content-wrapper">
		<section class="content-header">
  				<h1>Dashboard</h1>
  				<ol class="breadcrumb">
    				<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
    				<li class="active"><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
  				</ol>
		</section>
			<section class="content">
			    <div class="row">
			       <div class="col-lg-3 col-xs-6">
			          	<div class="small-box bg-aqua">
			            	<div class="inner">
			              		<p>Total Case Registered</p>
			              		<h3><?= $total_cases['value'];?></h3>
			            	</div>
			            	<div class="icon">
			              		<i class="ion ion-bag"></i>
			            	</div>
			            	<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			          	</div>
			        </div>
			        <div class="col-lg-3 col-xs-6">
			          	<div class="small-box bg-green">
			            	<div class="inner">
			              		<p>Total Action Taken</p>
			              		<h3><?= $total_action['value'];?></h3>
			            	</div>
			            	<div class="icon">
			              		<i class="ion ion-stats-bars"></i>
			            	</div>
			            	<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			         	 </div>
			        </div>
			        <div class="col-lg-3 col-xs-6">
			          	<div class="small-box bg-yellow">
				            <div class="inner">
			              		<p>Total Cases in Current Month</p>
			              		<h3><?= $total_cases_month['value'];?></h3>
			            	</div>
				            <div class="icon">
				              	<i class="ion ion-person-add"></i>
				            </div>
			            	<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			          </div>
			        </div>
			        <!-- ./col -->
			        <div class="col-lg-3 col-xs-6">
			          	<div class="small-box bg-red">
			            	<div class="inner">
			              		<p>Total Action in Current Month</p>
			              		<h3><?= $total_cases_month['value'];?></h3>
			            	</div>
				            <div class="icon">
				              	<i class="ion ion-pie-graph"></i>
				            </div>
			            	<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
		</script>