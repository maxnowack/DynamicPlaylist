<?php
	require_once("../includes/config.php");
	require_once("../includes/functions.php");
	
	if(isset($_POST['id']) && $_POST['id']!="" && !isIPBanned($_SERVER['REMOTE_ADDR']) && !songBlocked($_POST['id'])) {
		$ip_id = saveIP($_SERVER['REMOTE_ADDR']);
		$qry = mysql_query("INSERT INTO vote VALUES('', '".mres($_POST['id'])."', '".$ip_id."', NOW())");
	}
	
	$tracks = getTracks(5);
	
	if(isIPBanned($_SERVER['REMOTE_ADDR'])) echo "<p class=\"blocked\">Du kannst erst beim n&auml;chsten Song wieder voten.</p>\n"; 
	foreach($tracks as $track)
	{
		echo "<div class=\"track\" id=\"track_".$track['id']."\" onclick=\"updateVotes(".$track['id'].");\">\n";
		echo '	<span class="percent">'.$track['percent'].'%</span>'."\n";
		echo '	<span class="track">'.$track['artist'].' - '.$track['title'].'</span>'."\n";
		echo "</div>\n";
	}
?>