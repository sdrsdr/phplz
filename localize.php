<?php
function lz_init($lang){
	global $lz_data;
	$lz_data=NULL;
	if (file_exists("localize.$lang.php")) {
		require_once("localize.$lang.php");
	}
}

function lz($s){
	global $lz_data;
	if (is_array($lz_data) && array_key_exists($s,$lz_data)) return $lz_data[$s];
	return $s;
}
?>