<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>Competitive Section</title>
		<meta charset="utf-8">
		<meta name = "format-detection" content = "telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">      
	</head>
	<style>
	@page { size: a4 landscape; }
		* {padding: 0;margin: 5px;}
		body {font: 16px Courier New;color:#000000;border: 1px solid #0000;}

		.content img {display: inline;}
		#root{padding:15px;}
		
		.text-center{text-align:center;}
		.text-right{text-align:right;}
		.text-left{text-align:left;}
		.background-image:after {
		    content:'';
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
		
		/*.table {
		  border-collapse: collapse;
		  margin-top: 3%;
		  padding:5px;
		}*/
		
		table.TableCompetitive {
		 border-collapse: collapse;
		  margin-top: 3%;
		  padding:5px;
		  border: 1px solid black;
		}
		
		table.TableCompetitive td,th{
			border: 1px solid black;
		}
		
		tr, td, th{text-align:left; padding:0 5px;line-height: 16pt;font-weight: normal;}
		.body_content{
			margin-left:2%;
		}
	</style>	
	<body id="root">
		<div class="content">
			<h1 style="text-align: center;padding-top:0;"><u>Details of participants and programmes/items to be presented during 7th North East Youth Festival 2019</u></h1>
			<h3 style="text-align: center;padding-top: 5px;"><u>TAWANG, ARUNACHAL PRADESH, 10TH TO 12TH DECEMBER 2019</u></h3>
		</div>
		<div class="body_content">
			<div>
				<div>Name of the State:<span>Arunachal Pradesh</span></div>
			</div>
			<h4 class="text-center" style="margin-top:2%;"><u>Competitive Section</u></h4>
			<?php 	foreach($get_event_details as $evt): $i=1; ?>
			
				<table class="TableCompetitive" width="100%">
                	<thead>
                        <tr>
							<th rowspan="2">Sl No</th>
							<th rowspan="2">Item and allocated maximum</th>
							<th rowspan="2">Name of the item to be presented</th>
							<th rowspan="2">Duration of the item in minutes</th>
							<th colspan="4">Details of participants</th>
							<th colspan="3">For Office use only</th>
                        </tr>
                        <tr>
                        	<th>Sl. No</th>
                        	<th>Name of the Participant</th>
                        	<th>Sex</th>
                        	<th>Age</th>
                        	<th>Name of Hotel</th>
                        	<th>Room No.</th>
                        	<th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach($participant_details as $details): 
					if($evt['event_name'] == $details['event_name']):
					?>
                    	<tr>
                    		<td><?php echo $i; ?></td>
                    		<td><?php echo $details['event_name'] ?></td>
                    		<td><?php echo $details['item_name_presented'] ?></td>
                    		<td><?php echo $details['item_duration'] ?></td>
                    		<td><?php echo $i; ?></td>
                    		<td><?php echo $details['participants_name'] ?></td>
                    		<td><?php echo $details['gender'] ?></td>
                    		<td><?php echo $details['age'] ?></td>
                    		<td><?php echo $details['hotel_name'] ?></td>
                    		<td><?php echo $details['room_no'] ?></td>
                    		<td><?php echo $details['remarks'] ?></td>
                    	</tr>
            		<?php $i++;
	            	endif;
	            	endforeach; ?>
            	 </tbody>
            	</table>
	           <?php  endforeach;?>
		</div>
	</body>
</html>
	
