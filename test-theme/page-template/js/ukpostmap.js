function Gload()
{
}

function GUnload()
{
}

var map;
var georssLayer;
var georssLayer_single;
var postcode;
var marker;
var latlngbounds;
var routeMarkers=new Array(0);
var geocoder;
var geocoder2;
var districtPolygons = Array();
var sectorPolygons = Array();

function init() 
{
	var latlng = new google.maps.LatLng(54.7,-6.28);
	var myOptions = {zoom:6,center:latlng,mapTypeId:google.maps.MapTypeId.ROADMAP,draggableCursor:'crosshair',mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};
	map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
		
	//add area overlay	
	georssLayer = new google.maps.KmlLayer('http://testing.umbrellasupport.co.uk/wp-content/themes/umbrella-portal/js/Postcode%20Boundaries.kml');
	georssLayer.setMap(map);
	latlngbounds = new google.maps.LatLngBounds();
	
	google.maps.event.addListener(map, 'dragend', function() {
		mapdragend();
	});
	geocoder = new google.maps.Geocoder();
	
	var crosshairShape = {coords:[0,0,0,0],type:'rect'};
	var marker = new google.maps.Marker({
		map: map,
		icon: 'http://testing.umbrellasupport.co.uk/wp-content/themes/umbrella-portal/js/crosshairs.gif',
		shape: crosshairShape
	});
	marker.bindTo('position', map, 'center'); 
	
	
	var input=document.getElementById("tb_searchlocation");
	var options = {
  		bounds: map.getBounds()
	};
	var autocomplete = new google.maps.places.Autocomplete(input,options);
	
	autocomplete.bindTo('bounds', map);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function() 
	{ 
		var place = autocomplete.getPlace();
		if (place.geometry.viewport) 
		{
			map.fitBounds(place.geometry.viewport);
		} 
		else 
		{
			map.setCenter(place.geometry.location);
			map.setZoom(10);  // Why 17? Because it looks good.
		}
		mapdragend()
	});
}

function mapdragend()
{
	 geocoder.geocode({'latLng': map.getCenter()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
		  document.getElementById("div_address").innerHTML = "<p>"+results[0].formatted_address+"</p>";
        }
      } else {
        document.getElementById("div_address").innerHTML = "";
      }
    });

}

function ftb_cb_showboundaries_checked()
{
	//show / hide area overlay	
	var cb_showboundaries = document.getElementById("cb_showboundaries");
	if (cb_showboundaries.checked)
	{
		if (georssLayer_single)
		{
			georssLayer_single.setMap(null);
		}
		georssLayer.setMap(map);
	}
	else
	{
		if (georssLayer_single)
		{
			georssLayer_single.setMap(null);
		}
		georssLayer.setMap(null);
	}
}

function ftn_clearmap()
{
	document.getElementById("tb_pspc").value="";
	setSelectedIndex(document.getElementById("area"),1);
	latlngbounds = new google.maps.LatLngBounds();
	
	var latlng = new google.maps.LatLng(54.7,-6.28);
	map.panTo(latlng);
	map.setZoom(5);
	
	if (marker)
	{	
		marker.setMap(null);
	}
	
	if (routeMarkers) 
	{
		for (i in routeMarkers) 
		{
			routeMarkers[i].setMap(null);
		}
	}
	
	if (districtPolygons) 
	{
		for (i in districtPolygons) 
		{
			districtPolygons[i].setMap(null);
		}
	}
	
	if (sectorPolygons) 
	{
		for (i in sectorPolygons) 
		{
			sectorPolygons[i].setMap(null);
		}
	}

	routeMarkers=new Array(0);
}

function showspc()
{
	document.getElementById("tb_pspc").value=document.getElementById("tb_pspc").value.toUpperCase();
	
	postcode = document.getElementById("tb_pspc").value;
	if (postcode!="")
	{
		usePointFromPostcode(postcode,placeMarkerAtPoint);
	}
}

function placeMarkerAtPoint(point,text)
{
	marker = placeMarker(point, text);
	latlngbounds.extend(point);
	marker.setMap(map);
	zoomtofitall();
}

function placeMarker(location,text) 
{
	 
	var marker;
	var cb_showbubble = 1;
	if (cb_showbubble == 1)
	{
		  marker = new google.maps.Marker({
		  position: location,
		  map: map,
		  icon: new google.maps.MarkerImage(
			"https://chart.googleapis.com/chart?chst=d_bubble_text_small&chld=bb|"+text+"|4088b8|000000",
			null, null, new google.maps.Point(0, 42)),
		  shadow: new google.maps.MarkerImage(
			"https://chart.googleapis.com/chart?chst=d_bubble_text_small_shadow&chld=bb|Label%20123",
			null, null, new google.maps.Point(0, 45))
		});
	}
	else
	{
		var image = {url: FMTmarkerurl,size: new google.maps.Size(20, 34),origin: new google.maps.Point(0,0),anchor: new google.maps.Point(10*FMTmarkersizefactor,34*FMTmarkersizefactor), scaledSize: new google.maps.Size(20*FMTmarkersizefactor, 34*FMTmarkersizefactor)};	
		marker = new google.maps.Marker({position:location,map:map,icon:image,title:text,opacity:FMTmarkeropacity});
	}
	
	 marker.addListener('click', function() {
    		//console.log("clicked "+text);
			loadsectorboundaries(text)
  	});
	return marker;
}

function showarea() 
{
	if (document.getElementById("area").value!="")
	{
		//show / hide area overlay	
		document.getElementById("cb_showboundaries").checked=false;
		georssLayer.setMap(null);

		
		
		if (routeMarkers) 
		{
			for (i in routeMarkers) 
			{
				routeMarkers[i].setMap(null);
			}
		}
		
		if (districtPolygons) 
		{
			for (i in districtPolygons) 
			{
				districtPolygons[i].setMap(null);
			}
		}

		routeMarkers=new Array(0);
		districtPolygons=new Array(0);
							
				
		
		latlngbounds = new google.maps.LatLngBounds();
		
		//Create a boolean variable to check for a valid MS instance.
		var xmlhttp = false;
		//Check if we are using IE.
		try 
		{
			//If the javascript version is greater than 5.
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) 
		{
			//If not, then use the older active x object.
			try 
			{
				//If we are using IE.
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch (E)
			{
				//Else we must be using a non-IE browser.
				xmlhttp = false;
			}
		}
		
		//If we are using a non-IE browser, create a javascript instance of the object.
		if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
		{
			xmlhttp = new XMLHttpRequest();
		}
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState==4)
			{
	            var xml = xmlhttp.responseXML;
				var markers = xml.documentElement.getElementsByTagName("marker");
					
				for (var i = 0; i < markers.length; i++) 
				{
					var id = markers[i].getAttribute("id");
					var outcode = markers[i].getAttribute("outcode");
					var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("lat")),parseFloat(markers[i].getAttribute("lng")));
					latlngbounds.extend(point);
					
					marker2 = placeMarker(point, outcode);
					routeMarkers.push(marker2);
				}
				
				zoomtofitall();
				
				
				loaddistrictboundaries(document.getElementById("area").value);
			}
		};
		var rn= Math.random()*999;
		
		xmlhttp.open("GET","ajax/get-area-postcodes.php?area="+document.getElementById("area").value+"&rn="+rn,true);
		xmlhttp.send(null);	
	}
}

function usePointFromPostcode(place, callbackFunction) 
{	
	//alert('called!');
	geocoder2 = new google.maps.Geocoder();
	geocoder2.geocode( { 'address': place +", UK"}, function(results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{
			var point = results[0].geometry.location;			
			
			var resultLng = point.lng();
			if (resultLng>-10)
			{	
				callbackFunction(point,postcode);
				//document.getElementById("btn_find").innerHTML="&nbsp;Plot&nbsp;";
			}
			else
			{
				//document.getElementById("btn_find").innerHTML="&nbsp;Not Found&nbsp;";
			}
		}
		else 
		{
			alert("Postcode not found!");
			acObject.style.visibility = "visible";
			acObject.innerHTML+="Sorry, "+document.forms["inp"]["pointa"].value+" is not on our system at the current time.";
      	}
   	});
}


function zoomtofitall()
{
	map.setCenter(latlngbounds.getCenter());
	//map.fitBounds(latlngbounds);
	map.setZoom(19);
	mapdragend();
}




function loaddistrictboundaries(district)
{
	district=district.toUpperCase();

	var http = new XMLHttpRequest();
	var url = "ajax/load-district-boundary.php?allindistrict=true&district="+district;
	var rn=Math.floor(Math.random()*9999);	
	var params = "check=2&rn="+rn;
	http.open("POST", url, true);
	
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText!="")
			{
				
			var storepolys = http.responseText.split("$$");
			
			 for (i = 0; i < storepolys.length; ++i) 
			 {
					 if (storepolys[i]!="")
					 {
							 drivePolyPoints = Array();
							 //if (setting_debugmode){console.log( storepolys[i]);}
							 
							  var dollarsplit = storepolys[i].trim().split(" ");
							  
								 for (index = 0; index < dollarsplit.length; index++) {
						 
									 if (dollarsplit[index]!="")
									 {		 
										 var pointsplit = dollarsplit[index].split(",");
											var myLatLng = new google.maps.LatLng(pointsplit[1],pointsplit[0]);

										 drivePolyPoints.push(myLatLng);	 		 
									 }

								districtPolygon = new google.maps.Polyline({
								  path: drivePolyPoints,
								  strokeColor: '#FF00FF',
								  strokeOpacity: 1,
								  strokeWeight: 1,
								  editable: false,
								  clickable:false,
								   map :map
								});
								
								districtPolygons.push(districtPolygon);	
							}
					 }
					 
				}	 
		}	
		else
		{
			//alert ("Not Found");	
		}
	
			
		}
		
	}
	http.send(params);
}

function loadsectorboundaries(sector)
{
	if (sector!="")
	{
	document.getElementById('btn_findsectorboundary').innerHTML="Wait";
	
	sector=sector.toUpperCase();
	
	var bounds = new google.maps.LatLngBounds();

	var http = new XMLHttpRequest();
	var url = "ajax/load-sector-boundary.php?allinsector=true&sector="+sector;
	var rn=Math.floor(Math.random()*9999);	
	var params = "check=2&rn="+rn;
	http.open("POST", url, true);
	
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText!="")
			{
				
			var storepolysbig = http.responseText.split("$$");
			
			
			 for (i = 0; i < storepolysbig.length; ++i) 
			 {
					
				var datapipename = storepolysbig[i].split("|");
				
				var storepoly =datapipename[1];
				var thissector = datapipename[0];
				
					 if (storepoly)
					 {
							 var drivePolyPoints = Array();
							 
							  var dollarsplit = storepoly.trim().split(" ");
							  
								 for (index = 0; index < dollarsplit.length; index++) {
						 
									 if (dollarsplit[index]!="")
									 {		 
										var pointsplit = dollarsplit[index].split(",");
										var myLatLng = new google.maps.LatLng(pointsplit[1],pointsplit[0]);
										
										bounds.extend(myLatLng);	
										
										drivePolyPoints.push({lat: Number(pointsplit[1]), lng: Number(pointsplit[0])});		 
									 }
	

								}
								 var sectorPolygon = new google.maps.Polygon({
									paths: drivePolyPoints,
									strokeColor: '#3081B6',
									strokeOpacity: 1,
									strokeWeight: 1,
									fillColor: '#'+Math.floor(Math.random()*16777215).toString(16),
									fillOpacity: 0.3,
									map:map
								  });
							
								
								attachevent(sectorPolygon, thissector);
								attachPolygonInfoWindow(sectorPolygon, thissector);
								sectorPolygons.push(sectorPolygon);	
							
					 }
					 
				}	 
				
				map.fitBounds(bounds);
				document.getElementById('btn_findsectorboundary').innerHTML="Show";
		}	
		else
		{
			//alert ("Not Found");	
			document.getElementById('btn_findsectorboundary').innerHTML="Not Found";
		}
	
			
		}
		
	}
	http.send(params);
	}
}


function attachevent(sectorPolygon, text) {
 	google.maps.event.addListener(sectorPolygon, 'click', function() {
		//console.log('you clicked polyline '+text);
		document.getElementById("div_address").innerHTML = "<p><strong>Sector "+text+"</strong></p>";
	});
}


function attachPolygonInfoWindow(polygon, html)
{
	polygon.infoWindow = new google.maps.InfoWindow({
		content: html,
	});
	google.maps.event.addListener(polygon, 'mouseover', function(e) {
		var latLng = e.latLng;
		this.setOptions({fillOpacity:0.1});
		polygon.infoWindow.setPosition(latLng);
		polygon.infoWindow.open(map);
	});
	google.maps.event.addListener(polygon, 'mouseout', function() {
		this.setOptions({fillOpacity:0.35});
		polygon.infoWindow.close();
	});
}


// Register an event listener to fire when the page finishes loading.
google.maps.event.addDomListener(window, 'load', init);
//]]>