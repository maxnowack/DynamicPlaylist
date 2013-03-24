<?php
	require_once("includes/config.php");
	require_once("includes/functions.php");
	error_reporting(E_ALL);
	
	session_start();
	$err = "";
	
	if(isset($_GET['ajax'],$_GET['page']) && $_GET['ajax']==true && ($_GET['page'] == "add" || $_GET['page'] == "lst"))
	{
		require_once("archive_".$_GET['page'].".php");
		die();
	}
	
	if(isset($_GET['logout']))
	{
		$_SESSION['loggedIn'] = false;
		session_destroy();
	}
	
	if(isset($_POST['passwd']))
	{
		if($_POST['passwd']==MODERATOR_PASSWORD)
		{
			$_SESSION['loggedIn']=true;
		}
		else
		{
			$err = '<p class="error">Falsches Passwort!</p>';
		}
	}

	echo '<?xml version="1.0" ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title>Playlist Control Panel</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
		</head>
		<body>'."\n";
	
	if(!loggedIn())
	{
	echo'	<div id="login_box">
				<h2 id="login_headline">Playlist Control Panel</h2>
				'.$err.'
				<p id="login_text">Bitte loggen Sie sich ein:</p>
				<form action="index.php" method="post" style="margin:0px;background-color:transparent;">
					<div id="login_field">
						<span style="background-color:transparent;">Passwort:</span>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="password" name="passwd" />
					</div>
					<div id="login_button">
						<button type="submit">Login</button>
					</div>
				</form>
			</div>';
	}
	else
	{
		if(isset($_GET['page']) && ($_GET['page'] == "add" || $_GET['page'] == "lst"))
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = "lst";
		}
		
		echo '<ul id="navigation">
		<li><a href="index.php?page=lst">Songs anzeigen</a></li>
		<li><a href="index.php?page=add">Song hochladen</a></li>
		<li><a href="index.php?logout">Logout</a></li>
	</ul>
		<div id="content">';
		
		require_once("archive_".$page.".php");
		
		echo '</div>';
	}
?>
	<script type="text/javascript" src="script.js"></script>
	</body>
</html>