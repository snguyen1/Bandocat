<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col'])) {
	$collection = $_GET['col'];
	require('../../Library/DBHelper.php');
	require('../../Library/IndicesDBHelper.php');
	require('../../Library/ControlsRender.php');
	$DB = new IndicesDBHelper();
	$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
	$Render = new ControlsRender();
}
else header('Location: ../../');

?>

<!DOCTYPE html>
<html>
<head>
<title>BandoCat Transcription</title>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
	<script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script><link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
<link rel="stylesheet" type="text/css" href="css/Transcription_Status.css">
	<link rel="stylesheet" type="text/css" href="../../Master/master.css">
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<script src='javascript/rastercoords.js'></script>
<script src='javascript/Transcription.js'></script>
<script src='javascript/Leaflet.MakiMarkers.js'></script>

</head>

<body bgcolor = "#e5f1fd" onload = "getRectangleCoords()">

<div id = 'title'><?php echo $config['DisplayName']; ?> Transcription</div>

<!-- ENTRIES AND DISPLAY BUTTONS FOR CORRECTION OR COMPLETION-->
<div id = "Entries">
<form id = "myForm" >
	<input type="hidden" id="Collection" class="Input_Field" name="Collection" value="<?php echo $collection; ?>" />
	<input type = "hidden" id = "Document_ID" class= "Input_Field" name = "Document_ID">
	<input type = "hidden" id = "File_Name" class= "Input_Field" name = "File_Name">
	<input type="hidden" id = "Entry_Coordinates" class= "Input_Field"name="Entry_Coordinates">
	
<!--<input type="hidden" id = "Field_Book_Info" class= "Input_Field"name="Entry_Coordinates">
	<input type="hidden" id = "Map_Table_Info" class= "Input_Field"name="Entry_Coordinates">  -->
	
	Survey or Section:
	<input type="text" id = "Survey_Or_Section" class= "Input_Field" name="Survey_Or_Section">
	Block or Tract:
	<input type="text" class= "Input_Field" name="Block_Or_Tract" id ="Block_Or_Tract">
	Lot or Acres:
	<input type= "text" class= "Input_Field" id = "Lot_Or_Acres" name = "Lot_Or_Acres">
	Description:
	<input type = "text" class= "Input_Field" id = "Description" name = "Description">
	Client:
	<input type = "text" class= "Input_Field" id = "Client" name = "Client">

	<div id = "Table_Rows">
		<button type = 'button' onclick = "addFieldRow('Field_Book_Table')" id = "addFieldBookRow">+</button>
		<button type = 'button' onclick = "deleteMapRow('Field_Book_Table')" id = "deleteFieldBookRow">-</button>
	</div>
		
	<table id = "Field_Book_Table" name = 'Field_Book_Table'>
		<tr class = 'head'>
			<th>Field Book Number</th>
			<th>Field Book Pages</th>
		</tr>
		<tr>
			<td><input type="text" class= "Input_Field" id = "Field_Book_Number"></td>
			<td><input type="text" class= "Input_Field" id = "Field_Book_Page"></td>
		</tr>
	</table>

	Related Papers File No.:
	<input type="text" class= "Input_Field" id ="Related_Papers_File_No" name = "Related_Papers_File_No">
	<input type="hidden" class= "Input_Field" name="Map_Table_Info" id = "Map_Table_Info">
	<input type = "hidden" class= "Input_Field" name = "Field_Book_Info" id = "Field_Book_Info">
	<input type = "hidden" class= "Input_Field" name = "Date" id = "Date">
	
	<div id = "Table_Rows">
		<button type = 'button' onclick = "addMapRow('Map_Table')" id = "addMapTableRow">+</button>
		<button type = 'button' onclick = "deleteMapRow('Map_Table')" id = "deleteMapTableRow">-</button>
	</div>
		
	<table id = "Map_Table" name = 'Map_Table'>
		<tr class = 'head'>
			<th>Map Number</th>
			<th>Map Kind</th>
		</tr>
		<tr>
			<td><input type="text" class= "Input_Field" id = "Map_Number"></td>
			<td id= "Tabledata_Selection">
				<select id = 'Map_Kind' onchange = "Map_Kind_Dropdown()">
					<?php 
					$Render->GET_DDL($DB->GET_INDICES_MAPKIND($collection),null);
					?>
				</select>
			</td>
		</tr>
	</table>

	Date:
	<table id "Date_Table" name = "Date_Table">
			<td>
				<select onclick = "getDateTableJSON()" name = "monthStart" id = "Month" style = "width:75px;"> <option >Month</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select> 
				<select onclick = "getDateTableJSON()" name = "dayStart" id = "Day" style = "width:60px;"> <option >Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>
				<select onclick = "getDateTableJSON()" name = "yearStart" id = "Year" style = "width:70px;"> <option >Year</option><option value="1800">1800</option><option value="1801">1801</option><option value="1802">1802</option><option value="1803">1803</option><option value="1804">1804</option><option value="1805">1805</option><option value="1806">1806</option><option value="1807">1807</option><option value="1808">1808</option><option value="1809">1809</option><option value="1810">1810</option><option value="1811">1811</option><option value="1812">1812</option><option value="1813">1813</option><option value="1814">1814</option><option value="1815">1815</option><option value="1816">1816</option><option value="1817">1817</option><option value="1818">1818</option><option value="1819">1819</option><option value="1820">1820</option><option value="1821">1821</option><option value="1822">1822</option><option value="1823">1823</option><option value="1824">1824</option><option value="1825">1825</option><option value="1826">1826</option><option value="1827">1827</option><option value="1828">1828</option><option value="1829">1829</option><option value="1830">1830</option><option value="1831">1831</option><option value="1832">1832</option><option value="1833">1833</option><option value="1834">1834</option><option value="1835">1835</option><option value="1836">1836</option><option value="1837">1837</option><option value="1838">1838</option><option value="1839">1839</option><option value="1840">1840</option><option value="1841">1841</option><option value="1842">1842</option><option value="1843">1843</option><option value="1844">1844</option><option value="1845">1845</option><option value="1846">1846</option><option value="1847">1847</option><option value="1848">1848</option><option value="1849">1849</option><option value="1850">1850</option><option value="1851">1851</option><option value="1852">1852</option><option value="1853">1853</option><option value="1854">1854</option><option value="1855">1855</option><option value="1856">1856</option><option value="1857">1857</option><option value="1858">1858</option><option value="1859">1859</option><option value="1860">1860</option><option value="1861">1861</option><option value="1862">1862</option><option value="1863">1863</option><option value="1864">1864</option><option value="1865">1865</option><option value="1866">1866</option><option value="1867">1867</option><option value="1868">1868</option><option value="1869">1869</option><option value="1870">1870</option><option value="1871">1871</option><option value="1872">1872</option><option value="1873">1873</option><option value="1874">1874</option><option value="1875">1875</option><option value="1876">1876</option><option value="1877">1877</option><option value="1878">1878</option><option value="1879">1879</option><option value="1880">1880</option><option value="1881">1881</option><option value="1882">1882</option><option value="1883">1883</option><option value="1884">1884</option><option value="1885">1885</option><option value="1886">1886</option><option value="1887">1887</option><option value="1888">1888</option><option value="1889">1889</option><option value="1890">1890</option><option value="1891">1891</option><option value="1892">1892</option><option value="1893">1893</option><option value="1894">1894</option><option value="1895">1895</option><option value="1896">1896</option><option value="1897">1897</option><option value="1898">1898</option><option value="1899">1899</option><option value="1900">1900</option><option value="1901">1901</option><option value="1902">1902</option><option value="1903">1903</option><option value="1904">1904</option><option value="1905">1905</option><option value="1906">1906</option><option value="1907">1907</option><option value="1908">1908</option><option value="1909">1909</option><option value="1910">1910</option><option value="1911">1911</option><option value="1912">1912</option><option value="1913">1913</option><option value="1914">1914</option><option value="1915">1915</option><option value="1916">1916</option><option value="1917">1917</option><option value="1918">1918</option><option value="1919">1919</option><option value="1920">1920</option><option value="1921">1921</option><option value="1922">1922</option><option value="1923">1923</option><option value="1924">1924</option><option value="1925">1925</option><option value="1926">1926</option><option value="1927">1927</option><option value="1928">1928</option><option value="1929">1929</option><option value="1930">1930</option><option value="1931">1931</option><option value="1932">1932</option><option value="1933">1933</option><option value="1934">1934</option><option value="1935">1935</option><option value="1936">1936</option><option value="1937">1937</option><option value="1938">1938</option><option value="1939">1939</option><option value="1940">1940</option><option value="1941">1941</option><option value="1942">1942</option><option value="1943">1943</option><option value="1944">1944</option><option value="1945">1945</option><option value="1946">1946</option><option value="1947">1947</option><option value="1948">1948</option><option value="1949">1949</option><option value="1950">1950</option><option value="1951">1951</option><option value="1952">1952</option><option value="1953">1953</option><option value="1954">1954</option><option value="1955">1955</option><option value="1956">1956</option><option value="1957">1957</option><option value="1958">1958</option><option value="1959">1959</option><option value="1960">1960</option><option value="1961">1961</option><option value="1962">1962</option><option value="1963">1963</option><option value="1964">1964</option><option value="1965">1965</option><option value="1966">1966</option><option value="1967">1967</option><option value="1968">1968</option><option value="1969">1969</option><option value="1970">1970</option><option value="1971">1971</option><option value="1972">1972</option><option value="1973">1973</option><option value="1974">1974</option><option value="1975">1975</option><option value="1976">1976</option><option value="1977">1977</option><option value="1978">1978</option><option value="1979">1979</option><option value="1980">1980</option><option value="1981">1981</option><option value="1982">1982</option><option value="1983">1983</option><option value="1984">1984</option><option value="1985">1985</option><option value="1986">1986</option><option value="1987">1987</option><option value="1988">1988</option><option value="1989">1989</option><option value="1990">1990</option><option value="1991">1991</option><option value="1992">1992</option><option value="1993">1993</option><option value="1994">1994</option><option value="1995">1995</option><option value="1996">1996</option><option value="1997">1997</option><option value="1998">1998</option><option value="1999">1999</option><option value="2000">2000</option><option value="2001">2001</option><option value="2002">2002</option><option value="2003">2003</option><option value="2004">2004</option><option value="2005">2005</option><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select><span class = "errorInput" id = "docStartDateSub"></span></td>
			</tr>
	</table>
	
	Job Number:
	<input type="text" class= "Input_Field" id ="Job_Number" name = "Job_Number" >

	Comments:
	<textarea class="Input_Field" id="Comments" name="Comments" rows="3"></textarea>

	<!--hidden fields containing entry coordinates-->
	<input type="hidden" name ="x1" id = "x1" value="x1" disabled = "disabled">
	<input type="hidden" name ="y1" id = "y1" value="y1"disabled = "disabled">
	<input type="hidden" name ="x2" id = "x2" value="x2"disabled = "disabled">
	<input type="hidden" name ="y2" id = "y2" value="y2"disabled = "disabled"> 
	
	<input type = "button" id = "updateEntry" value = "Update Entry" onclick = "updateEntryData()">
	<input type = "button" id = "deleteEntry" value = "Delete Entry" onclick = "deleteSelected()">
	<input type="submit"  value="Submit Entry" class = "Submit" id = "Submit" name = "submit">

</form>
</div>

<!-- BUTTONS THAT ARE NOT PART OF THE FORM -->
<div id = "buttons" style="text-align:center">
	<button onclick = "deletePrevious()" id = 'deletePrevious' name="deletePrevious" class="btn">Delete Active Rectangle</button>
	<button  id = "Complete_Transcription" onclick = "completeTranscription(collection_config.Name)" class="btn">Mark as Complete & Close</button>
	<button  id = "Incomplete_Transcription" onclick = "incompleteTranscription()" class="btn" >Close </button>
</div>

<!-- DOCUMENT CONTAINER WITH INDEX IMAGE DISPLAYED-->
<div id = "DOCUMENT_VIEW">
<script>
	Maki_Icon = tempIcon = L.MakiMarkers.icon({color: "#33c1ff", size: "m"});
	
	if (document.getElementById("Year").value == "Year" || document.getElementById("Month").value == "Month" || document.getElementById("Day").value == "Day")
	{
		var dateString ="0000-00-00";
		document.getElementById("Date").value = dateString;
	}
	// create the slippy map
	var map = L.map('DOCUMENT_VIEW', 
	{
		minZoom: 1,
		maxZoom: 6,
		zoom: 1,
		crs: L.CRS.Simple,
		drawControl: true,
	});
	
	var h = 5454;
	var w = 4544;
	
	img = 
	[
		w,  // original width of raster
		h  // original height of raster
	];
	
	//defines our rasterCoords variable, see rasterCoords.js for more details, used for reprojection
	//of latlng values into image coordinates on the document
	var rc = new L.RasterCoords(map, img);
	
	//sets bounds of slippy map
	rc.setMaxBounds();
	var southWest = rc.unproject([0, h], map.getMaxZoom() - 1);
	var northEast = rc.unproject([w, 0], map.getMaxZoom() - 1);
	var bounds = new L.LatLngBounds(southWest, northEast);
	
	//sets view of map on the center to start  
	map.setView(rc.unproject([img[0]/2, img[1]/2]), 2);
	
	//gets Document ID
	var docID = window.localStorage.getItem("docID");
	document.getElementById("Document_ID").value = docID;
	var fileName = window.localStorage.getItem("fileName");
	//gets fileName and writes it to hidden form, fileName in this case is the path of the image in the viewer
	document.getElementById("File_Name").value = fileName;
	
	
	//add image to slippy map
	L.imageOverlay(fileName, bounds).addTo(map);
	
	var markerCount = 0; 						// number of markers currently placed on slippy map
	var rectangleCount = 0; 					// number of rectangles currently placed on the slippy map
	var rectangleCoords = new Array(); 	// stores coordinates of created rectangles
	var rectangleArray = new Array();		// stores rectangle features 
	var markerArray = new Array();			// stores the marker features 
	
	function onMapClick(e) 
	{
		// proccesses that take place after first marker is placed 
		if(rectangleCount < 1)
		{
			//resets form so that new input can be entered
			document.getElementById("myForm").reset();
			deleteTable("Field_Book_Table");
			deleteTable("Map_Table");
			addFieldRow("Field_Book_Table");
			addMapRow("Map_Table");
			
			//dateString = "0000-00-00";
			getDateTableJSON();
			getFileName();
			
			//reset non-active rectangles to default color
			var defaultColor = {color: "#58d68d", weight: 1};			
			for(var i = 0; i < rectangleArray.length; i++)
			{
				rectangleArray[i].setStyle(defaultColor);
			}
			rectangleSelected = false;
			
			//declares variables relevant to creating the active rectangle using markers 
			var coords = rc.project(e.latlng);
			rectangleCoords.push(rc.unproject(coords));
			var marker = L.marker(rc.unproject(coords))
			marker.setIcon(tempIcon);
			map.addLayer(marker);
			markerArray.push(marker);
			markerCount++;
			
			//makes sure that the user doesnt place more than one active rectangle at a time
			if(markerCount % 2 == 0 )
			{
				//creates rectangle on slippy map and adds increments counters and pushes to appropriate arrays 
				rectangleCount++;
				var rectangle = L.rectangle([[rectangleCoords[markerCount-2].lat, rectangleCoords[markerCount-2].lng],
				[rectangleCoords[markerCount-1].lat, rectangleCoords[markerCount-1].lng]],{color: "#28afd5", weight: 1});
				map.addLayer(rectangle);
				rectangleArray.push(rectangle);
				
				var point1 = rc.project(rectangle.getBounds()._southWest);
				var point2 = rc.project(rectangle.getBounds()._northEast);
				
				//create JSON containing image coordinates of rectangle southwest and northeast corners
				entryCoordinateObject = addEntryCoordinateObject(point1.x.toFixed(2), point1.y.toFixed(2), point2.x.toFixed(2), point2.y.toFixed(2));
				
				//write JSON as a string to hidden form "Entry_Coordinates"
				document.getElementById("Entry_Coordinates").value = JSON.stringify(entryCoordinateObject);
			}
		}
		else
			alert("Can't place more than one rectangle at a time");
	}

	//Hadnel on right click functions TODO: MOVE THIS LATER
	map.on('contextmenu', onMapClick);
</script>
</div>

</body>
</html>