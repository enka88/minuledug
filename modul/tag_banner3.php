<?php // banner tengah khusus
//$tag .="<br>";
$query = "SELECT * FROM t_banner where status='15' and aktif='0' order by id desc limit 0,1";
$result = mysql_query($query) or die("Query failed banner");
    	while ($rows = mysql_fetch_array($result)) {
    		$src= "../images/banner/bn$rows[id].$rows[jenis]";
			$link=$rows[url];
			$vlink1=$rows[id];
			$alt=$rows[alt];		
			if (!empty($link)) {
				$vlink="$link' target='_blank";
			}
			else $vlink="#";   
			$tag .="<center><a href='$vlink' title='$rows[alt]' ><img src='$src' alt='$alt' border='0' width='467' height='70'></a><br></center>";
    	}

?>