<?php
function lz_init($lang){
	global $lz_data;
	global $lz_init_lang;
	$lz_init_lang=$lang;
	$lz_data=NULL;
	if (file_exists("localize.$lang.php")) {
		require_once("localize.$lang.php");
	}
}
/*
global $lz_data;
$lz_data=array('translate me'=>'foo','translate me too'=>'bar');

lz('translate me') returns 'foo'
lz('translate me too') returns 'bar'
*/
function lz($s){
	global $lz_data;
	if (is_array($lz_data) && array_key_exists($s,$lz_data)) return $lz_data[$s];
	return $s;
}

/*
	global $lz_init_lang;$lz_init_lang='en';
	$a=array (
		'test'=>'translate me'
		'test_lz'=>serialize(array(
			'en'=>array('translate me'=>'foo'),
			'bg'=>array('translate me'=>'bar'),
		))
	);
	
	$lzaa($a);
	
	$a['test'] will be set to 'foo'
*/
function lzaa(&$a){
	global  $lz_init_lang;
	foreach ($a as $k=>$v) {
		if (array_key_exists($k."_lz",$a)) {
			$lz_data=$a[$k."_lz"];
			if (!is_array($lz_data)) $lz_data=@unserialize($lz_data);
			if (is_array($lz_data) && array_key_exists($lz_init_lang,$lz_data)) {
				if (is_array($lz_data[$lz_init_lang]) && array_key_exists($a[$k],$lz_data[$lz_init_lang])) $a[$k]=$lz_data[$lz_init_lang][$a[$k]];
			}
		}
	}
}
?>