<?php
	require('../lib/config.php');
	// Buat database connection.
	if(!$db= @mysql_connect("$dbhost", "$dbuser", "$dbpasswd"))
 		die('<font size=+1>An Error Occured</font><hr>$nmsekolah gagal koneksi dengan server <BR>Silahkah rubah variabel $dbhost, $dbuser, dan $dbpasswd ');
	if(!@mysql_select_db("$dbname",$db))
		die ("<font>Database belum ada </font>");
?>