<?php // banner kiri atau kanan
	$tag .="<div align='center'>";
		$query = "SELECT * FROM t_banner where status='12' and aktif='1' order by id desc limit 0,2";
    	$result = mysql_query($query) or die("Query failed banner");
    	while ($rows = mysql_fetch_array($result)) {
    		$src= "../images/banner/bn$rows[id].$rows[jenis]";
			$link=$rows[url];
			$vlink1=$rows[id];
			$alt=$rows[alt];		
			if (!empty($link)) {
				$vlink="../functions/visit.php?id=$vlink1' target='_blank";
			}
			else $vlink="#";   
			$tag .="<a href='$vlink' ><img src='$src' alt='$alt' border='0' width='500' height='100'></a>";
    	}
		$tag .="</div>";

?>