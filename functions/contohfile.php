<?php
session_start();
echo'
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contoh file</title>
</head>

<body>';

if (isset($_SESSION['Admin'])) {

  	$dread = file("../modul/tag_info.php");
	for ($i=0; $i <= count($dread); $i++) {
		$output .= $dread[$i];
	}
		echo "<b> Contoh File Script</b><br><textarea name=nfile cols=80 rows=20>$output</textarea>";
  }
 else {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
 }
?>
</body>
</html>
