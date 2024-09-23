<?php
	function page_status($sidebar,$menu_item){
		$i='';
		foreach($sidebar as $row){
			if($row['menu_name']==$menu_item)
				$i=$row['is_maintenance'];
		}
		return $i;
	}
	
?>