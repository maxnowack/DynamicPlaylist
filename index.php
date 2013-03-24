<? echo '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

'; ?>
	<head>
			<title>Vote your Song</title>
			<link rel="stylesheet" type="text/css" href="style.css" />
	</head>

	<body unselectable="on">
	<?php
	require_once("includes/config.php");
	require_once("includes/functions.php");
	
	if(isset($_GET['showall']) && $_GET['showall']==1)
	{
	
	}
	else
	{
		$f = fopen(NEXTTIME_FILE,"r");
		$time = fread($f,1024)-time();
		fclose($f);
		
		$minutes = floor($time/60);
		$minutes = ($minutes>0 ? ($minutes<=9 ? "0".$minutes : $minutes) : "00");
		$seconds = $time % 60;
		$seconds = ($seconds>0 ? ($seconds<=9 ? "0".$seconds : $seconds) : "00");
		
		echo "<div id=\"next_track\">";
		if($minutes + 10 < 0)
		{
			echo "Playlist zur Zeit nicht Online!";
		}
		else
		{
			echo "N&auml;chster Track in ".$minutes.":".$seconds;
		}
		echo "</div>";
		echo "<div id=\"timestamp\">$time</div>";
		echo "<div id=\"tracks\">\n";
		
		$tracks = getTracks(5);
		
		if(isIPBanned($_SERVER['REMOTE_ADDR'])) echo "<p class=\"blocked\">Du kannst erst beim n&auml;chsten Song wieder voten.</p>\n";
		foreach($tracks as $track)
		{
			echo "<div class=\"track\" id=\"track_".$track['id']."\" onclick=\"updateVotes(".$track['id'].");\">\n";
			echo '	<span class="percent">'.$track['percent'].'%</span>'."\n";
			echo '	<span class="track">'.$track['artist'].' - '.$track['title'].'</span>'."\n";
			echo "</div>\n";
		}
		echo "</div>\n";
		
		echo '<div id="search">
			<div id="search_head">
				<p id="search_headline">Suche nach einem Titel</p>
				<div id="search_form">
					<form action="javascript:void(0);" method="get" onsubmit="searchTitle(this.search.value,document.getElementById(\'search_result\'));">
						<input type="text" name="search" onkeyup="searchTitle(this.value,document.getElementById(\'search_result\'));" />&nbsp;
						<button type="submit">Suchen</button>
					</form>
				</div>
			</div>
			<div id="search_result">
				<p id="search_none" class="search">Keine Titel gefunden</p>
			</div>
		</div>';
		
		echo '<div id="help_icon" onmouseover="info(document.getElementById(\'help_text\'),this,true)" onmouseout="info(document.getElementById(\'help_text\'),this,false)"><img src="img/help.png" border="0" alt="Hilfe" /></div>';
		echo '<div id="help_text"><p>Um f&uuml;r einen Song abzustimmen, klicken Sie einfach auf den gew&uuml;nschten Song.</p><p>Wenn Sie f&uuml;r einen anderen Song als die 5 ersten Songs abstimmen m&ouml;chten, geben Sie Interpret oder Titel in dem Suchfeld ein. Mit einem Klick auf Suchen werden Ihnen alle Songs, die mit den Suchbegriff &uuml;bereinstimmen angezeigt. Sollte Ihr Song nicht dabei sein, wenden Sie sich bitte an den Administrator.</p></div>';
		
		echo '<div id="info_icon" onmouseover="info(document.getElementById(\'info_text\'),this,true)" onmouseout="info(document.getElementById(\'info_text\'),this,false)"><!--<a href="http://www.dasnov.de" target="_blank">--><img src="img/info.png" border="0" alt="Hilfe" /><!--</a>--></div>';
		echo '<div id="info_text">Interaktive Playlist Version 1.0<br />Entwickelt von Max Nowack<br /><!--&copy; 2010 by <a href="http://www.dasnov.de" id="copyright_link" target="_blank">dasnov.de</a>--></div>';
		
		echo "<script type=\"text/javascript\" src=\"script.js\"></script>";
		echo "<script type=\"text/javascript\">setInterval(\"setCountdown(document.getElementById('timestamp'),document.getElementById('next_track'));\", 1000);setInterval(\"update();\", 10000);</script>";
	}
?>
	</body>
</html>
