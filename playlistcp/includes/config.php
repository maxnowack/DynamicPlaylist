<?php
	error_reporting(E_ALL);
	
	### MySQL-Konfiguration ###
	define("MySQL_Server",		"localhost");			// Servername des MySQL-Servers
	define("MySQL_User",		"playlist");			// Benutzername für den MySQL-Server
	define("MySQL_Password",	"dBC3yRsd7BH8J4RC");	// Passwort des Benutzers auf dem MySQL-Server
	define("MySQL_Database",	"playlist");			// Name der Datenbank die benutzt werden soll.
	
	define("MODERATOR_PASSWORD",	"123456");
	
	define("MP3_DIR",				"/var/www/playlist/protected/mp3/");
	
	
	#### AB HIER NICHTS MEHR ÄNDERN ####
	define("MySQL_Connection", mysql_connect(MySQL_Server,MySQL_User,MySQL_Password));
	mysql_select_db(MySQL_Database,MySQL_Connection);
?>
