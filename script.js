var get_updates = true;
var blinkint = null;
var newTrack = false;

function updateVotes(id)
{
	getTime();
	var request = getRequest();
	if (!request) {
		alert("Request failed!");
		return false;
	} else {
		request.open((id>0 ? 'post' : 'get'), 'ajax/save_vote.php', true);
		if(id>0) request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send(id>0 ? '&id=' + id : '');
		request.onreadystatechange = function()
		{
			switch (request.readyState) {
			case 4:
				if (request.status == 200) {
					document.getElementById('tracks').innerHTML = request.responseText;
				}
				break;
			default:
				break;
		}

		};
	}
}

function getTime()
{
	var request = getRequest();
	if (!request) {
		alert("Request failed!");
		return false;
	} else {
		request.open('get', 'ajax/getTime.php', true);
		request.send("");
		request.onreadystatechange = function()
		{
			switch (request.readyState) {
			case 4:
				if (request.status == 200) {
					document.getElementById('timestamp').innerHTML = request.responseText;
				}
				break;
			default:
				break;
		}

		};
	}
}

function searchTitle(str,div)
{
	if(str.length>=3)
	{
		killChilds(div);
		ajaxLoader(div);
		var request = getRequest();
		if (!request) {
			alert("Request failed!");
			return false;
		} else {
			request.open('get', 'ajax/getSearch.php?str=' + str, true);
			request.send('');
			request.onreadystatechange = function()
			{
				switch (request.readyState) {
				case 4:
					if (request.status == 200) {
						killChilds(div);
						div.innerHTML = request.responseText;
					}
					break;
				default:
					break;
			}

			};
		}
	}
	else
	{
		killChilds(div);
		buildInfo(div);
	}
}

function update() {if(get_updates==true) updateVotes();}

function setCountdown(timestamp,time) {
	timestamp.innerHTML = timestamp.innerHTML - 1;
	var total = timestamp.innerHTML;
	if(total=="NaN") 
	{
		getTime();
	}
	else
	{
		var minutes = Math.floor(total / 60);
		seconds = total % 60;
		if(seconds<0 && minutes<0)
		{ 
			getTime();
			updateVotes();
			time.innerHTML = "N&auml;chster Track in 00:00";
			newTrack = true;
			if(!blinkint) 
			{
				blinkint = setInterval(function(){blinkIt(time);},250);
			}
			if(minutes + 10 < 0)
			{
				time.innerHTML = "Playlist zur Zeit nicht Online!";
				time.style.color = "#FF0000";
			}
		}
		else
		{
			time.innerHTML = "N&auml;chster Track in " + (minutes>=0 && minutes<=9 ? "0" : "") + minutes + ":" + (seconds>=0 && seconds<=9 ? "0" : "" ) + seconds;
			if(blinkint) clearInterval(blinkint);
			if(newTrack)
			{
				makeBlack(time,255);
				newTrack = false;
			}
		}
		updateColor(time,total);
	}
}

function buildInfo(div)
{
	var info = new Array();
	info['p'] = document.createElement('p');
	info['p_class'] = document.createAttribute('class');
	info['p_id'] = document.createAttribute('id');
	info['p_text'] = document.createTextNode('Keine Titel gefunden');
	info['p_class'].nodeValue = 'search';
	info['p_id'].nodeValue = 'search_none';
	
	info['p'].setAttributeNode(info['p_class']);
	info['p'].setAttributeNode(info['p_id']);
	info['p'].appendChild(info['p_text']);
	div.appendChild(info['p']);
}

function blinkIt(div)
{
	if(div.style.color == "rgb(255, 0, 0)" || div.style.color.toLowerCase() == "#ff0000")
		div.style.color = "#000000";
	else
		div.style.color = "#FF0000";
}

function killChilds(elm)
{
	while(elm.childNodes.length>0)
	{
		elm.removeChild(elm.firstChild);
	}
}

function ajaxLoader(elm)
{
	var ajax_loader = new Array();
	ajax_loader['div'] = document.createElement('div');
	ajax_loader['div_id'] = document.createAttribute('id');
	ajax_loader['div_id'].nodeValue = 'ajax_loader';
	ajax_loader['div'].setAttributeNode(ajax_loader['div_id']);
	
	ajax_loader['img'] = document.createElement('img');
	ajax_loader['img_src'] = document.createAttribute('src');
	ajax_loader['img_alt'] = document.createAttribute('alt');
	ajax_loader['img_border'] = document.createAttribute('border');
	ajax_loader['img_src'].nodeValue = 'img/loader.gif';
	ajax_loader['img_alt'].nodeValue = 'loading...';
	ajax_loader['img_border'].nodeValue = '0';
	ajax_loader['img'].setAttributeNode(ajax_loader['img_src']);
	ajax_loader['img'].setAttributeNode(ajax_loader['img_alt']);
	ajax_loader['img'].setAttributeNode(ajax_loader['img_border']);
	
	ajax_loader['div'].appendChild(ajax_loader['img']);
	elm.appendChild(ajax_loader['div']);
}

function updateColor(div,total)
{
//White Background
	var hex = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
	if(document.getElementsByTagName('body')[0].style.backgroundColor.toLowerCase()=="#ffffff" || document.getElementsByTagName('body')[0].style.backgroundColor.toLowerCase()=="rgb(255, 255, 255)" || document.getElementsByTagName('body')[0].style.backgroundColor.toLowerCase()=="")
	{
		var bgwhite = true;
	}
	else
	{
		var bgwhite = false;
	}
	if(total<=32 && total>0) {
		if(bgwhite)
		{
			var color = hex[16 - Math.floor(total / 2)] + "" + hex[16 - Math.floor(total / 2)];// + "" + hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)];
			div.style.color = "#" + color + "0000";
		}
		else
		{
			var color = hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)];
			div.style.color = "#FF" + color;
		}
	}
}


/*function updateColor(div,total)
{
//Black Background
	var hex = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
	if(total<=32 && total>0) {
		var color = hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)] + "" + hex[Math.floor(total / 2)];
		div.style.color = "#FF" + color;
	}	
}*/

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
	return request;
}

function getAbsoluteX (elm) {
   var x = 0;
   if (elm && typeof elm.offsetParent != "undefined") {
     while (elm && typeof elm.offsetLeft == "number") {
       x += elm.offsetLeft;
       elm = elm.offsetParent;
     }
   }
   return x;
}

function getAbsoluteY(elm) {
   var y = 0;
   if (elm && typeof elm.offsetParent != "undefined") {
     while (elm && typeof elm.offsetTop == "number") {
       y += elm.offsetTop;
       elm = elm.offsetParent;
     }
   }
   return y;
}

function info(elm,ankor,show)
{
	var x = getAbsoluteX(ankor);
	var y = getAbsoluteY(ankor);
	var w = ankor.offsetWidth;
	var h = ankor.offsetHeight;
	
	if(show)
		elm.style.display = "inline";
	else
		elm.style.display = "none";
		
	elm.style.position = "absolute";
	elm.style.left = (x+w-elm.offsetWidth) + "px";
	elm.style.top = (y+h) + "px";
}

function makeBlack(div,i)
{
	if(i>=0 && i<=255)
	{
		div.style.color = "rgb(" + i + ", " + i + ", " + i + ")";
		i = i-3;
		setTimeout(function(){makeBlack(div,i)},10);
	}
}

function getColor(color,defaultArray)
{
	var retVal = new Array();
	if(color.trim()!="")
	{
		if(color.indexOf("rgb")==-1)
		{	
			retVal[0] = hexToDec(color.substr(1,2));
			retVal[1] = hexToDec(color.substr(3,2));
			retVal[2] = hexToDec(color.substr(5,2));
		}
		else
		{
			var col = color.replace("rgb(","").replace(")","").split(",");
			retVal[0] = parseInt(col[0]);
			retVal[1] = parseInt(col[1]);
			retVal[2] = parseInt(col[2]);
		}
	}
	else
	{
		retVal = defaultArray;
	}
	return retVal;
}

function hexToDec(hex)
{
	return parseInt(hex,16);
}


function bla(col,def)
{
	var v = getColor(col,def);
	console.debug(v[0]);
	console.debug(v[1]);
	console.debug(v[2]);
}