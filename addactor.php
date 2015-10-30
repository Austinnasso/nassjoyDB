<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nassjoy Database</title>
<script src="jquery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery.mask.js"></script>
<LINK REL=StyleSheet HREF="main.css" TYPE="text/css" MEDIA=screen>
</head>

<body>
<script type="text/javascript">
function toggleSex()
{
	var type = document.getElementById("AOD").value;
	if (type == "Director")
	{
		document.getElementById("sex-div").style.display = "none";
		document.getElementById("addMovie").style.display = "none";
		document.getElementById("addActor").style.display = "inline-block"; 
	}
	else if (type == "Actor")
	{
		document.getElementById("sex-div").style.display = "table-row";
		document.getElementById("addMovie").style.display = "none";
		document.getElementById("addActor").style.display = "inline-block";
	}
	else if (type == "Movie")
	{
		document.getElementById("addMovie").style.display = "inline-block"; 
		document.getElementById("addActor").style.display = "none"; 
	}
}

function dateForm(obj)
{
	$(obj).mask('00/00/0000');		
}
</script>
<?php include "header.php" ?>
<div id="content">
<div class="form-wrap">
<h1 class="form-header">Contribute</h1>
<div class="inner-form-wrap">
<div style="margin: auto; display: block; width: 115px;">
<form>
Add </td><td><select onchange="toggleSex()" id="AOD" name="AOD"><option value="Actor" selected="selected">Actor</option><option value="Director">Director</option><option value="Movie">Movie</option></select></td></tr>
</form>
</div>
<br />

<div id="addActor">
<form method="post" name="actorDirectorForm">
<table class="table-form">
<tr><td>First Name: </td><td><input id="first" name="first" type="text" /></td></tr>
<tr><td>Last Name:</td> <td><input id="last" name="last" type="text" /></td></tr>
<tr id="sex-div"><td>Sex: </td><td><select id="sex" name="sex"><option value="M" selected="selected">Male</option><option value="F">Female</option></select></td></tr>
<tr><td>Date of Birth <br /><span style="font-size:10px">(MM/DD/YYYY)</span>: </td><td><input id="dob" name="dob" type="date" onfocus="dateForm(this)" /></td></tr>
<tr><td>Date of Death <br /><span style="font-size:10px">(MM/DD/YYYY)</span>: </td><td><input id="dob" name="dob" type="date" onfocus="dateForm(this)"/></td></tr>
</table>
<br />
<div class="submit">
<button type="submit">Insert</button>
</div>
</form>
</div>

<div id="addMovie" class="table-form" style="display: none;">
<form method="post" name="movieForm">
<table class="table-form">
<tr><td>Title:</td> <td><input id="movie_title" name="movie_title" type="text" /></td></tr>
<tr><td>Year:</td> <td><input id="year" name="year" type="number" /></td></tr>
<tr><td>Rating:</td> <td><input id="rating" name="rating" type="text" /></td></tr>
<tr><td>Company:</td> <td><input id="company" name="company" type="text" /></td></tr>
</table>
</form>
<br />
<div class="submit">
<button type="submit">Insert</button>
</div>
</form>
</div>


</div>
</div>
</div>


</div>
</div>
</body>
</html>