<?php
	$tag .='<script>
  			function clearText(thefield){
				if (thefield.defaultValue==thefield.value)
				thefield.value =""
			}
			</script>
				<div ><marquee direction="up" onMouseover="this.stop()" onMouseOut="this.start()"
	scrollAmount=2 scrollDelay=90 direction=up height=150 align="center">';
	$bln=date("m");
    //
	$sql="select * from t_siswa where mid(tgl_lahir,1,2)='".$bln."' order by tgl_lahir ";
	if(!$alan=mysql_query($sql)) die ("Pengambilan gagal pesan pinggir");
	while ($row=mysql_fetch_array($alan)) {
		$tag .= "<b>$row[nama]</b>
		<br>$row[kelas]<br>$row[tgl_lahir]<br><br>";
	}
	$tag .="</marquee></div>";
?>