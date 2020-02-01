<?php

	if (file_exists('lib/config.php')) {
		//header("Location:html/index.php");
        echo "<script>document.location.href = 'html/index.php';</script>";
	} else {
    	//header("Location:install/index.php");
        echo "<script>document.location.href = 'install/index.php';</script>";
	}

?>