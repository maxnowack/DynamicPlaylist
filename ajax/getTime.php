<?php
	require_once("../includes/config.php");
	$f = fopen(NEXTTIME_FILE,"r");
	echo fread($f,1024)-time();
	fclose($f);
?>