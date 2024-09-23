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
		body {font: 12px Courier New;color:#000000;border: 1px solid #0000;}
		
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

		/*table{width:100%;}
		td, th{padding:0 5px;}
		table.dashedBorder, .dashedBorder th, .dashedBorder td{border:solid 1px #999; border-collapse: collapse; padding:3px 10px; margin-bottom:20px;}
		*/strong{font-size:12pt;}
		address{font-size:9pt; line-height:10pt;text-align: justify;padding-top: 5px;width: 80%;}
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
		
		/*.tableedit {
		    border-spacing: 0;
			text-align: center;
		}*/
		 .dotted_underline {    
			border-bottom: 2px dotted #000;
			text-decoration: none;
		}
		.table {
		  border-collapse: collapse;
		  margin-top: 1%;
		  padding:5px;
		}
		
		table.TableReport {
		 border-collapse: collapse;
		  margin-top: 1%;
		  padding:5px;
		}
		
		table.TableReport td{
			border: 1px solid black;
			padding:2px;
		}
		table.TableReport th{
			border: 1px solid black;
			text-align: center;
			font-weight: bold;
		}
		
		table.table_form th{
			font-weight: bold;
		}
		.table-top {
		  border-collapse: collapse;
		  margin-top: 3%;
		  margin-left:-20px;
		}
		
		tr, td, th{text-align:left; padding:0 5px;line-height: 16pt;font-weight: normal;}
		.body_content{
			margin-left: 2%;
		}
		.note{
			margin-top:0;
			font-weight: bold;
		}
	</style>	
	<body id="root">
		<div class="content">
			<h1 style="text-align: center;padding-top:0;">7th NORTH EAST YOUTH FESTIVAL 2019-20</h1>
			<h3 style="text-align: center;padding-top: 5px;">TAWANG, ARUNACHAL PRADESH, 10TH TO 12TH DECEMBER 2019</h3>
		</div>
		<h1 style="text-align: center;padding-top: 0;"><u>DETAIL STATEMENT OF PARTICIPANTS</u></h1>
		<div class="body_content">
			<table class="TableReport">
                <thead>
                    <tr>
						<th >Sl No</th>
						<th >Events</th>
						<th>Name of Events</th>
						<th>Male</th>
						<th>Female</th>
						<th>Total</th>
                    </tr>
                </thead>
                <tbody>
                	<?php $sl=1; 
                	$male_cnt = 0;
                	$female_cnt = 0;
                	$total_cnt = 0;
                	
                	foreach($get_detail_participants as $evt){
                	?>
                	<tr>
                		<td><?php echo $sl ?></td>
                		<td><?php echo $evt['event_type'];?></td>
                		<td><?php echo $evt['event_name'];?></td>
                		<td class="text-right"><?php echo $evt['male_cnt'];?></td>
                		<td class="text-right"><?php echo $evt['female_cnt'];?></td>
                		<td class="text-right"><?php echo $evt['total'];?></td>
                	</tr>
                	<?php $sl++; 				                    	
                	$male_cnt += $evt['male_cnt'];
                	$female_cnt += $evt['female_cnt'];
                	$total_cnt += $evt['total'];
                	}?>
                </tbody>
                <tfoot>
                	<tr>
                		<td colspan="3" class="text-right"><b>Total Participants</b></td>
                		<td class="text-right"><?php echo $male_cnt;?></td>
                		<td class="text-right"><?php echo $female_cnt;?></td>
                		<td class="text-right"><?php echo $total_cnt;?></td>
                	</tr>
                </tfoot>
            </table>
            <div style="page-break-before: always;"></div>
			<table class="table_form" align="center">
				<tr>
		    	   	<th>i.    Name of Female group In-charge:</th>
		    	   	<td class="dotted_underline"><?php echo $view_annexure_one['incharge_female_name'];?></td>
		    	   	<th>Mo.No:</th>
		    	   	<td class="dotted_underline"><?php echo $view_annexure_one['incharge_female_mobile'];?></td>
		    	</tr>
				<tr>
		    	   <th>ii.    Name of Male group In-charge:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['incharge_male_name'];?></td> 
		    	   <th>Mo.No:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['incharge_male_mobile'];?></td>
		    	</tr>
		    	<tr>
		    	   <th>Name of the train/Bus:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['train_bus_name'];?></td>
		    	</tr>
		    	<tr>
		    	   <th>Train/Bus No:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['train_bus_no'];?></td>
		    	</tr>
		    	<tr>
		    	   <th>Date of Journey:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['journey_date'];?></td>
		    	</tr>
		    	<tr>
		    	   <th>Schedule Departure:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['schedule_time'];?></td>
		    	</tr>
		    	<tr>
		    	   	<th>Arrival at GHY(Date & Time):</th>
		    		<td class="dotted_underline"><?php echo $view_annexure_one['arrival_date'];?>      <?php echo $view_annexure_one['arrival_time'];?></td>
		    		<!--<td class="dotted_underline"><?php echo $view_annexure_one['arrival_time'];?></td>-->
		    	</tr>
		    	<tr>
		    	   <th>Name of sending/forwarding officer:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['sending_officer'];?></td>
		    	</tr>
		    	<tr>
		    	   <th>Designation:</th>
		    	   <td class="dotted_underline"><?php echo $view_annexure_one['designation'];?></td>
		    	</tr>
			</table>
			
			<div style="margin-top:5%;">
			    <?php
					if(file_exists($view_annexure_one['signature'])){
						$image_path = $view_annexure_one['signature'];
					}else{
						$image_path = 'public/custom/photos/no_image.png';
					}
				?>
				<img src="<?php echo $image_path; ?>" style="width: 100px;margin-left:70%;"/>
				<div style="margin-left:70%;">Signature with Seal</div>
	    	</div>
			
		</div>
	</body>
</html>
	
