<?php
	require_once("../includes/config.php");
	require_once("../includes/functions.php");
	sleep(15);
	if(isset($_GET['str']) && strlen($_GET['str'])>=3)
	{
		//$qry = mysql_query("SELECT id,artist,title FROM library WHERE (interpret LIKE '%".mres($_GET['str'])."%' OR title LIKE '%".mres($_GET['str'])."%')") or die(mysql_error());
		$qry = mysql_query("SELECT songs.id,songs.artist,songs.title,UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(MAX(played.time)) AS lastplayed FROM played RIGHT JOIN songs ON played.song_id = songs.id WHERE	songs.deleted=false AND (songs.artist LIKE '%".mres($_GET['str'])."%' OR songs.title LIKE '%".mres($_GET['str'])."%') GROUP BY songs.id ORDER BY songs.artist,songs.title;") or die(mysql_error());
		if(mysql_num_rows($qry)>0)
		{
			while($track=mysql_fetch_assoc($qry))
			{
				echo "<div class=\"track\" id=\"track_".$track['id']."\" onclick=\"updateVotes(".$track['id'].",true);\">\n";
				echo "<span class=\"track\">".htmlentities(stripslashes($track['artist']))." - ".htmlentities(stripslashes($track['title']));
				if($track['lastplayed']<WAITING_TIME && $track['lastplayed']!=NULL)
				{
					$diff = WAITING_TIME-($track['lastplayed']);
					if($diff<60)
					{
						$str = $diff." Sek";
					}
					else
					{
						$str = round($diff/60)." Min";
					}
					
					echo " <font style=\"color: #990000;background:transparent;\">(noch $str gesperrt)</font>";
				}
				echo "</span>\n</div>\n";
			}
		}
		else
		{
			echo '<p id="search_none" class="search">Keine Titel gefunden</p>';
		}
//		echo "</table>";
	}
?>