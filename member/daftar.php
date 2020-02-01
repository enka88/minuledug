<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Member</title>
<style type="text/css">
body { /* background gambar */

	margin-top: 0px;
	color:#666666;
	background: #fff url("back.jpg") repeat-x;
}
#konten {   /* lebar layout web member */
	width:800px;						
	margin-left: auto;
	margin-right: auto;
	background-color:#FFFFFF;			
}
td {
font-family:Arial, Helvetica, sans-serif;
font-size: 12px;
}
</style>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />

<script type="text/javascript" src="js/jquery.js"></script>

</head>
<body style="margin-top: 0px;background:#fff url('back.jpg') repeat-x;">
<div id="konten">
	<div id="isi" >
<?php
define("CMSBalitbang",1);
include ("../functions/koneksi.php");
include ("../functions/member_daftar.php");

	$id=$_GET['id'];
	if ($id=='') $id=$_POST['id'];
	switch ($id) {
		case "lupa"; //------------daftar member
			$isi .= lupa();
			break;
		case "lupasim"; //------------daftar member
			$isi .= lupasim();
			break;
		default: //------------daftar member
			$isi .= daftar();
			break;
	}		
	echo $isi; 

?>
	
	<div id="bawah"> <?php echo $webhost; ?> .Website engine's code is copyright © 2011 Tim Balitbang Depdiknas versi 3.5</div>
	</div>
</div>

</body>
</html>
