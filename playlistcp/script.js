function submitSong(frm)
{	
	var request = getRequest();
	
	var sid = frm.sid.value;
	var mode = frm.mode.value;
	var artist = frm.artist.value;
	var title = frm.title.value;
	var length = frm.length.value;
	
	if(mode=="edit")
	{
		var data = "sid=" + sid + "&artist=" + artist + "&title=" + title + "&length=" + length;
	}
	else
	{
		var data = "mode=" + mode + "&sid=" + sid;
	}
	
	if (!request) {
		return false;
	} else {		
		request.open('post', 'index.php?ajax=1&page=lst', true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');		
		request.send(data);
		request.onreadystatechange = function()
		{
			switch (request.readyState) {
				case 4:
					if (request.status == 200) {
						var json = eval('(' + request.responseText + ')');
						if(json.error)
						{
							var err = "";
							for(i=0;i<json.error.length;i++)
							{
								err = err + json.error[i];
							}
							alert(err);
						}
						else if(json.success)
						{
							switch(mode)
							{
								case "delete":
									frm.className = "deleted";
									frm.del.innerHTML = "Entsperren";
									var parent = (frm.del.parentElement ? frm.del.parentElement.parentElement : frm.del.parentNode.parentNode);
									
									var td = document.createElement("td");
									var btn = document.createElement("button");
									var btn_style = document.createAttribute("style");
									var btn_id = document.createAttribute("id");
									var btn_name = document.createAttribute("name");
									var btn_type = document.createAttribute("type");
									
									btn_style.nodeValue = "width:200px;";
									btn_id.nodeValue = "del" + frm.name.split("_")[1];
									btn_name.nodeValue = "del2";
									btn_type.nodeValue = "button";
									
									var btn_text = document.createTextNode("Komplett Löschen");
									
									btn.setAttributeNode(btn_style);
									btn.setAttributeNode(btn_name);
									btn.setAttributeNode(btn_id);
									btn.setAttributeNode(btn_type);
									
									addEvent(btn,"click",function(){frm.mode.value=('deletecomplete');frm.subm.click();});
									
									btn.appendChild(btn_text);
									td.appendChild(btn);
									parent.appendChild(td);
									
									break;
								case "restore":
									frm.className = "active";
									frm.del.innerHTML = "Sperren";
									document.getElementById("del" + frm.name.split("_")[1]).parentNode.parentNode.removeChild(document.getElementById("del" + frm.name.split("_")[1]).parentNode);
									break;
								case "edit":
									frm.className = "saved";
									break;
								case "deletecomplete":
									if(frm.parentElement)
									{
										frm.parentElement.removeChild(frm);
									}
									else
									{
										frm.parentNode.removeChild(frm);
									}
									break;
							}
						}
					}
					break;
				default:
					break;
			}
		};
	}
}

function getRequest()
{
	if (window.XMLHttpRequest) {
		var request = new XMLHttpRequest(); // Mozilla, Safari, Opera
	} else if (window.ActiveXObject) {
		try {
			var request = new ActiveXObject('Msxml2.XMLHTTP'); // IE 5
		} catch (e) {
			try {
				var request = new ActiveXObject('Microsoft.XMLHTTP'); // IE 6
			} catch (e) { var request = null; }
		}
	}
	else{var request = null;}
	return request;
}

function addEvent( obj, type, fn )
{
   if (obj.addEventListener) {
      obj.addEventListener( type, fn, false );
   } else if (obj.attachEvent) {
      obj["e"+type+fn] = fn;
      obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
      obj.attachEvent( "on"+type, obj[type+fn] );
   }
}

function removeEvent( obj, type, fn )
{
   if (obj.removeEventListener) {
      obj.removeEventListener( type, fn, false );
   } else if (obj.detachEvent) {
      obj.detachEvent( "on"+type, obj[type+fn] );
      obj[type+fn] = null;
      obj["e"+type+fn] = null;
   }
}
