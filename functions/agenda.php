<html>
<head>
  <title>Agenda</title>
  <style>
A: {
	color : Black;
	text-decoration: underline;
}
A:HOVER {
	color : ff9900;
	text-decoration: none;
}
.ver10 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	text-decoration: none;
	color: #000000;
}
.ver10u {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	text-decoration: underline;
	color: #000000;
}
.tah11 {
	font-family: Tahoma;
	font-size: 11px;
	color: #000000;
	text-decoration: none;
	border: none;
}
td {
	font-family: Tahoma;
	font-size: 11px;
	color: #000000;
	text-decoration: none;
	border: none;
}
  </style>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body topmargin='3' leftmargin='0' rightmargin='0' marginwidth='0' marginheight='0' bgcolor="#ddecca">
<?php
$kode = $_GET['kode'];
include "koneksi.php";
  	$sql="select * from calendarevent where id='".mysql_real_escape_string($kode)."'";
	if(!$query=mysql_query($sql)) die ("Pengambilan gagal1 id $kode");
	$row=mysql_fetch_array($query);

echo "<center><table width='98%' BORDER=1 bordercolor=#B73E02 CELLPADDING=4 CELLSPACING=2>
<tr ><td bgcolor=#B73E02 >Acara </td><td bgcolor=#FFCEB6>".$row[eventTitle]."</td></tr>
<tr ><td bgcolor=#B73E02>Tanggal </td><td bgcolor=#FFCEB6>".date("d M Y",strtotime($row[date_start]))." s.d ".date("d M Y",strtotime($row[date_end]))."</td></tr>
<tr ><td bgcolor=#B73E02>Kegiatan </td><td bgcolor=#FFCEB6>".$row[EventDetail]."</td></tr>
</table>";
?>
</body>
</html>