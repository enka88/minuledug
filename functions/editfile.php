<?php
session_start();
if (!isset($_SESSION['Admin'])) {
    echo "<h1>Permission Denied</h1>You don't have permission to access the this page.";
    exit;
}
    $save=$_POST['save'];
    $file=$_GET['file'];

    
	if ($save=='simpan') {
	    $nfile=$_POST['nfile'];
        $file=$_POST['file'];
        $nmfile=$_POST['nmfile'];
		$dwrite = fopen("../modul/tag_".$file.".php", "w");
		$nfile = stripslashes($nfile);
		fputs ($dwrite, $nfile);
		fclose ($dwrite);
		echo "File sudah disimpan....Silahkan tutup jendela ini";
		//header("Location: ../admin/admin.php?mode=konf&kd=berhasil");
	}
	else {
  	$dread = file("../modul/tag_".$file.".php");
	for ($i=0; $i <= count($dread); $i++) {
		$output .= $dread[$i];
	}
	echo "<form action='editfile.php' method=post >File Name : <input type=text name=nmfile value='tag_".$file.".php'> Jarngan diganti<br><textarea name=nfile cols=80 rows=20>$output</textarea><br><input type=hidden name=file value='$file' >
	<input type=submit value='Simpan' ><input type=hidden name='save' value='simpan' ></form>";
	
	}

?>