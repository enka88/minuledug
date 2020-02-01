<?php
 $tag .='<form action="../functions/salam.php" methode="post"><script>
  			function clearText(thefield){
				if (thefield.defaultValue==thefield.value)
				thefield.value =""
			}
			</script><marquee direction="up" onMouseover="this.stop()" onMouseOut="this.start()"
	scrollAmount=2 scrollDelay=90 direction=up height=100 align="center">';
	$sql="select * from t_pesan order by id desc limit 0,10";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal pesan pinggir");
	while ($row=mysql_fetch_array($alan)) {
		$pengirim=strip_tags($row[pengirim]);
		$pesan=strip_tags($row[pesan]);
		$tag.= "<font class='tah10' ><b>$pengirim</b>
		<br>$row[waktu]<br>$pesan</font><br><br>";
	}
	$tag .="</marquee><br><input class='editbox' type=text name='pengirim' title='Nama Pengirim' onfocus=clearText(this) value='Nama' size='10' maxlength='10'>&nbsp;
	<input class='editbox' type=text name='pesan' onfocus=clearText(this) value='Pesan' maxlength='50' size='10' title='Pesan/Salam yang akan disampaikan'>&nbsp;<input class='button' name='submit' type=submit value='Kirim'></form>";

?>