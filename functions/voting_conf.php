<?php
$color_5p="#FF0000";
$color_10p="#FF0099";
$color_15p="#cccccc";
$color_20p="#9966ff";
$color_25p="#ccffcc";
$color_30p="#0000ff";
$color_35p="#ff6600";
$color_40p="#00ff33";
$color_45p="#ff9933";
$color_50p="#ffff00";
$color_55p="#ff0099";
$color_60p="#ffcc00";
$color_65p="#66ff33";
$color_70p="#ff0066";
$color_75p="#ccff00";
$color_80p="#99ff00";
$color_85p="#99cc33";
$color_90p="#33ff33";
$color_95p="#66cc00";
$color_100p="#00cc00";

// size for the poles
$pole_size="100";

function get_color($hold)
	{
	$hold2=round($hold/5) * 5;
	$test="color_".$hold2."p";
	$col=$GLOBALS[$test];
	return $col;
	}

?>
