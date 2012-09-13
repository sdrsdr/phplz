<?php
require_once ('localize.php');

/*
lz_init($lang) will load localization data from "localize.$lang.php" file
maintained with localize.php tool. To create new localize.$lang.php just 
edit and copy/rename localize.proto.php from phplz distribution
*/
lz_init ('demo');

/*
now you can use lz function to make translations and mark strings for translation
which localize.php will update to localize.*.php files.
*/
echo "this is a ".lz("test")."!\n";
$a=array ("some data","more data");//lz("some data") this forces string for translation
foreach ($a as $d) echo "data: ".lz($d)."\n";
echo str_replace(array("%data%","%result%"),array("important","creative"),lz("it is %data% to be %result%"))."\n";
// lz("for translation");

/*db result localization:
to localize database data you need to have a localization column for every column you would like to localize 
that must have the same name as the one for localization but with suffix _lz. So to localize column 'prodname' you need
column 'prodname_lz' containing serialized assoc aray with lzdata for evey loacalization you need:
*/

	$a=array (
		'prodname'=>'translate me',
		'prodname_lz'=>serialize(array(
			'demo'=>array('translate me'=>'foo'),
			'bg'=>array('translate me'=>'bar'),
		)),
	);
	
	lzaa ($a);
	
	echo "Localized db string: ".$a['prodname']."\n";
	
?>