<?php if(!loggedIn()) die("Error");		
	if(isset($_GET['ajax'],$_POST['sid']) && $_GET['ajax']==true)
	{
		$json = array();
		if(isset($_POST['mode']) && $_POST['mode']=="delete")
		{
			$qry = mysql_query("SELECT deleted FROM songs WHERE id='".mres($_POST['sid'])."'");
			$get = mysql_fetch_assoc($qry);
			if(!$get['deleted'])
			{
				mysql_query("UPDATE songs SET deleted=true WHERE id='".mres($_POST['sid'])."'");
				$json['success'] = true;
			}
			else
			{
				$json['success'] = false;
				$json['error'][] = 'Song wurde bereits geloescht!\n';
			}
		}
		elseif(isset($_POST['mode']) && $_POST['mode']=="restore")
		{
			$qry = mysql_query("SELECT deleted FROM songs WHERE id='".mres($_POST['sid'])."'");
			$get = mysql_fetch_assoc($qry);
			if($get['deleted'])
			{
				mysql_query("UPDATE songs SET deleted=false WHERE id='".mres($_POST['sid'])."'");
				$json['success'] = true;
			}
			else
			{
				$json['success'] = false;
				$json['error'][] = 'Song wurde bereits wiederhergestellt!\n';
			}
		}
		elseif(isset($_POST['mode']) && $_POST['mode']=="deletecomplete")
		{
			$qry = mysql_query("SELECT id,deleted,filepath FROM songs WHERE id='".mres($_POST['sid'])."'");
			$get = mysql_fetch_assoc($qry);
			if($get['deleted'])
			{
				if(@unlink($get['filepath']))
				{
					mysql_query("DELETE FROM songs WHERE id='".$get['id']."'");
					$json['success'] = true;
				}
				else
				{
					$json['success'] = false;
					$json['error'][] = 'Datei konnte nicht geloescht werden!\n';
				}
			}
			else
			{
				$json['success'] = false;
				$json['error'][] = 'Song wurde noch nicht gesperrt!\n';
			}
		}
		elseif(isset($_POST['artist'],$_POST['title'],$_POST['length']))
		{
			$alert = array();
			$artist = $_POST['artist'];
			$title = $_POST['title'];
			$length = $_POST['length'];
			
			if(strlen(trim($artist))<=0) $alert[] = 'Bitte geben Sie einen Interpreten ein!\n';
			if(strlen(trim($title))<=0) $alert[] = 'Bitte geben Sie einen Titel ein!\n';
			if(strlen(trim($length))<=0 || !is_numeric($length) || $length<=0) $alert[] = 'Es wurde keine gueltige Laenge eingegeben!';
			if(count($alert)==0)
			{
				$qry = mysql_query("SELECT id FROM songs WHERE artist='".mres($artist)."' AND title='".mres($title)."'");
				if(mysql_num_rows($qry)>0)
				{
					$get = mysql_fetch_assoc($qry);
					if($get['id'] != $_POST['sid'])
						$alert[] = 'Dieser Titel existiert schon im Musikarchiv!\n';
				}
			}
			
			if(count($alert)==0)
			{
				$qry = mysql_query("SELECT filepath FROM songs WHERE id='".mres($_POST['sid'])."'");
				$get = mysql_fetch_assoc($qry);
				
				require_once("includes/classes/getid3/getid3.php");
				$getID3 = new getID3();
				
				require_once("includes/classes/getid3/write.php");
				$tagwriter = new getid3_writetags;
				$tagwriter ->filename = $get['filepath'];
				$tagwriter->tagformats     = array('id3v1', 'id3v2.3');
				
				$tagwriter->overwrite_tags = true;
				
				$TagData['title'][]   = $title;
				$TagData['artist'][]  = $artist;
				
				$tagwriter->tag_data = $TagData;
				
				if (!$tagwriter->WriteTags())
				{
					$alert[] = 'Tags konnten nicht in Datei geschrieben werden!\n';
				}
			}
			if(count($alert)==0)
			{
				mysql_query("UPDATE songs SET artist='".mres($artist)."', title='".mres($title)."', length='".mres($length)."' WHERE id='".mres($_POST['sid'])."' LIMIT 1;");
				$json['success'] = true;
			}
			else
			{
				$json['success'] = false;
				$json['error'] = $alert;
			}
		}
		else
		{
			$json['error'][] = "wrong mode!";
			//var_dump($_POST);
		}
		die(json_encode($json));
	}
	else
	{
		echo '<h2>Songs anzeigen</h2>';
			
				$qry = mysql_query("SELECT * FROM songs ORDER BY artist ASC");
				if(mysql_num_rows($qry)>0)
				{
					echo "\n".'<form class="active" action="javascript:void(0);" method="get" id="form_0" name="form_0">
						<table class="form_elm">	
							<tr>
								<td style="width: 25px;">ID</td>
								<td style="width: 140px;">Interpret</td>
								<td style="width: 140px;">Titel</td>
								<td style="width: 140px;">L&auml;nge (in Sekunden)</td>
							</tr>
							</table>
						</form>';
				
					while($get=mysql_fetch_assoc($qry))
					{
						echo "\n".'<form class="'.($get['deleted'] ? "deleted" : "active").'" action="javascript:void(0);" method="get" id="form_'.$get['id'].'" name="form_'.$get['id'].'" onsubmit="submitSong(this);">
						<table class="form_elm">	
							<tr>
								<td style="width: 25px;"><input type="hidden" name="sid" value="'.$get['id'].'" />'.$get['id'].'</td>
								<td><input type="text" name="artist" value="'.$get['artist'].'" /></td>
								<td><input type="text" name="title" value="'.$get['title'].'" /></td>
								<td><input type="text" name="length" value="'.$get['length'].'" /></td>
								<td><input type="hidden" name="mode" value="edit" />
								<button type="button" name="sav" onclick="document.getElementById(\'form_'.$get['id'].'\').mode.value=\'edit\';document.forms[\'form_'.$get['id'].'\'].subm.click();">Speichern</button></td>';
								if($get['deleted']==true)
								{
									echo '<td><button name="del" type="button" onclick="document.getElementById(\'form_'.$get['id'].'\').mode.value=(this.innerHTML==\'Entsperren\' ? \'restore\' : \'delete\');document.forms[\'form_'.$get['id'].'\'].subm.click();">Entsperren</button></td>';
									echo '<td><button style="width:200px;" name="del2" id="del'.$get['id'].'" type="button" onclick="document.getElementById(\'form_'.$get['id'].'\').mode.value=(\'deletecomplete\');document.forms[\'form_'.$get['id'].'\'].subm.click();">Komplett L&ouml;schen</button></td>';
								}
								else
								{
									echo '<td><button name="del" type="button" onclick="document.getElementById(\'form_'.$get['id'].'\').mode.value=(this.innerHTML==\'Entsperren\' ? \'restore\' : \'delete\');document.forms[\'form_'.$get['id'].'\'].subm.click();">Sperren</button></td>';
								}
							echo '</tr></table><input type="submit" value="" name="subm" style="display:none" />
						</form>';
					}
				}
				else
				{
					echo '<table class="form_elm"><tr><td colspan="6"><i>Keine Titel vorhanden!</i></td></tr></table>';
				}
			
		echo '';
	}
?>