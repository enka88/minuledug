<?php

include("../functions/fungsi_kalender.php");
include "../lib/config.php";


$width=200;////Or can be define percent $width="100%"; or mixed
$height=90;//Or can be define percent $height="50%"; or mixed

 $htmlcalendar=DisplayCalendar($CurrentMonth,"index.php","small",$width,$height,$dbhost,$dbuser,$dbpasswd,$dbname );

$tag .="<center>$htmlcalendar</center>";

?>