	<?php if($this->session->flashdata('warning')): ?>
		<div class="alert" style="background-color: #fcf8e3;border-color: #faf2cc;color: #8a6d3b;">
			<button type="button" class="close" data-dismiss = "alert">&times;</button>
			<?php echo $this->session->flashdata('warning'); ?>
		</div>
	<?php endif; ?>
	<?php if($this->session->flashdata('info')): ?>
		<div class="alert" style="background-color: #d9edf7;border-color: #bcdff1;color: #31708f;">
			<button type="button" class="close" data-dismiss = "alert">&times;</button>
			<?php echo $this->session->flashdata('info'); ?>
		</div>
	<?php endif; ?>
	<?php if($this->session->flashdata('success')): ?>
		<div class="alert" style="background-color: #dff0d8;border-color: #d0e9c6;color: #3c763d;">
			<button type="button" class="close" data-dismiss = "alert">&times;</button>
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php endif; ?>
	<?php if($this->session->flashdata('error')): ?>
		<div class="alert" style="background-color: #f2dede;border-color: #ebcccc;color: #a94442;">
			<button type="button" class="close" data-dismiss = "alert">&times;</button>
			<?php echo $this->session->flashdata('error'); ?>
		</div>
	<?php endif; ?>