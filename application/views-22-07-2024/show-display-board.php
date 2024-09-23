<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Display Board - DIAC</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.css" />

	<style>
		*{
			margin: 0px;
		}
		body{
			font-family: 'Poppins', sans-serif;
			background-color: #DCDEE9;
		}
		#sdb_wrapper{
			background-color: #DCDEE9;
			min-height: 100vh;
		}
		.container{
			width: 95%;
			margin: auto;
			max-height: 100vh;
		}

		@media(min-width: 1200px){
			.container{
				max-width: 1400px;
			}
		}

		.sdb_header{
			text-align: center;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 10px 20px 20px 20px;
			position: relative;
		}
		.sdb_logo{
			object-fit: contain;
			max-height: 100px;
		}

		.sdb_time_section{
			text-align: center;
			border-radius: 20px;
			padding: 6px 15px;
			color: white;
			background-color: #A9474A;
			box-shadow: 0px 0px 10px 0px white;
			position: absolute;
			right: 20px;
		}

		.sdb_time{
			font-size: 25px;
			font-family: monospace;
			font-weight: bolder;
		}

		.sdb_row{
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
		}
		.sdb_col{
			width: 19%;
			padding: 5px;
			margin-bottom: 10px;
		}

		.sdb_card{
			background-color: white;
			padding: 0px 20px;
			box-shadow: 0px 0px 10px 1px grey;
			border-radius: 20px;
			height: 100%;
			display: flex;
			align-items: center;
		}

		.sdb_card_body{
			flex: 1;
			min-height: 157px;
			height: 100%;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

		.sdb_card_room_no{
			margin-bottom: 5px;
		}
		.sdb_card_room_no, .sdb_card_case_no{
			font-size: 15px;
			display: flex;
			justify-content: space-between;
			font-weight: 500;
			align-items: flex-end;
		}

		.sdb_card_room_no span, .sdb_card_case_no span{
			font-size: 14px;
		}

		.sdb_card_room_no strong{
			background: #0d71b8;
			color: white;
			width: 30px;
			height: 30px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.sdb_card_case_no strong{
			background-color: #0d71b8;
			border-radius: 30px;
			padding: 1px 8px;
			color: white;
		}

		.sdb_arbitrator_name{
			margin: 5px auto;
			display: block;
			text-align: center;
			border: 1px solid #e7e7e7;
			border-radius: 30px;
			padding: 5px;
			font-size: 11px;
			font-weight: 600;
			background-color: #e5e5e5;
			color: #575757;
		}

		.btn{
			padding: 6px 15px;
			border-radius: 30px;
			border: none;
			box-shadow: 0px 2px 8px 1px #8f8f8f;
			font-weight: 600;
			margin: 5px auto;
			display: block;
		}
		.sdb_btn_success{
			background-color: #08a708;
			color: white;
		}
		.sdb_btn_danger{
			background-color: #e10303;
			color: white;
		}

		.sdb_card_top_col{
			margin-top: 10px;
		}
		.sdb_card_bottom_col{
			margin-bottom: 10px;
		}

		.fullScreenButton, .exitScreenButton{
			position: fixed;
			bottom: 50px;
			right: 50px;
			background-color: rgb(1 1 1 / 36%);
			border-radius: 50%;
			display: flex;
			width: 40px;
			height: 40px;
			box-shadow: 0px 1px 6px 1px #9f9f9f;
			color: white;
			align-items: center;
			justify-content: center;
			text-decoration: none;
		}
		.exitScreenButton{
			display: none;
		}

		/* 3 Columns Styling Start */
		.sdb_row_with_3_col .sdb_col{
			width: 32%;
		}
		@media(max-width: 920px){
			.sdb_row_with_3_col .sdb_col{
				width: 48%;
			}
		}
		@media(max-width: 640px){
			.sdb_row_with_3_col .sdb_col{
				width: 100%;
			}
		}

		.sdb_row_with_3_col .sdb_col .sdb_card_body{
			min-height: 178px;
		}

		.sdb_row_with_3_col .sdb_col .sdb_card_body .sdb_card_room_no span, .sdb_row_with_3_col .sdb_col .sdb_card_body .sdb_card_case_no span{
			font-size: 16px;
		}

		.sdb_row_with_3_col .sdb_col .sdb_card_body .sdb_card_top_col{
			margin-top: 15px;
		}
		.sdb_row_with_3_col .sdb_col .sdb_card_body .sdb_card_bottom_col{
			margin-bottom: 13px;
		}

		.sdb_row_with_3_col .sdb_col .sdb_card_body .btn{
			font-size: 15px;
		}
		/* 3 Columns Styling End */

		.sdb_row_with_4_col .sdb_col{
			width: 24%;
		}
		.sdb_row_with_4_col .sdb_col .sdb_card_body{
			min-height: 178px;
		}
		@media(max-width: 1140px){
			.sdb_row_with_4_col .sdb_col{
				width: 32%;
			}	
		}
		
		.sdb_row_with_5_col .sdb_col{
			width: 19%;
		}
		@media(max-width: 1400px){
			.sdb_row_with_5_col .sdb_col{
				width: 24%;
			}
		}
		@media(max-width: 1100px){
			.sdb_row_with_5_col .sdb_col{
				width: 32%;
			}
		}

		@media(max-width: 890px){
			.sdb_row_with_4_col .sdb_col{
				width: 48%;
			}
			.sdb_row_with_5_col .sdb_col{
				width: 48%;
			}
		}

		@media(max-width: 580px){
			.sdb_row_with_4_col .sdb_col{
				width: 100%;
			}
			.sdb_row_with_5_col .sdb_col{
				width: 100%;
			}
		}
	</style>
</head>
<body>
	
	<div id="sdb_wrapper">
		<main class="container" id="container">
			<header class="sdb_header">
				<img src="<?= base_url('public/custom/photos/logo.png') ?>" alt="DIAC Logo" class="sdb_logo">
				<div class="sdb_time_section">
					<h4 class="sdb_time" id="sdb_time">00:00:00 AM</h4>
					<h4 class="sdb_date"><?= date('D, d-M, Y') ?></h4>
				</div>
			</header>

			<div class="sdb_content_wrapper">
				<div class="sdb_row sdb_row_with_3_col">
				</div>
			</div>
			<a href="#" class="fullScreenButton"><span class="fa fa-expand"></span></a>
		</main>
	</div>
	
	<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>
	<script>

		// Setup the timer
		var x = setInterval(function() {
			var time = new Date(); 
            var hour = time.getHours(); 
            var min = time.getMinutes(); 
            var sec = time.getSeconds(); 
            am_pm = "AM"; 
  
            if (hour > 12) { 
                hour -= 12; 
                am_pm = "PM"; 
            } 
            if (hour == 0) { 
                hr = 12; 
                am_pm = "AM"; 
            } 
  
            hour = hour < 10 ? "0" + hour : hour; 
            min = min < 10 ? "0" + min : min; 
            sec = sec < 10 ? "0" + sec : sec; 
  
            var currentTime = hour + ":"  
                + min + ":" + sec +" "+ am_pm; 
  
            document.getElementById("sdb_time") 
                .innerHTML = currentTime; 
		}, 1000);

		// Setup the full screen mode ===============================================
		document.querySelector('.fullScreenButton').addEventListener('click',fullscreenMode);
		var elem = document.getElementById("sdb_wrapper");
		
		function fullscreenMode() {
			if (elem.requestFullscreen) {
				elem.requestFullscreen();
			}
			else if (elem.mozRequestFullScreen) {
				elem.mozRequestFullScreen();
			}
			else if (elem.webkitRequestFullscreen) {
				elem.webkitRequestFullscreen();
			}
			else if (elem.msRequestFullscreen) {
				elem.msRequestFullscreen();
			}
			
		}

		function loadDisplayBoardData(){
			// Load data using ajax request
			$.ajax({
				url: '<?php echo base_url(); ?>'+'main_controller/get_display_board_data',
				type: 'post',
				data: {

				},
				success: function(response){
					var response = JSON.parse(response);
					if(response.status == true){
						
						var display_data = response.data;
						var html_col = '';
						$.each(display_data, function(index, data){
							html_col += '<div class="sdb_col">\
									<div class="sdb_card">\
										<div class="sdb_card_body">\
											<div class="sdb_card_top_col">\
												<h4 class="sdb_card_room_no"><span>Room No.</span> <strong>'+ ((data.room_no)? data.room_no: 'N/A') + '</strong></h4>\
												<h4 class="sdb_card_case_no"><span>Case No.</span> '+ ((data.case_no)? '<strong>'+data.case_no+'</strong>': '-') +'</h4>\
												'+ ((data.arbitrator_name)? '<small class="sdb_arbitrator_name">'+ data.arbitrator_name+'</small>': '') +'\
											</div>\
											<div class="sdb_card_bottom_col">\
												'+ ((data.room_status == 'In Session')? '<button class="btn sdb_btn_success">In Session</button>': '<button class="btn sdb_btn_danger">Not In Session</button>')+'\
											</div>\
										</div>\
									</div>\
								</div>\
							';
						});
						
						$('.sdb_row').html(html_col);
					}
					else{
						toastr.error('Something went wrong. Please try again or contact support.');
					}
				},
				error: function(error){
					toastr.error('Server is not responding. Please try again or contact support.');
				}
			})
		}
		
		$(window).on('load', function(){
			loadDisplayBoardData();
		});

		// On every 10 sec, load display board data
		setInterval(function(){
			loadDisplayBoardData();
		}, 20000);


	</script>
</body>
</html>