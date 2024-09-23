<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>Complaint Form</title>
		<meta charset="utf-8">
		<meta name = "format-detection" content = "telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">      
	</head>
	<style>
	@page { size: a4 portrait; }
		* {padding: 0;margin: 10px;}
		body {font: 16px Courier New;color:#000000;}

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
		h1 { font-size: 20pt; }
		h2 { font-size: 11pt; margin-top: 1em; }
		h3 { font-size: 15pt; margin-top: 1em; }

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
		table.TableReport td{
			border: 1px solid black;
			padding:2px;
		}
		table.TableReport th{
			border: 1px solid black;
			text-align: center;
			font-weight: bold;
		}
		table {
		  border-collapse: collapse;
		  margin-top: 1%;
		  padding:5px;
		}
		
		table.TableReport {
		 border-collapse: collapse;
		  margin-top: 5%;
		  padding:5px;
		  width:100%;
		  font-size: 20px;
		}
		
		.body_content{
			margin-left:2%;
		}
		.top_section{
			text-align: center;
			margin-top: 5%;
			font-size: 22px;	
		}
	</style>	
	<body id="root">
		<div class="content">
			<h1 style="text-align: center;padding-top:0;">Lokayukta: Odisha</h1>
		</div>
		<h3 style="text-align: center;padding-top: 0;"><u>Complaint Case No:- <?php echo $view_case_form['case_no'];?>/<?php echo date('Y'); ?></u></h3>
		<div class="body_content">
			<div class="top_section">
				<div>
					<p><span class="dotted_underline"><?php echo $view_case_form['complaint_name'];?></span>&nbsp; &nbsp;Complainant</p>
				</div>
				<div>
					<p>Vrs,</p>
				</div>
				<div>
					<p><span class="dotted_underline"><?php echo $view_case_form['public_name'];?></span> &nbsp; &nbsp; Opp. Party</p>
				</div>
			</div>
			<table class="TableReport">
                <thead>
                    <tr>
						<th style="width:10%;">Sl. No.</th>
						<th style="width:20%;">Date</th>
						<th style="width:50%;">Decision</th>
						<th style="width:20%;">Action Taken with date</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                		<td style="text-align: center;">1.</td>
                		<td style="text-align: center;"><?php echo $view_case_form['institution_date'];?></td>
                		<td style="text-align: left;">
                			<p style="line-height:30pt; font-size:16pt;">
                				The petition dated &nbsp;<span style="text-decoration: underline;"><?php echo $view_case_form['institution_date'];?></span>
                				of Complainant Sri/Smt.&nbsp;&nbsp;<span style="text-decoration: underline;"><?php echo $view_case_form['complaint_name'];?></span>
                				is registered as a complaint under the Odisha Lokayukta Act, 2014. Put up the Complaint
                				before the Hon'ble Bench of the Lokayukta for considerartion & kind orders. 
                			</p>
                			<div style="margin-left:60%;margin-top:10%;margin-bottom:5%;">Secretary</div>
                		</td>
                		<td style="text-align: center;"><?php echo $view_case_form['complaint_result'];?></td>
                	</tr>
                </tbody>
            </table>
		</div>
	</body>
</html>