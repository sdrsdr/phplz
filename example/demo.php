<?php
require_once ('localize.php');
//lz_init($_REQUEST['lang']);
lz_init ('demo');
echo "this is a ".lz("test")."!\n";
$a=array ("some data","more data");//lz("some data") this forces string for translation
foreach ($a as $d) echo "data: ".lz($d)."\n";
echo str_replace(array("%data%","%result%"),array("important","creative"),lz("it is %data% to be %result%"))."\n";
// lz("for translation");
?>