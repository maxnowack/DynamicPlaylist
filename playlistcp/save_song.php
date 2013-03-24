<?php
	require_once("config.php");
	require_once("functions.php");
	require_once("Id.php");
	
	error_reporting(0);
	
	if(isset($_POST['id'],$_POST['artist'],$_POST['title'],$_POST['length'])
	&& trim($_POST['id'])!="" && trim($_POST['artist'])!="" && trim($_POST['title'])!="" && trim($_POST['length']))
	{
		$qry = mysql_query("SELECT path FROM library WHERE id='".mres($_POST['id'])."'");
		if(mysql_num_rows($qry)>0)
		{
			$get = mysql_fetch_assoc($qry);
			
			$mp = new MP3_Id;
			$mp->read($get['path']);
			
			$mp->setTag("artist",$_POST['artist']);
			$mp->setTag("name",$_POST['title']);
			
			$result = $mp->write();
			
			if (PEAR::isError($result)) {
				die("PEAR Error");
			}
			
			mysql_query("UPDATE library SET interpret='".mres($_POST['artist'])."',title='".mres($_POST['title'])."',length='".mres($_POST['length'])."' WHERE id='".mres($_POST['id'])."'") or die(mysql_error());
			echo "saved";
		}
		else
		{
			echo "no datarow";
		}
	}
	else
	{
		echo "not set";
	}
?>