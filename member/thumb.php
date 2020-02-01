<?php
	isset($_GET['img']) or die('NO IMAGE');
	
	include "thumbnail.class.php";
	$thumb = new T10Thumbnail;
	$thumb->setMaxWidth(100); // lebar maksimal untuk setiap image adalah 80px
	$thumb->getThumbnail($_GET['img']);	// generate thumbnail image
?>
