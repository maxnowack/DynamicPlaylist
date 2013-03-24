<?php
	function getLength($length)
	{
		$tmp = explode(":", $length);
		return (($tmp[0] * 60) + $tmp[1]);
	}
	
	function getVotesNum()
	{
		$qry = mysql_query("SELECT COUNT(*) AS num FROM vote;");
		$get = mysql_fetch_assoc($qry);
		return $get['num'];
	}
	
	function getTracks($anz=0, $search="")
	{
		$order = "ORDER BY num DESC";
		$order2 = "ORDER BY RAND()";
		if($anz==0)
		{
			$anz = getVotesNum();
			$order = "ORDER BY id";
			$order2 = "ORDER BY id";
		}
		
		$tracks = array();
		$ids = "";
		$num = getVotesNum();
		
		$qry = mysql_query("SELECT songs.id, songs.filepath, songs.length, songs.artist, songs.title, COUNT(vote.song_id) AS num FROM songs LEFT JOIN vote ON (songs.id=vote.song_id) WHERE songs.deleted=false GROUP BY vote.song_id ".$order." LIMIT $anz;") or printf(mysql_error());

		while($get=mysql_fetch_assoc($qry))
		{
			if($get['num']!=0)
			{
				$track['id'] = $get['id'];
				$ids .= $get['id'].", ";
				$track['artist'] = htmlentities(stripslashes($get['artist']));
				$track['title'] = htmlentities(stripslashes($get['title']));
				$track['num'] = $get['num'];
				$track['filepath'] = $get['filepath'];
				$track['length'] = $get['length'];
				$track['lastplayed'] = 0;
				$track['percent'] = ($num!=0 ? round(($get['num'] / $num) * 100,2) : 0);
				$tracks[] = $track;
			}
		}
		
		if(count($tracks)<$anz)
		{
			//$qry = mysql_query("SELECT songs.id,songs.artist,songs.title,songs.length,songs.filepath,IF(MAX(played.time)<>NULL,UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(MAX(played.time)) AS lastplayed FROM played RIGHT JOIN songs ON played.song_id = songs.id WHERE	songs.deleted=false".($ids=="" ? "" : " AND songs.id NOT IN(".substr($ids,0,-2).")")." GROUP BY songs.id HAVING lastplayed+(".WAITING_TIME.")<UNIX_TIMESTAMP(NOW()) ".$order2." LIMIT ".($anz-count($tracks)).";") or printf(mysql_error());
			$qry = mysql_query("SELECT songs.id,songs.artist,songs.title,songs.length,songs.filepath,UNIX_TIMESTAMP(MAX(played.time)) AS lastplayed,COUNT(played.id) as anzid FROM played RIGHT JOIN songs ON played.song_id = songs.id WHERE	songs.deleted=false".($ids=="" ? "" : " AND songs.id NOT IN(".substr($ids,0,-2).")")." GROUP BY songs.id HAVING lastplayed+(".WAITING_TIME.")<UNIX_TIMESTAMP(NOW()) OR anzid=0 ".$order2." LIMIT ".($anz-count($tracks)).";") or printf(mysql_error());
			while($get=mysql_fetch_assoc($qry))
			{
				$track['id'] = $get['id'];
				$track['artist'] = htmlentities(stripslashes($get['artist']));
				$track['title'] = htmlentities(stripslashes($get['title']));
				$track['num'] = 0;
				$track['filepath'] = $get['filepath'];
				$track['length'] = $get['length'];
				$track['lastplayed'] = $get['lastplayed'];
				$track['percent'] = 0;//($get['num'] / $num) * 100;
				$tracks[] = $track;
			}
		}
		return $tracks;
	}
	
	function mres($str){return mysql_real_escape_string($str);}
	
	function isIPBanned($IP)
	{
		$time = getLastPlayedTime();
		
		$qry = mysql_query("SELECT MAX(`time`) AS zeit FROM vote INNER JOIN user ON vote.user_id=user.id AND user.ip_address='".mres($IP)."'");
		if(mysql_num_rows($qry)>0)
		{
			if($time>0)
			{
				$get = mysql_fetch_assoc($qry);
				if($get['zeit']>$time)
					return true;
				else
					return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	function saveIP($IP)
	{
		$qry = mysql_query("SELECT id FROM user WHERE ip_address='".mres($IP)."' LIMIT 1;") or printf(mysql_error());
		
		if(mysql_num_rows($qry)>0)
			mysql_query("UPDATE user SET last_action=NOW() WHERE ip_address='".mres($IP)."';") or printf(mysql_error());
		else
			mysql_query("INSERT INTO user VALUES('','".mres($IP)."',NOW(),NOW())") or printf(mysql_error());

		$qry = mysql_query("SELECT id FROM user WHERE ip_address='".mres($IP)."' LIMIT 1;") or printf(mysql_error());
		$get = mysql_fetch_assoc($qry);
		return $get['id'];
	}
	
	function songBlocked($id)
	{
		$qry = mysql_query("SELECT id FROM played WHERE song_id='".mres($id)."' GROUP BY song_id HAVING UNIX_TIMESTAMP(MAX(time)) + ( ".WAITING_TIME." )<UNIX_TIMESTAMP(NOW());");
		if(mysql_num_rows($qry)>0)
			return false;
		elseif(getNumPlayed($id)>0)
			return true;
		else
			return false;
	}
	
	function getNumPlayed($id)
	{
		$qry = mysql_query("SELECT COUNT(played.id) as anzid FROM played WHERE song_id='".mres($id)."'");
		$get = mysql_fetch_assoc($qry);
		return $get['anzid'];
	}
	
	function readMP3Folder($folder,&$files)
	{
		$dir = opendir($folder);
		while($file = readdir($dir))
		{
			if($file!="." && $file!="..")
			{
				if(is_dir($folder.$file))
				{
					readMP3Folder($folder.$file."/",$files);
				}
				else
				{
					$files[] = $folder.$file;
				}
			}
		}
	}
	
	function getLastPlayedTime()
	{
		$qry = $qry = mysql_query("SELECT `time` FROM played ORDER BY `time` DESC LIMIT 1;");
		if(mysql_num_rows($qry)>0)
		{
			$get = mysql_fetch_assoc($qry);
			return $get['time'];
		}
		else
		{
			return 0;
		}
	}
	
	function sctransAPICall($cmd)
	{
		$header = 	"GET /".$cmd." HTTP/1.1\r\n".
			"Host: localhost:".SC_TRANS_PORT."\r\n".
			"User-Agent: DynamicPlaylist/1.0\r\n".
			"Connection: close\r\n".
			"Authorization: Basic ".base64_encode(SC_TRANS_USER.":".SC_TRANS_PASS)."\r\n\r\n";
		$s = fsockopen("localhost", SC_TRANS_PORT);
		fputs($s, $header);
		fclose($s);
	}
?>