<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<?php if ($this->session->userdata('user_logo')) : ?>
					<img src="<?php echo base_url($this->session->userdata('user_logo')); ?>" class="img-circle" alt="User Image">
				<?php else : ?>
					<img src="<?php echo base_url('public/upload/profile/defult.png'); ?>" class="img-circle" alt="User Image">
				<?php endif; ?>

			</div>
			<div class="pull-left info sm-online-box">
				<p class="sm-online-dn">
					<?= $this->session->userdata('user_display_name') ?>
				</p>
				<a href="#" class="sm-online-icon"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>

		<ul class="sidebar-menu" data-widget="tree">
			<?php foreach ($sidebar as $obj) {
				$dbmenuid = $obj['menu_id'];
				$dblinktext = $obj['menu_name'];
				$dblinkurl = $obj['resource_link'];
				$dbparentid = $obj['parent_id'];
				$dbslno = $obj['sl_no'];
				$dbhaschild = $obj['has_child'];
				$dbislastchild = $obj['is_last_child'];
				$dbiconclass = $obj['icon_class'];
				$state = $menu_group == $obj['menu_name'] ? "groupactive active" : "";
				$menuitemstate = ($menu_item == $obj['menu_name'] ? "active bold" : "");
				$dblinkurl = isset($dblinkurl) ? site_url($dblinkurl) : '#';
				if ($dbparentid == 0 && $dbhaschild) {
					echo "<li class=\"treeview $state\">			
							<a href='" . $dblinkurl . "'>
								<i class=\"$dbiconclass\"></i>&nbsp;&nbsp;<span>$dblinktext</span>
								<span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
							</a>
							<ul class=\"treeview-menu\">";
				} else if ($dbparentid == 0 && !$dbhaschild) {
					echo "<li class=\"$state\">
								<a href='" . $dblinkurl . "'>
									<i class=\"$dbiconclass\"></i>&nbsp;&nbsp;<span>$dblinktext</span>
								</a>
								
						</li>";
				} else if ($dbparentid != 0 && !$dbislastchild) {
					echo "<li class=\"$menuitemstate\">
							<a href='" . $dblinkurl . "'>
								<i class=\"$dbiconclass\"></i>&nbsp;&nbsp;$dblinktext
							</a>
						</li>";
				} else if ($dbparentid != 0 && $dbislastchild) {
					echo "<li class=\"$menuitemstate\">
							<a href='" . $dblinkurl . "'>
								<i class=\"$dbiconclass\"></i>&nbsp;&nbsp;$dblinktext
							</a>
						</li>
					</ul>
					</li>";
				}
			} ?>
		</ul>
	</section>
</aside>