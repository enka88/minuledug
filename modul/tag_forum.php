<?php
$tag .="<table>";
	$sql="select * from t_forum_isi,t_forum_balas where t_forum_isi.isi_id=t_forum_balas.isi_id order by t_forum_balas.balas_id desc limit 0,4";
	if(!$alan=mysql_query($sql)) die ("Pengambilan project");
	while ($row=mysql_fetch_array($alan)) {
		$tgl2=strtotime($row[isi_tgl]);
		$tgl2= date('j M Y - H:i',$tgl2);
		$isi2 =strip_tags($row[isi_body]);
		$isi2  =  substr($isi2, 0, 100)."...";
		$rw = mysql_query("SELECT * FROM t_member where userid='$row[userid]'") or die("Query failed");
		$rows = mysql_fetch_array($rw);
		$nama2 =$rows[nama];
		$tag .="<tr><td>Topik : <b><a href='index.php?id=view_replies&kd=$row[isi_id]&kode=$row[forum_id]' class='ver10' title='$lihat Forum'>$row[isi_judul]</a></b>
		<br><img src='../images/mod.gif' align='left'>&nbsp;
		<b>$nama2</b> - $tgl 
		<br>$isi2 <i><a href='index.php?id=view_replies&kd=$row[isi_id]&kode=$row[forum_id]' class='ver10' title='Lihat Forum'>Selengkapnya</a></i></td></tr>
		<tr><td height=2 background='../images/gris_user.gif'></td></tr>";
	}
$tag .="</table>";
?>