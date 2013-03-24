<?php	
	function loggedIn()
	{
		if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function isDuplicate($artist,$title,$md5)
	{
		$qry = mysql_query("SELECT id FROM songs WHERE filepath LIKE '%".mres($md5)."%' OR (artist LIKE '%".mres($artist)."%' AND title LIKE '%".mres($title)."%');");
		if(mysql_num_rows($qry)>0)
			return true;
		else
			return false;
	}
	
	function mres($str){return mysql_real_escape_string($str);}
?>