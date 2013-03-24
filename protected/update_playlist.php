<?php
	require_once("../includes/config.php");
	require_once("../includes/functions.php");
	require_once("../includes/classes/scrobbler.class.php");
	
	error_reporting(E_ALL);
	
	$tracks = getTracks(1);

	$fp = fopen(PLAYLIST_FILE, "w");
	fwrite($fp, $tracks[0]['filepath']."\n".PAUSE_FILE."\n");
	fclose($fp);
	
	mysql_query("DELETE FROM vote WHERE song_id='".mres($tracks[0]['id'])."'");
	mysql_query("INSERT INTO played VALUES('','".mres($tracks[0]['id'])."','".mres($tracks[0]['percent'])."','".mres($tracks[0]['num'])."',NOW());");
	
	sctransAPICall("loadplaylist");
	usleep(1000);
	sctransAPICall("nextsong");
	
	$time = time() + $tracks[0]['length'] - TRACK_END_CUT;
	$f = fopen(NEXTTIME_FILE,"w");
	fputs($f, $time);
	fclose($f);
	
	echo $tracks[0]['length'] - TRACK_END_CUT;
	
	if(SCROBBLE_SONGS)
	{	
		$scrobbler = new md_Scrobbler(LASTFM_USERNAME,LASTFM_PASSWORD);
		$scrobbler->add($tracks[0]['artist'],$tracks[0]['title'],'',$tracks[0]['length']);
		$scrobbler->submit();
	}
?>
