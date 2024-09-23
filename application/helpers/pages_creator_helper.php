<?php
  	function create_controller_page($controller_name){
  		if (file_exists(APPPATH.'controllers/'.$controller_name.'.php')){
			return array('status'=>'exists','msg'=>'Controller Already Exists');
		}else{
			$controller = fopen(APPPATH.'controllers/'.$controller_name.'.php', "a")or die("Unable to open file!");
			$controller_content ="<?php defined('BASEPATH') OR exit('No direct script access allowed');
		  		class $controller_name extends CI_Controller  {
			  		public function __construct(){
			    		parent::__construct();
			    		
			    		# libraries
						\$this->load->library('form_validation');
						\$this->load->library('user_agent');
						
						# models
						\$this->load->model('getter_model');
						
						# views
						\$data['title'] = \$this->getter_model->get(null,'get_title');
						\$this->load->view('templates/header',\$data);
			   		}
		   		}";
  			fwrite($controller, "\n". $controller_content);
  			fclose($controller);
		}
	}
  	// Create Model
  	function create_model_page($model_name){
	  	if (file_exists(APPPATH.'models/'.$model_name.'.php')){
	  		return array('status'=>'exists','msg'=>'Model already Exist');
	  	}else{
			$model = fopen(APPPATH.'models/'.$model_name.'.php', "a") or die("Unable to open file!");
	   		$model_content ="<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		   	class ".$model_name." extends CI_Model{
		  		function __construct(){
		    		// Call the Model constructor
		    		parent::__construct();
		    		
		    		\$this->load->helper('date');

			        if (ENVIRONMENT == 'production') {
			            \$this->db->save_queries = FALSE;
			        }
			        date_default_timezone_set('Asia/Kolkata');
					\$date = date('Y-m-d H:i:s', time());
			        
			        \$this->role 		= \$this->session->userdata('role');
			        \$this->user_name 	= \$this->session->userdata('user_name');
		 	 	}
		  	}";
	  		fwrite($model, "\n". $model_content);
	  		fclose($model);
		}
	}
  	// Create View Page
	function create_view_page($view_name,$view_path){
		$file_path = APPPATH.'views/'.$view_path.'/'.$view_name.'.php';
		if (file_exists($file_path)){
	  		return array('status'=>'exists','msg'=>'View already Exist');
	  	}else{
	  		$dir_path= APPPATH.'views/'.$view_path;
	  		if(!is_dir($dir_path)){
				mkdir($dir_path,0777,true);
			}
			$page = fopen($file_path,"a") or die("Unable to open file!"); 
		  	$page_content ='<div class="content-wrapper">
				<section class="content-header">
		  				<h1>
		    				Dashboard
		    				<small>Control panel</small>
		  				</h1>
		  				<ol class="breadcrumb">
		    				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    				<li class="active">Dashboard</li>
		  				</ol>
				</section>
				<section class="content">
				    <div class="row">
				        <div class="col-lg-3 col-xs-6">
				          	<div class="small-box bg-aqua">
				            	<div class="inner">
				              		<h3>150</h3>
				              		<p>New Orders</p>
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
				              		<h3>53<sup style="font-size: 20px">%</sup></h3>
				              		<p>Bounce Rate</p>
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
					              	<h3>44</h3>
					              	<p>User Registrations</p>
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
				              		<h3>65</h3>
				              		<p>Unique Visitors</p>
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
				  window.addEventListener("popstate", function () {
				      history.pushState(null, null, document.URL);
				  });
				/*TO DISABLE BROWSER BACK BUTTON IN THIS PARTICULAR PAGE END */
			</script>';
		  	fwrite($page, "\n". $page_content);
		  	fclose($page);
		}
	}
  	