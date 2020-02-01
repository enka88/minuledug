<?php
session_start();
include "../functions/random.php";

$random -> Set($_SESSION['kodeRandom']);
$random -> Stroke();
?>