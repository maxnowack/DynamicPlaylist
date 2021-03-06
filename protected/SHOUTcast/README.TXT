          _ _          __ _        _             _              _   
 _ _ _  _| | |___ ___ / _| |_   __| |_  ___ _  _| |_ __ __ _ __| |_ 
| ' \ || | | (_-</ _ \  _|  _| (_-< ' \/ _ \ || |  _/ _/ _` (_-<  _|
|_||_\_,_|_|_/__/\___/_|  \__| /__/_||_\___/\_,_|\__\__\__,_/__/\__|
====================================================================
                                              streaming audio system
README.TXT for SHOUTCAST SERVER 1.9.8 - February 28, 2007

Table of Contents:
  Introduction
  Requirements
  Updates
  Bug reporting / Support
  Installation
  Configuration
  Running and Shutdown
  Tech Notes / How it Works
  Remote Administration
  XML statistics
  How to Choose Your Maximum Listeners Value
  License
  Version History



Introduction:

  SHOUTcast is a streaming audio system for Windows and Un*x platforms.
At the center of the system is this product, the SHOUTcast Distributed
Network Audio Server (DNAS).  The DNAS is responsible for accepting
a broadcast feed from Winamp and the SHOUTcast Source DSP plug-in, and
repeating the broadcast to listeners connected to this SHOUTcast DNAS.
Once your source content is being fed into the SHOUTcast DNAS, it will
also, dependent on the source content's configuration, list itself
with the SHOUTcast directory so listeners can locate your broadcast.

  The DNAS also has the ability to deliver on-demand content in MP3
format stored in the content/ directory.

  SHOUTcast is a product of Nullsoft, Inc, makers of the fantastically
popular Winamp audio player for Win32.  The SHOUTcast system relies on
Winamp for playback and content sourcing.  Winamp is available at
http://www.winamp.com


Requirements:

If you want to broadcast to listeners, you'll need:

* 90Mhz or faster server, running one of Windows 95, 98, NT, 2000, ME,
  Mac OS X, Sparc Solaris 2.7+, FreeBSD 4.x+, or Linux with a libc6 kernel.

* 14kB of memory for every listener you want to broadcast to (i.e. 1,000
  listeners means you need 14 Megabytes of RAM), plus whatever your
  operating system needs for overhead, plus 1.5MB for the server's
  base requirements.  Don't set the listener count higher than you need,
  it just screws things up.

* Enough bandwidth to run the server.  If you want to broadcast to 100
  listeners at 24kbps, you'll need about 24kbps*100 = 2,400kbps = 2.4Mbps
  of bandwidth.  That's about 2 T1 lines worth of bandwidth.  Trying to
  push 100 128kbps listeners down your 768kbps cable modem isn't going
  to work :)

* If you want people on the internet to be able to hear you, you also need
  a clear connection to the internet.  No firewalls, NAT devices, web
  caches at the ISP, proxy servers, or internet sharing devices.  In some
  cases you can make a workaround but if you intend to provide a quality
  streaming service you have to invest in a quality connection.  Naturally,
  if you just want to run a private server locally on a LAN, you don't need
  this at all.

* If you want to list this server on the SHOUTcast directory, you will need
  a working DNS server configured as well.  You can verify DNS is working
  by pinging www.yahoo.com, or pulling up a web browser and visiting
  www.shoutcast.com.  Note that you can't ping www.shoutcast.com, because
  AOL blocks ICMP traffic.

* A broadcast source.  This is usually in the form of a computer running
  Winamp (can be the same computer running the DNAS), and the SHOUTcast
  Source Plugin.  This is available in the I wanna be a DJ section on
  SHOUTcast.com.

If you don't have some/any of the above, you can still broadcast a station, 
but you'll have to pay someone for the privelege of hosting a SHOUTcast server
for you, and you won't need this piece of software.  To find good providers,
visit the SHOUTcast forums on www.shoutcast.com and hear what others say
about the growing number of streaming audio providers.


Updates:

  SHOUTcast world headquarters is at http://www.shoutcast.com.  Stop by 
to snag the latest version.



Bug reporting / Support:

  SHOUTcast is a labor of love, and as such has no formal support mechanism.
Please send your support questions to our volunteer-staffed (and highly
effective) web forums.  Please search the forums BEFORE asking a question
someone else has probably asked a thousand times before.

  You may also find the SHOUTcast list server and forums to be useful tools.
The URLs are http://listserv.winamp.com and http://forums.winamp.com,
respectively.



Installation:

Windows:  The provided installer will automatically install the DNAS,
an uninstaller, and Start Menu shortcuts.

Unix versions:  Use gunzip and tar to decompress and extract the necessary
binaries for your particular operating system.  When complete, you should
have three files:  the server binary, server config file, and this readme.
Make certain the server is chmod u+x, and that the config file is readable
by the user you want to run the server as.  The server does *not* need to
be run as root, unless you want to use port numbers below 1024 to serve
SHOUTcast audio streams.



Configuration:

Windows:  Launch the GUI SHOUTcast DNAS by going to Start Menu -> SHOUTcast
DNAS.  Click on Edit Config in the menu bar, and a text editor will appear 
with the configuration file for the SHOUTcast server.  When finished, save 
your changes, and kill the GUI server.  You have to restart the DNAS for 
changes to take effect.

Unix:  Edit the sc_serv.conf file in the text editor of your choice.  Tom
would prefer you use Emacs, because it makes Justin really mad.  Justin would
prefer you use vi, because he thinks Tom suffers from some vicious malaise.
You'll probably be lame and end up using Pico.

There's additional documentation available on the parameters on shoutcast.com
in the documentation section.



Running / Shutdown:

WINDOWS:

The SHOUTcast DNAS installer creates shortcuts under Start Menu -> Programs ->
SHOUTcast DNAS to launch either the console or GUI version of the DNAS.

To launch via command line, cd to the directory the DNAS is installed in, and run sc_serv_cons.exe sc_serv.ini.

To shutdown, click Kill Server in the GUI version, or press Ctrl-C on the keyboard in the console version.

UNIX:

Under Unix, cd to the directory where you unpacked the SHOUTcast server, and type ./sc_serv to start the server.  You can start the server in the background by entering ./sc_serv &.  If you want to stop the server, send a TERM or INT signal by issuing a ctrl-C if the server is in the foreground, or a kill -TERM if the server is running in the background.

Signals support is included on UNIX.  Issuing a SIGHUP (kill -HUP) will force the DNAS to close and re-open the logfiles (useful for logfile rotation.)     Also, SIGWINCH (kill -WINCH) will reload the following config file items
(and, specifically, NOT items which aren't listed here) and start again:

   "Password"
   "LogFile"
   "RelayServer"
   "RelayPort"
   "PublicServer"
   "RealTime"
   "ScreenLog"
   "IntroFile"
   "AutoDumpUsers"
   "DestIP"
   "SrcIP"
   "AutoDumpSourceTime"
   "BackupFile"
   "Yport"
   "BanFile"
   "RipFile"
   "AdminPassword"
   "AllowRelay"
   "AllowPublicRelay"
   "ListenerTimer"
   "WebLog"
   "TchLog"
   "Sleep"
   "Unique"
   "W3CLog"
   "W3CEnable"
   "CleanXML"
   "RIPOnly"

If you intend on broadcasting to more than 64 listeners, you may also want to look into unlimiting the descriptors allocated to your shell.  The commands limit, ulimit, and unlimit may be of some assistance to you.

Tech Notes / How it Works:

As of SHOUTcast 1.6.0, there are two modes of operation.  The DNAS's primary
function is as a live-broadcast repeater.  It receives data from a broadcaster, notifies the SHOUTcast directory when requested to by the client, and repeats the broadcast audio data from the source to the listeners.  It attempts to do this rather efficiently.

The second mode of operation is on-demand content streaming.  If you place
.MP3 files in the content directory, SHOUTcast will send these streams to 
listeners along with a small amount of protection keeping them from being
able to save the stream as it comes in (unlike web servers).

An example of on-demand streaming and how it works:

Assuming you've installed the DNAS in /usr/local/shoutcast, and have
copied a valid MP3 file named music.mp3 into /usr/local/shoutcast/content,
you would place a URL link on a web page as follows:

<A HREF="http://your.dnas.ip.address:port/content/music.pls">Music</A>

Now, although there is no file called music.pls in the content directory,
when a WEB BROWSER (NOT WINAMP) connects to the DNAS and requests music.pls, 
the DNAS will check to make sure a file called music.mp3 exists in the
content directory, and if so, auto-generate a playlist for the browser to
hand off to the player for streaming.

The server runs under many operating systems.  It's a threaded, multi-processor aware application.  Memory use is static (meaning it won't change over time), and dependent upon the number of listeners configured.  CPU usage of the DNAS is very low -- we've broadcasted 24kbps on a single 300mhz processor to over 600 listeners.

The DNAS server runs (as of this release) on Windows Server 2003, XP, 2000, Windows NT 4/3, Windows 98, Windows 95, FreeBSD, Linux (glibc), MacOS X, and Sparc Solaris.



Remote Administration:

Remote administration is handled by connecting to the server with a
web browser.  Point your browser to http://your.servers.ip.address:port/
and log in with the admin password you have specified in the config
file.



XML Statistics:

The SHOUTcast DNAS provides an internal mechanism for providing statistics
to external applications via XML, which is useful for providing live 
information on your DNAS embedded in other webpages via PHP, Perl, or 
other methods which can implement XML.  Accessing this data is a bit tricky,
so it's briefly explained here:

XML pages should be called via:

http://yourip:port/admin.cgi?pass=yourpass&mode=viewxml&page=0
http://yourip:port/admin.cgi?pass=yourpass&mode=viewxml&page=1
...

Where:
Page 0 is all DNAS data
Page 1 is only Main data 
Page 2 is only Webdata Table
Page 3 is only Listener Table
Page 4 is only Songhistory Table

Your XML parser MUST send a User-Agent: HTTP header containing the
word "Mozilla" in order for the DNAS to recognize it as something
other than a listener.

A sample PHP script for getting XML data from a SHOUTcast DNAS is
available at http://beta.shoutcast.com/~tpepper/grabxml.phps.  It
should be fairly painless to induce what should be done differently
for other implementations of middleware.


Quick Statistics:

To snag a quick list of listener counts, server state, bitrate, and current
title, use the URL http://yourip:port/7.html



How to Choose Your Maximum Listeners Value:

All listeners are only able to listen at what their connections can support.
For most users on the net, when you consider modem/PPP overhead, internet 
congestion, and the fact that most modems out there are around 33.6kbps, it
makes sense to use 24kbps for most public streaming. Even though, 
theoretically, 33.6 users should be able to listen at 32kbps, with overhead 
it becomes impossible.

Anyone with a 56k modem sending to an upstream server is affected by the 
asymmetric nature of 56k modems. That is, the fastest a 56k modem can download 
data as fast as 53kbps, the upload data is at most 31.2kbps. Sorry, no
56 or 32kbps streams are servable over a 56k modem.

You cannot serve more users that you have available bandwidth. If you're 
running the SHOUTcast server over a modem link at any speed, the most you 
can muster is one user at 24 or 32kbps. Attempting to serve more users than
you have bandwidth only causes skippage.

Shell sysadmins will be *very* unhappy if you consume their available 
bandwidth and server CPU without their consent. SHOUTcast is a highly 
demanding program of bandwidth. A T1 line can only theoretically support 
about 60 listeners if no other traffic is on that T1. Additionally, each 
listener takes up a thread on the server operating system, which can slow 
some operating systems if allowed to expand beyond the limits of the system. 
If you throw up a SHOUTcast server unbeknownst to the sysadmin with 50+ 
maximum users, you had better be prepared to face the consequences of your 
actions.  As a fellow sysadmin, I offer this to you as your ONLY WARNING.

Pick a smart number of maxusers for your server. Calculate by taking the 
available bandwidth you have, multiplying by 0.9 to account for overhead, 
and dividing by the bitrate you want to serve at. For example, an ADSL 
connection @ 768kbps upstream * 0.9 / 24kbps ~= 29 maximum users. Again, 
set this number too high, and when you reach the limit of bandwidth ALL 
the streams will start to skip. 



License:

SHOUTCAST SERVER(TM)

SHOUTCAST(TM), all versions, are copyright protected and are the property 
of Nullsoft, Inc.  Until further notice, this software may be used for personal or commercial purposes for free and need not be registered with Nullsoft, Inc., or its parent company, America Online, Inc.

SHOUTCAST(TM) may not be copied, sold, distributed or used in any other 
manner without the express written consent of Nullsoft, Inc.

To the maximum extent permitted by law, Nullsoft, Inc. disclaims all
warranties regarding this software, express or implied, including but not
limited to warranties of merchantability and fitness for a particular
purpose.  In no event shall Nullsoft, Inc. be liable for consequential,
special, incidental or indirect damages arising out of the use or inability
to use this software even if Nullsoft, Inc. is aware of the possibility of
such damages or a known defect.

By using this software, you are agreeing to the above terms.

Additional Terms of Use information is available at:
http://www.shoutcast.com/disclaimer.phtml



Version History:
v1.9.8 (February 28, 2007)
* bugfix
  When source is not connected, someone can send HTML in the password field, which shows up in the log

v1.9.7 (June 23, 2006)
* bugfix
  "/content/" exploit, final option, if this doesnt work, "/content/" support is going away

v1.9.6 (June 19, 2006)
* bugfixes
  - more "/content/" exploits
  - html in source info removed

v1.9.5 (December 27, 2004)
* bugfixes
  - fixed "/content/" exploit

v1.9.4 (March 17, 2004)
* bugfixes
  - fixed buffer overrun vulnerability for users who knew DJ / Admin Password
  - fixed icq/irc/aim parsing so yp handles spaces in the field
  - fixed listen playlist generator to handle video sources correctly
  - servers can now relay NSV video content from one another

v1.9.2 (November 25, 2002)
* new features
  - sc_serv now recognizes content-types other than Audio/MPEG.  We've been
    sending it all sorts of things.  Rudimentary, DNAS 3.x will be the
    real launch vehicle for different media types.
  - Development support for authentication servers.  Using auth will
    preclude you being able to use the SHOUTcast directory, since listeners
    need authorization to listen to your server.  Ask in the mailing list
    for information on testing this new undocumented featureset.
  - Since bugtraq apparently feels unix admins aren't capable of demonstrating
    enough self-control to mark their logfiles as private, the server no
    longer logs the correct password alongside the submitted password on
    a failed broadcaster connect.

* new bugfixes
  - Fixed overflow exploit where if no broadcast was currently active on the
    server and attacker knew the broadcast password to the server, the server
    could be coredumped.

v1.8.9 (March 15, 2002)
* new features
  - w3c logfile support.  w3c logfiles allow providers to use tools like
    analog and webtrends to track their station statistics.  it also permits
    companies like Arbitron and Measurecast to officially track your station
    metrics.
  - DNAS can now run in reserved-IP only mode.  This means both that your
    server will only accept connections from Reserved IP addresses, and
    also that it will force this server into private-only mode.  Servers
    in reserved-IP mode will not be listed on the SHOUTcast directory, but
    are still compatible when using downstream public relay servers to
    cluster.
  - XML can be changed with a conf line item to remove linefeeds and spacing
    which confuses some XML parsers (i.e. Flash).  The default leaves it in
    "pretty" mode.
  - Public servers now provide cumulative metrics back to the SHOUTcast
    directory.  These values are used to make the statistics on SHOUTcast
    (i.e. http://shoutcast.com/ttsl.html) more accurate.
  - XML now contains two new listener items, POINTER and UID, for ShoutClub's
    tools
  - XML now contains STREAMSTATUS for noting active source connection
  - New HTTP pages, title or title.html and listen.m3u
  - Streamrippers are now denied connections

* new config items
  - W3CLog -- Use this item to define the name of the file for w3c logfiles.
  - W3CEnable -- Use this item to turn W3C logging on or off.
  - CleanXML -- Use this item to strip linefeeds and spacing from XML files.
  - RIPOnly -- Use this item to only allow IP addresses in the Reserved IP
    list to connect/relay.  Setting this item to yes forces this server to
    not list in the SHOUTcast directory (PublicServer=never)

* bugfixes
  - Fixed sc_serv.ini so Content dir doesn't have trailing slash (fixes 
    on-demand serving)
  - Average Listening Time no longer includes relay connections


v1.8.8 (January 16, 2002)
* internal release only


v1.8.7 (December 12, 2001)
* internal release only
 
v1.8.6 (November 2, 2001)
* internal release only

v1.8.5 (October 18, 2001)
* internal release only

v1.8.4 (September 15, 2001)
* internal release only

v1.8.3 (August 7, 2001)
* bugfixes
 - Fixed buffer overrun issue on nasty implementations of vsprintf() on some
   OS's
 - Fixed buffer overrun issue with XML Page generator, when under a DoS 
   Attack.
 - Fixed buffer overrun issue with GUI logging function when under a DoS 
   Attack.

v1.8.2 (July 23, 2001)
* new features (trying to remember...)
 - Automatic full server redirection for clusters
 - On-demand content can now be from any specified directory
 - On-demand content now supports subdirectories

* new config items
 - ContentDir -- Allows you to specify the source directory for on-demand
   streams

* bugfixes
 - Fixed rare bug where losing DNS of yp server caused multiple-day loss of
   contact to yp
 - XML header change (more compatible.  yay.)
 - uh, some other stuff we did in february that I don't remember.

v1.8.0 (January 4, 2001)
* new features
 - internal buffer size now 1 meg -- better resiliency for bad connections
 - initial buffer fill size now larger -- no waits for fast connects
 - far better international language support
 - new web logs now approved for Arbitron ratings
 - buffer position now tracked in admin page (entries that are always at zero
   are recording or relaying your stream.  should nominally sit around 
   128,000)
 - Solaris build now even more optimized, we've pushed 500Mbps on one box with
   this build.
 - Title updates should now be much more closely synchronized with the change
   in the audio stream.

* new config items (none)

* bugfixes
 - Windows sleep granularity fixed.  No more skipping on high bitrate streams.
   Sorry.
 - XML and HTML pages now correctly encode high-byte characters.  You need to
   upgrade to DSP 1.8.0 for international titling to work correctly.
 - Good tweaks in good places, smarter net delivery, smarter buffering.

v1.7.1 (August 29, 2000)
* new features (none)

* new config items
 - Unique -- Allows variable substitution in mass configuration setups
 - Include -- Reads additional configuration file

* bugfixes
 - Average Listener Time now calculated only for listeners connected longer
   than one minute, and includes current listeners in calculated value
 - Average Listener Time no longer returns negative values on very short
   connects
 - Windows version no longer experiences heavy skipping (1.7.0 bug)
 - Fixed rare condition where "zombie" listeners took up listener slots

v1.7.0 (August 14, 2000)
* new features
 - New XML pages for subsets of shoutcast statistics data.  See XML section.
 - Average Listener Time now listed on status page
 - New logging system tracks individual listener events, and assigns
   unique ID for every connected socket for easier tracking.
 - DNAS should now be MUCH more optimized for high-bandwidth
   delivery (listener impact on CPU is now linear instead of geometric)

* new config items
 - WebLog (Yes/No, default No) - display http:// requests in log
 - TchLog (Yes/No, default Yes) - display yp touches
 - Sleep (value) - tweakable parameter for high-traffic SHOUTcast
   servers -- adjusts sleep granularity.
 - CpuCount (value) - tweakable parameter for high-traffic SHOUTcast
   servers -- adjusts client threads.

* bugfixes
 - All non-ASCII characters are now properly encoded for XML
 - YP now receives average listener time data (for ratings)

v1.666.1 (June 12 2000)
* bug fixes
 - Occasional CPU full load issue at idle, and during client connections
   (a thread-safe sleep() would sure be nice.  way to track it down, tag.)

v1.666.0 (June 6 2000)
* new features
 - ListenerTimer config item will force-disconnect listeners active for 
   the number of minutes specified
 - AllowRelay config item will permit or deny other servers from relaying
 - AllowPublicRelay config item allows relaying, but forces relays to treat
   server as non-public
 - Ban / Reserve IP lists now available to add on-the-fly
 - Initial stream backpositioning set at 32k (faster sync-up for listeners
   with faster connections)
 - Taillog via HTML admin now available regardless of disk logging setting

* bugfixes
 - XML now displays appropriately URL-encoded data for all wacky characters
 - Linux SIGSEGV errors fixed.  No, really, I mean it.
 - Taillog now handled internally via circular buffer because Linux pukes
   when more than one thread is accessing a file a lot
 - Relay lock fixed
 - On-demand content delivery fixed for Linux

v1.6.0rc2 (May 16 2000)
* new features
 - Reported listener count only tracks unique IPs (cuts down on fake entries)
 - Upon NAK from yp, server will now attempt to speak to yp again on a source
   disconnect/reconnect, instead of total server restart

* bugfixes
 - SPARC solaris sleep isn't thread-safe, fixed
 - Fixed bug where servers occasionally locked in listener thread and listener
   count skyrocketed
 - Fixed Linux web-admin SIGSEGV bug
 - Fixed taillog SIGSEGV bug

v1.6.0b1 (Feb 16 2000)
* new features:
 - XML status page [http://server:port/admin.cgi?pass=(passwd)&mode=viewxml]
 - on-demand content serving (prevents save-to-disk in Winamp, etc.)
   example included: [http://server:port/content/scpromo.mp3] (open in Winamp)
 - on-demand content playlist generator (for direct URL linking)
   example included: [http://server:port/content/scpromo.pls] (open in IE)

* bugfixes:
 - yp bug where losing connection with yp would leave server out of directory
   until sc_serv restart
 - fixed obscure bug in client thread which would occasionally freeze clients

v1.5.0 (Jan 20 2000)
 - Complete rewrite of source
 - Now 4 thread model instead of n+3, much more efficient
 - Fixed annoying year 2000 log display anomaly (thank you tm struct)
 - Code no longer leaky on any supported OS to best of our knowledge
 - No more wierd signals sneaking in and killing sc_serv on Solaris
 - Addition of IRC, AIM, and ICQ flags for directory
 - Addition of Bans via web interface -- kick and ban the bastards
 - Addition of Reserved connects -- always permit connects by IP
 - Low-bandwidth status report for Palm VII (http://server/7.html)
 - New hyperleet web interface (thanks for the help Steve)
 - Last n songs integrated into web interface (1-20)
 - Admin and Broadcast passwords can optionally be different (hello live365)
 - Listener list (http) now sorted by connect time
 - (fixed 1.3.0b1 gui display bug, taillog now persistent)
 - removal of usage graph (it was crappy)
 - removal of telnet log interface (use http, please)
 - Top secret credits page (happy hunting)
 - Tom has new kick-ass girlfriend (Nullsoft is in bloom)
 - Tag now highly eligible bachelor
