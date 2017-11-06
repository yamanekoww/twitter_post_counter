<?php
// ini_set("display_errors", On);
// error_reporting(E_ALL);

	$filename = "count.txt";
	$num = @file_get_contents($filename,true);

	if($num == ""){
		$num = 0;
	}
	echo $num;

?>
