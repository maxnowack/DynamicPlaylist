<?php
	error_reporting(E_ALL);
	
	### MySQL-Konfiguration ###
	define("MySQL_Server",		"localhost");			// Servername des MySQL-Servers
	define("MySQL_User",		"playlist");			// Benutzername für den MySQL-Server
	define("MySQL_Password",	"dBC3yRsd7BH8J4RC");	// Passwort des Benutzers auf dem MySQL-Server
	define("MySQL_Database",	"playlist");			// Name der Datenbank die benutzt werden soll.
	
	### LastFM-Konfiguration ###
	define("SCROBBLE_SONGS",	true);			// Legt fest ob Songs an LastFM übertragen werden.
	define("LASTFM_USERNAME",	"IntPlaylist");		// LastFM-Benutzername
	define("LASTFM_PASSWORD",	"honkhonk");	// LastFM-Passwort
	
	### sc_trans-Konfiguration ###
	define("SC_TRANS_PORT",		5555);			// Port auf dem sc_trans läuft.
	define("SC_TRANS_USER",		"admin");		// Administrator-Benutzername von sc_trans
	define("SC_TRANS_PASS",		"goaway");		// Passwort des Administrators von sc_trans
	
	define("MP3_DIR",			"/var/www/playlist/protected/mp3/");	// Verzeichnis in dem die MP3-Dateien gespeichert werden.
	define("PLAYLIST_FILE",		"/var/www/playlist/protected/playlist.lst");	// Playlist-Datei von sc_trans 
	define("NEXTTIME_FILE",		"/var/www/playlist/protected/next_time");	// Datei in der die Zeit bis zum nächsten Song gespeichert wird.
	define("PAUSE_FILE",		"/var/www/playlist/protected/pause.mp3");	// Datei die am Ende der Playlist-Datei eingefügt wird, um eventuelle Pausen zu überbrücken.
	define("TRACK_END_CUT",		0);	//Zeit in Sekunden, die am Ende eines Songs abgeschnitten wird.
	define("WAITING_TIME",		0);//0.5 * 60 *60 ); //Wartezeit zwischen den Songs in Sekunden
	
	
	#### AB HIER NICHTS MEHR ÄNDERN ####
	define("MySQL_Connection", mysql_connect(MySQL_Server,MySQL_User,MySQL_Password));
	mysql_select_db(MySQL_Database,MySQL_Connection);
	// Verbindung zur Datenbank wird aufgebaut.
?>