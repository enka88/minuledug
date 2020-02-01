<?php // banner tengah
//$tag .="<br>";
$query = "SELECT * FROM t_banner where status='13' and aktif='1' order by id desc limit 0,1";
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
			$tag .="<center><a href='$vlink' ><img src='$src' alt='$alt' border='0' width='467' height='80'></a><br></center>";
    	}

?>