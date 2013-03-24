#!/bin/bash

while : ; do
	TLEN=`php5 -f update_playlist.php`
	DTIME=`date '+%d.%m.%Y %H:%M:%S'`
	if [ -f kill.PL ] ; then
		echo "$DTIME - Playlist wird beendet"
		exit 0
	else
		if [ $TLEN -gt 0 ] ; then
			echo "$DTIME - Next track in $TLEN seconds" >> playlist.log
			echo "$DTIME - Warte $TLEN Sekunden"
			sleep $TLEN
		else
			echo "$DTIME - No tracks to play" >> playlist.log
			echo "$DTIME - Keine Tracks zum abspielen"
			sleep 30
		fi
	fi
done