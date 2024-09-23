<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>Entry Form</title>
		<meta charset="utf-8">
		<meta name = "format-detection" content = "telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">      
	</head>
	<style>
	@page { size: a4 portrait; }
		* {padding: 0;margin: 5px;}
		body {font: 16px Courier New;color:#000000;border: 1px solid #0000;}

		.content img {display: inline;}
		#root{padding:15px;}
		
		.text-center{text-align:center;}
		.text-right{text-align:right;}
		.text-left{text-align:left;}
		.background-image:after {
		    content:'';
		    background: url(<?php echo base_url();?>public/upload/logo/ap-agri-logo.png) no-repeat center center;
		    background-size: 60% 100%;
		    position: absolute;
		    top:0px;
		    left: 0px;
		    width:100%;
		    height:100%;
		    z-index:-1;
		    opacity: 0.1; /* Here is your opacity */
		}
		
		h1, h2, h3{color: #000000;background: none;text-decoration: none;font-weight: bold; margin-bottom:10px;}
		h1 { font-size: 13pt; }
		h2 { font-size: 11pt; margin-top: 1em; }
		h3 { font-size: 9pt; margin-top: 1em; }

		.content {line-height: 14pt;margin-bottom: 1em;	overflow: auto; width:100%;}
		
		hr {height: 0;border-top:solid 1px #ccc; border-bottom:0; border-left:0;  border-right:0;margin:10px 0;}

		ol, ul {margin-left: 15pt}

		strong{font-size:12pt;}
		address{font-size:10pt; line-height:10pt;text-align: justify;padding-top: 5px;width: 80%;}
		.small{font-size:8pt; line-height:10pt;}	
		
		
		@media all {
			.page-break	{ display: none; }
		}

		@media print {
			.page-break	{ display: block; page-break-before: always; }
			.noPrint{display:none;}
			thead{
				display:table-header-group;/*repeat table headers on each page*/
			}
			tbody{
				display:table-row-group;  
				
			}
		}
		p.ridge {border-style: ridge;height: 120px;width: 120px;}
		
		.dotted_underline {    
			border-bottom: 2px dotted #000;
			text-decoration: none;
		}
		table.table_one {
			width: 100%;
		  	border-bottom: 1px solid #000;
		  	margin-top: 5%;
		  	margin-bottom: 1em;
		}
		table.table_one th{
			width:40%;
		}
		.female_table{
			border-bottom: 1px solid #000;
		  	margin-bottom: 1em;
		}
		.table_two {
			width: 100%;
		}
		.table_two th{
			width:40%;
		}
		.male_table{
		  	margin-bottom: 1em;
		}
		.table_three {
			width: 100%;
		}
		.table_three th{
			width:40%;
		}
		
		tr, td, th{text-align:left; padding:0 5px;line-height: 16pt;font-weight: normal;}
		.body_content{
			margin-left:2%;
		}
	</style>	
	<body id="root">
		<div class="content">
			<h1 style="text-align: center;padding-top:0;">7th NORTH EAST YOUTH FESTIVAL 2019-20</h1>
			<h3 style="text-align: center;padding-top: 5px;">TAWANG, ARUNACHAL PRADESH, 10TH TO 12TH DECEMBER 2019</h3>
		</div>
		<h1 style="text-align: center;padding-top: 0;"><u>NUMBER OF PARTICIPANTS AND DETAIL OF CONTINGENT IN-CHARGES</u></h1>
		<div class="body_content">
			 <table class="table_one">
				<tr>
		    	   	<th>1.Total No. of Male Participants:</th>
		    	   	<td><?php echo $view_annexure_two['total_male_participants'];?></td>
		    	</tr>
				<tr>
		    	   <th>2.Total No. of female participants:</th>
		    	   <td><?php echo $view_annexure_two['total_female_participants'];?></td>
		    	</tr>
		    	
		    	<tr>
		    	   <th>3.Grand Total:</th>
		    	   <td><?php echo $view_annexure_two['grand_total'];?></td>
		    	</tr>
			</table>
			<div class="female_table">
				<table class="table_two">
					<h4 class="text-center"><u>Female Group In- charges</u></h4>
					<tr>
			    	   	<th>1.Name:</th>
			    	   	<td><?php echo $view_annexure_two['incharge_female_name'];?></td>
			    	</tr>
					<tr>
			    	   <th>2.Age:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_age'];?></td>
			    	</tr>
			    	
			    	<tr>
			    	   <th>3.Occupation:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_occupation'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>4.Designation:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_designation'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>5.E-mail ID:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_emailId'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>6.Mobile No:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_mobile'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>7.Address:</th>
			    	   <td><?php echo $view_annexure_two['incharge_female_address'];?></td>
			    	</tr>
				</table>
				<div>
				    <?php
						if(file_exists($view_annexure_two['incharge_female_signature'])){
							$image_path = $view_annexure_two['incharge_female_signature'];
						}else{
							$image_path = 'public/custom/photos/no_image.png';
						}
					?>
					<img src="<?php echo $image_path; ?>" style="width: 100px;margin-left:70%;"/>
					<div style="margin-left:73%;">Signature</div>
		    	</div>
	    	</div>
		    <div class="male_table">	
				<table class="table_three">
					<h4 class="text-center"><u>Male Group In- charges</u></h4>
					<tr style="padding-bottom: 10%">
			    	   	<th>1.Name:-</th>
			    	   	<td><?php echo $view_annexure_two['incharge_male_name'];?></td>
			    	</tr>
					<tr>
			    	   <th>2.Age:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_age'];?></td>
			    	</tr>
			    	
			    	<tr>
			    	   <th>3.Occupation:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_occupation'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>4.Designation:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_designation'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>5.E-mail ID:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_emailId'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>6.Mobile No:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_mobile'];?></td>
			    	</tr>
			    	<tr>
			    	   <th>7.Address:-</th>
			    	   <td><?php echo $view_annexure_two['incharge_male_address'];?></td>
			    	</tr>
				</table>
				<div>
				    <?php
						if(file_exists($view_annexure_two['incharge_male_signature'])){
							$image_path = $view_annexure_two['incharge_male_signature'];
						}else{
							$image_path = 'public/custom/photos/no_image.png';
						}
					?>
					<img src="<?php echo $image_path; ?>" style="width: 100px;margin-left:70%;"/>
					<div style="margin-left:73%;">Signature</div>
		    	</div>
		    	<!--<div>
		    		<div style="margin-left:60%;">Name of Sending Officer:<span><?php echo $view_annexure_two['incharge_male_address'];?></span></div>
		    		<div style="margin-left:60%;">Designation :<span><?php echo $view_annexure_two['incharge_male_address'];?></span></div>
		    	</div>-->
		    </div>
		</div>
	</body>
</html>
	
