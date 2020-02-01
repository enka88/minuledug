<?php

/**
 * @author alanrm82
 * @copyright 2011
 */
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.{filter}");
}
  function filter($text, $filter = "lcase ucase num space symbol enter tab", $exclude = "") {
		$option = explode(" ", $filter);
		$filter = "";
		$len    = count($option);
		for($i=0; $i<$len; $i++) {
			switch($option[$i]) {
			case "lcase" :
				$filter .= "abcdefghijklmnopqrstuvwxyz";
				break;
			case "ucase" :
				$filter .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
			case "num" : 
				$filter .= "1234567890-.";
				break;
			case "space" :
				$filter .= " ";
				break;
			case "symbol" :
				$filter .= "!@#$%^&*()_+=[]{}:;,./?'".'"';
				break;
			case "enter" :
				$filter .= chr(13).chr(10);
				break;
			case "tab" :
				$filter .= "	";
				break;
			default :
				$filter .= $option[$i];
				break;
			}
		}
		$len = strlen($exclude);
		for($i=0; $i<$len; $i++) {
			$filter = str_replace($exclude[$i],"",$filter);
		}
		$i = 0;
		while($i < strlen($text)) {
			if(strpos($filter, $text[$i]) === false) {
				$text = str_replace($text[$i], "", $text);
			} else {
				$i++;
			}
		}
		return $text;
	}

?>