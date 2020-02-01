<?php

 	$data="select user_id from t_staf ";
	if(!$query=mysql_query($data)) die ("Pengambilan gagal2 profil ");
	$i=0;
	while($row=mysql_fetch_array($query)) {
		$guru[$i]=$row[user_id];
		$i++;
	}
 	$n = rand(1,$i-1);
	$sql="select user_id,nip,tgl_lahir,alamat,pelajaran,email,telp,nama from t_staf where user_id='".$guru[$n]."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 staf a $n");
	$row=mysql_fetch_array($query);
	$file ="../images/staf/".$row['nip'].".jpg";
		$gbr="<img src='../images/noava.jpg' border='1' hspace='5' align='left' >";
		if (file_exists($file)) {
	        $gbr="<img src='../images/staf/$row[nip].jpg' width='100' height='120' hspace='5' border=1  align='left'>";
		}
$tag .="$gbr Saya bernama $row[nama] , NIP $row[nip]. Saya tinggal di $row[alamat] Telp.$row[telp]
			. Saya lahir pada tanggal $row[tgl_lahir]. Saya mengajar pelajaran $row[pelajaran]. E : $row[e]";
?>