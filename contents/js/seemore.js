var page = 2;
function see_more($page) {
	var xmlHttpReq;
	var url = document.location.href;

	if (window.XMLHttpRequest) {
		xmlHttpReq = new XMLHttpRequest();
	} else {
		xmlHttpReq = new ActiveXObject();
	}
	xmlHttpReq.onreadystatechange = function() { 
		if ( xmlHttpReq.readyState == 4 && xmlHttpReq.status == 200 ) {
			var text, re;
			text = xmlHttpReq.responseText;
			var result1 = text.split("<div id=\"feel-content\">");
			var result2 = result1[1].split("<div class=\"more\">");
			var div = document.getElementById("feel-content");
			div.innerHTML += result2[0];
		}
	}
	xmlHttpReq.open("GET", url + "?p=" + page++);
	xmlHttpReq.send();
}