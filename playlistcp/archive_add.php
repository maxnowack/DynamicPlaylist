<?php 
	if(!loggedIn()) die("Error");
	require_once("includes/classes/getid3/getid3.php");
	
	echo '<h2>Song hochladen</h2>';
	if(isset($_POST['upload']))
	{
		if(isset($_FILES['song']['tmp_name']))
		{
			$filename = basename($_FILES['song']['name']);
			$file = "tmp/".$filename;
			if(move_uploaded_file($_FILES['song']['tmp_name'],$file))
			{				
				$getID3 = new getID3;
				
				$fileinfo = $getID3->analyze($file);
				getid3_lib::CopyTagsToComments($fileinfo);				
				
				if($fileinfo['audio']['dataformat']=="mp3")
				{
					@$artist = $fileinfo['comments_html']['artist'][0];
					@$title = $fileinfo['comments_html']['title'][0];
					@$length = round($fileinfo['playtime_seconds']);
				
					echo '<form action="index.php?page=add" method="post">
							<input type="hidden" name="filename" value="'.$filename.'" />
							<p class="info">Bitte korrigieren Sie gegebenenfalls Interpret und Titel. Achten Sie bitte ebenfalls darauf, dass die L&auml;nge des Songs stimmt.</p>
							<table id="songs">
								<tr>
									<td>ID</td>
									<td>Interpret</td>
									<td>Titel</td>
									<td>L&auml;nge (in Sekunden)</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>1</td>
									<td><input type="text" name="artist" value="'.$artist.'" /></td>
									<td><input type="text" name="title" value="'.$title.'" /></td>
									<td><input type="text" name="length" value="'.$length.'" /></td>
									<td><button type="submit">Speichern</button></td>
									<td><button type="button" onclick="document.getElementById(\'del\').submit();">Abrechen</button></td>
								</tr>
							</table>
						</form>
						<form action="index.php?page=add" id="del" method="post"><input type="hidden" name="delete" value="'.$filename.'" /></form>';
				}
				else
				{
					echo "Die hochgeladene Datei ist keine g&uuml;ltige MP3-Datei oder die ID3-Tags konnten nicht erkannt werden.";
					echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
					unlink($file);
				}
			}
			else
			{
				echo "hochgeladene Datei konnte nicht verschoben werden.<br />".$_FILES['song']['tmp_name']."<br />".$file;
				echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
			}
		}
		else
		{
			echo "Datei konnte nicht hochgeladen werden.";
			echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
		}
	}
	elseif(isset($_POST['filename']))
	{
		if($_POST['artist']!=="" && $_POST['title']!=="" && is_numeric($_POST['length']) && $_POST['length']>0)
		{
			$getID3 = new getID3;
		
			$artist = $_POST['artist'];
			$title = $_POST['title'];
			$length = $_POST['length'];
			
			if(!isDuplicate($artist,$title,md5_file("tmp/".$_POST['filename'])))
			{
				$newfilename = md5_file("tmp/".$_POST['filename']);
				
				$filename = "tmp/".str_replace("..",".",$_POST['filename']);
				if(!file_exists(MP3_DIR.date("Ymd")."/")) mkdir(MP3_DIR.date("Ymd"));
				$file = MP3_DIR.date("Ymd")."/".$newfilename.".mp3";
				if(rename($filename,$file))
				{
					require_once("includes/classes/getid3/write.php");
					$tagwriter = new getid3_writetags;
					$tagwriter ->filename = $file;
					$tagwriter->tagformats     = array('id3v1', 'id3v2.3');
					
					$tagwriter->overwrite_tags = true;
					
					$TagData['title'][]   = $title;
					$TagData['artist'][]  = $artist;
					
					$tagwriter->tag_data = $TagData;
					
					if ($tagwriter->WriteTags())
					{
						mysql_query("INSERT INTO songs VALUES('', '".mres($artist)."', '".mres($title)."', '".mres($length)."', '".mres($file)."', '0');");
						echo "Der Song wurde erfolgreich hochgeladen!";
						echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},1000);</script>';
					}
					else
					{
						echo "ID3-Tag konnten nicht neu gesetzt werden.";
						echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
					}
				}
				else
				{
					echo "Verschieben der Datei '".$newfilename.".mp3' fehlgeschlagen.";
					echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
				}
			}
			else
			{
				echo "Diese Datei existiert bereits.";
				echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
			}
		}
		else
		{
			echo "Interpret, Titel oder L&auml;nge falsch.";
			echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
		}
	}
	elseif(isset($_POST['delete']))
	{
		$filename = "tmp/".str_replace("..",".",$_POST['delete']);
		unlink($filename);
		echo "&Auml;nderungen wurden nicht gespeichert.";
		echo '<script type="text/javascript">setTimeout(function(){location.href="index.php?page=add"},5000);</script>';
	}
	else
	{
		echo '<form action="index.php?page=add" method="post" enctype="multipart/form-data"><input type="file" name="song" /><input type="hidden" name="upload" value="1" /> <button type="submit">Hochladen</button></form>';
	}
?>