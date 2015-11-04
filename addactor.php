<?php

// Create connection
$db_connection = mysql_connect("localhost", "cs143", "");
mysql_select_db("CS143", $db_connection);

if(!$db_connection) {
    $errmsg = mysql_error($db_connection);
    print "Connection failed: $errmsg <br />";
    exit(1);
}

?>


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
	var type = document.getElementById("type").value;
	if (type == "Director")
	{
		document.getElementById("sex-div").style.display = "none";
		document.getElementById("addMovie").style.display = "none";
		document.getElementById("addActor").style.display = "inline-block"; 
		document.getElementById("AOD").value = "Director";
		document.getElementById("form-wrap-id").style.height = "400px";
	}
	else if (type == "Actor")
	{
		document.getElementById("sex-div").style.display = "table-row";
		document.getElementById("addMovie").style.display = "none";
		document.getElementById("addActor").style.display = "inline-block";
		document.getElementById("AOD").value = "Actor";
		document.getElementById("form-wrap-id").style.height = "400px";
	}
	else if (type == "Movie")
	{
		document.getElementById("addMovie").style.display = "inline-block"; 
		document.getElementById("addActor").style.display = "none"; 
		document.getElementById("form-wrap-id").style.height = "600px";
	}
}

function dateForm(obj)
{
	$(obj).mask('00/00/0000');		
}
</script>
<?php include "header.php" ?>
<?php
$debug = 0;
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function dateFormat($date)
{
	$year = substr($date, 6, 4); 
	$day = substr($date, 3, 2); 
	$mo = substr($date, 0,2); 
	$newdate = $year . "-" . $mo . "-" . $day;
	return $newdate;
}

function testName($name) {
	if(empty($name))
		return 1;
	if(strlen($name)>20)
		return 1;
	else 
	{
		$matches;
		preg_match('/^[a-zA-Z\'-]*$/', $name, $matches);
		if (!empty($matches[0]))
			return 0;
		else
			return 1;
	}
}
//CHECK IF FORM WAS SUBMITTED AND INSERT RECORD INTO DATABASE
//ERROR INPUT CHECKS FOR ACTOR/DIRECTOR:
//1. MAKE SURE ONLY ALPHABETICAL CHARACTERS, SPACES, AND APOSTROPHES ARE USED
//2. MAKE SURE DOB PRECEDES DOD
//3. MAKE SURE NAME HAS AT LEAST ONE CHARACTER
$post = $post2 = 0; 
$success = $success2 = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$table = $_POST['type'];
	
	$success_msg = "";
	$first_err = $last_err = $dob_err = $title_err = $year_err = $company_err = $rating_err = $genre_err;
	if ($table == "Actor" || $table == "Director")
	{
		$post = 1;
		$first = test_input($_POST['first']);
		$first_err=testName($first); 
	
		$last = test_input($_POST['last']);
		$last_err=testName($last);
		
			
		if ($table=="Actor")
			$sex = $_POST['sex'];
		
		if (!empty($_POST['dob']) && strlen($_POST['dob']) == 10)
		{
			$DOB = $_POST['dob'];
			$DOB = dateFormat($DOB);
			
			//CHECK IF VALID DATE
			$dob_check = date_create($DOB);
			if (!$dob_check)
				$dob_err = 1;
		}
		else
			$dob_err = 1;
			
			
		if (!empty($_POST['dod']) && strlen($_POST['dod']) == 10)
		{
			$DOD = $_POST['dod'];
			$DOD = dateFormat($DOD);
			
			//CHECK IF VALID DATE
			$dod_check = date_create($DOD);
			if (!$dod_check)
				$dod_err = 1;
			
			if(!empty($DOB) && strtotime($DOD) < strtotime($DOB) ) 
				$dod_err = 1;
			
			
		}
		else if (empty($_POST['dod']))
			$DOD = "";
		else
			$dod_err = 1;
			
		$sql = "";
		
		
		//INSERT IF NO ERROR
		if (!$dod_err &&  !$last_err && !$first_err && !$dob_err) 
		{
			$sql = "SELECT id FROM MaxPersonID"; 
			$rs = mysql_query($sql, $db_connection);
			$row = mysql_fetch_array($rs);
			$maxID = $row['id'] + 1;
			$prevMax = $maxID - 1;
			$alreadyExists=0;
		
			if ($table == "Actor"){	
			if ($DOD == "")
			{
				$sql = "SELECT id FROM Director WHERE last='{$last}' AND first='{$first}' AND dob='{$DOB}'"; 
				$rs = mysql_query($sql, $db_connection);
				$row = mysql_fetch_array($rs);
				if ($row)
				{
					$alreadyExists = 1;
					$maxID = $row['id'];
				}
				$sql = "INSERT INTO Actor (id, last, first, sex, dob) VALUES ('{$maxID}', ". "'". mysql_escape_string($last) . "'". ", " . "'". mysql_escape_string($first) . "'". ", '{$sex}', '{$DOB}')";
			}
			else
			{
				$sql = "SELECT id FROM Director WHERE last='{$last}' AND first='{$first}' AND dob='{$DOB}' AND dod='{$DOD}'"; 
				$rs = mysql_query($sql, $db_connection);
				$row = mysql_fetch_array($rs);
				if ($row)
				{
					$alreadyExists = 1;
					$maxID = $row['id'];
				}
				$sql = "INSERT INTO Actor (id, last, first, sex, dob, dod) VALUES ('{$maxID}', '{$last}', '{$first}', '{$sex}', '{$DOB}', '{$DOD}')";
			}
			}
			
			else if ($table == "Director"){
			if ($DOD == "")
			{
				$sql = "SELECT id FROM Actor WHERE last='{$last}' AND first='{$first}' AND dob='{$DOB}'"; 
				$rs = mysql_query($sql, $db_connection);
				$row = mysql_fetch_array($rs);
				if ($row)
				{
					$alreadyExists = 1;
					$maxID = $row['id'];
				}
				$sql = "INSERT INTO Director (id, last, first, dob) VALUES ('{$maxID}', '{$last}', '{$first}', '{$DOB}')";
				
			}
			else
			{
				$sql = "SELECT id FROM Actor WHERE last='{$last}' AND first='{$first}' AND dob='{$DOB}' AND dod='{$DOD}'"; 
				$rs = mysql_query($sql, $db_connection);
				$row = mysql_fetch_array($rs);
				if ($row)
				{
					$alreadyExists = 1;
					$maxID = $row['id'];
				}
				$sql = "INSERT INTO Director (id, last, first, dob, dod) VALUES ('{$maxID}', '{$last}', '{$first}', '{$DOB}', '{$DOD}')";
			}
				
			}

			if ($debug)
				echo $sql; 
			$result = mysql_query($sql, $db_connection);
			if (!$result) {
    			die('Invalid query: ' . mysql_error());
			}
			else
			{
				$success = 1;
				if ($_POST['type'] == 'Actor')
					$success_msg = "Actor successfully inserted!";
				else
					$success_msg = "Director successfully inserted!";
			}
	
			//IF PERSON ISN'T ALREADY IN DB, ADD TO EXISTING MAXID
			if (!$alreadyExists)
			{
				$sql = "UPDATE MaxPersonID SET id='{$maxID}' WHERE id='{$prevMax}'";
				mysql_query($sql, $db_connection);
			}
			
			if (!$result) {
    			die('Invalid query: ' . mysql_error());
			}
		}
	}
	
	else if ($table == "Movie")
	{
		$post = 1;
		if (!empty($_POST['title']))
			$title = test_input($_POST['title']); 
		else
			$title_err = 1;

		$rating = $_POST['rating'];

		if (!empty($_POST['company']))
			$company = test_input($_POST['company']); 
		else
			$company_err = 1;

		
		if (!empty($_POST['year']) && strlen($_POST['year']) == 4)
			$year = test_input($_POST['year']); 
		else
			$year_err = 1; 
		


		if (!$year_err && !$title_err && !$company_err)
		{
			$sql = "SELECT id FROM MaxMovieID"; 
			$rs = mysql_query($sql, $db_connection);
			$row = mysql_fetch_array($rs);
			$maxID = $row['id'] + 1;
			$prevMax = $maxID - 1;
			
			//ADD MOVIE
			$sql = "INSERT INTO Movie (id, title, year, rating, company) VALUES ('{$maxID}', '{$title}', '{$year}', '{$rating}', '{$company}')";
			if ($debug)
				echo $sql; 

			$result = mysql_query($sql, $db_connection);
			if (!$result)
				die('Invalid query: ' . mysql_error());
			else 
			{
				$success = 1;
				$success_msg = "Movie successfully inserted!";
			}

			//UPDATE MAXID
			$sql = "UPDATE MaxMovieID SET id='{$maxID}' WHERE id='{$prevMax}'";
			mysql_query($sql, $db_connection);
		} 

			//ADD GENRES
		for ($x=1; $x<19; $x++)
		{
			$index = "g" . $x; 
			if (!$_POST[$index])
				continue;
			else
			{
				$genre = $_POST[$index];
				$sql = "INSERT INTO MovieGenre (mid, genre) VALUES ('{$maxID}', '{$genre}')";
				$result = mysql_query($sql, $db_connection);
				if (!$result) {
					die('Invalid query: ' . mysql_error());
				} 

			}



		}
	}
	else if ($table == 'AddToMovie')
	{
		//$_POST['aod'] returns 'Actor' OR 'Director
		//$_POST['a_first'] returns first name raw input
		//$_POST['a_last'] returns last name raw input
		//$_POST['a_title'] returns title name raw input
		
		//TODO: Verify input is correct. Then check first name and last name against database to make sure
		//actor or director exists. If doesn't exist, display a message "Actor doesn't match our records.", "Director doesn't match our records.",
		//and/or "Movie doesn't match our records." If actor, director, and movie exist, grab their IDs and insert
		//tuple into MovieActor and MovieDirector tables respectively. 
		$first = test_input($_POST['first']);
		$first_err=testName($first); 
		
		$last = test_input($_POST['last']);
		$last_err=testName($last);
		$success_msg2 = "";
		$error_msg2 = "";
		$post2=1;
		$id_actor_director=$id_movie;
		$role;
		$check_movie=$check_actor_director=0;
		if(!$first_err&&!$last_err) {
			$title = $_POST['a_title'];
			//person exist
			$aod=$_POST['aod'];
			if($aod=='Actor') {
				$sql="SELECT * FROM Actor WHERE first='{$first}' AND last='{$last}'"
				$result = mysql_query($sql, $db_connection);
				if (!$result) {
					$error_msg2="Actor doesn't exist!";
					$check_actor_director=1;
				}
			}
			else if($aod=='Director') {
				$sql="SELECT * FROM Director WHERE first='{$first}' AND last='{$last}'"
				$result = mysql_query($sql, $db_connection);
				if (!$result) {
					$error_msg2="Director doesn't exist!";
					$check_actor_director=1;
				}
			}
			//movie exist
			$sql="SELECT * FROM Movie WHERE title='{$title}'";
			$result = mysql_query($sql, $db_connection);
			if (!$result) {
				$error_msg2="Actor doesn't exist!";
				$check_movie=1;
			}
			if(!$check_movie&&!$check_actor_director) {
				//check if person exists in movie
				if($aod=='Director') {
					$sql="SELECT mid FROM MovieDirector WHERE did=(SELECT id FROM Director WHERE first='{$first}' AND last='{$last}')"
					$result = mysql_query($sql, $db_connection);
					if($result) {
						$error_msg2="Director already exists in the specified movie!";
						$check_actor_director=1;
					}
				}
				if($aod=='Actor') {
					$sql="SELECT mid FROM MovieActor WHERE aid=(SELECT id FROM Actor WHERE first='{$first}' AND last='{$last}')"
					$result = mysql_query($sql, $db_connection);
					if($result) {
						$error_msg2="Actor already exists in the specified movie!";
						$check_actor_director=1;
					}
				}
				if(!$check_actor_director&&$aod=='Actor') {
					$sql_getMovie="SELECT id FROM Movie WHERE title='{$title}'";
					$value_mid=mysql_fetch_object(mysql_query($sql_getMovie));
					$sql_getActor="SELECT id From Actor WHERE first='{$first}' AND last='{$last}'"
					$value_aid=mysql_fetch_object(mysql_query($sql_getActor));
					$id_movie=$value_mid->id;
					$id_actor_director=$value_aid->id;
					$sql = "INSERT INTO MovieActor (mid, aid, role) VALUES ('{$id_movie}', '{$id_actor_director}', '')";
					$result = mysql_query($sql, $db_connection);
					if (!$result) {
						die('Invalid query: ' . mysql_error());
					}
					else
						$success2=1; 
				}
				if(!$check_actor_director&&$aod=='Director') {
					$sql_getMovie="SELECT id FROM Movie WHERE title='{$title}'";
					$value_mid=mysql_fetch_object(mysql_query($sql_getMovie));
					$sql_getDirector="SELECT id From Director WHERE first='{$first}' AND last='{$last}'"
					$value_did=mysql_fetch_object(mysql_query($sql_getDirector));
					$id_movie=$value_mid->id;
					$id_actor_director=$value_did->id;
					$sql = "INSERT INTO MovieDirector (mid, did) VALUES ('{$id_movie}', '{$id_actor_director}')";
					$result = mysql_query($sql, $db_connection);
					if (!$result) {
						die('Invalid query: ' . mysql_error());
					}
					else
						$success2=1; 
				}
			}
		}
	}
}
?>
<div id="content">

<div class="form-wrap" id="form-wrap-id">
<h1 class="form-header">Contribute</h1>
<div class="inner-form-wrap">
<div style="margin: auto; display: block; width: 115px;">
<form>
Add </td><td><select onchange="toggleSex()" id="type" name="type"><option value="Actor" selected="selected">Actor</option><option value="Director">Director</option><option value="Movie">Movie</option></select></td></tr>
</form>
</div>
<br />

<div id="addActor">
<form method="post" name="actorDirectorForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
<table class="table-form">
<tr><td>First Name: </td><td><input id="first" name="first" type="text" /></td></tr>
<tr><td>Last Name:</td> <td><input id="last" name="last" type="text" /></td></tr>
<tr id="sex-div"><td>Sex: </td><td><select id="sex" name="sex"><option value="M" selected="selected">Male</option><option value="F">Female</option></select></td></tr>
<tr><td>Date of Birth <br /><span style="font-size:10px">(MM/DD/YYYY)</span>: </td><td><input id="dob" name="dob" type="text" onfocus="dateForm(this)" /></td></tr>
<tr><td>Date of Death <br /><span style="font-size:10px">(MM/DD/YYYY)</span>: </td><td><input id="dod" name="dod" type="text" onfocus="dateForm(this)"/></td></tr>
</table>
<input type="hidden" id="AOD" name="type" value="Actor" />
<?php 

if (!$success && $post)
	echo '<p id="ad_error" style="color: red">*Invalid input. Try Again.</p>'; 
else 
	echo'<p id="ad_success" style="color:green">' . $success_msg . '</p>';
?>
<br />
<div class="submit">
<button type="submit">Insert</button>
</div>
</form>
</div>

<div id="addMovie" class="table-form" style="display: none;">
<form method="post" name="movieForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
<table class="table-form">
<tr><td>Title:</td> <td><input id="movie_title" name="title" type="text" /></td></tr>
<tr><td>Year:</td> <td><input id="year" name="year" type="number" /></td></tr>
<tr><td>Rating:</td> <td><select name="rating"><option selected="selected" value="G">G</option><option value="PG">PG</option><option value="PG-13">PG-13</option><option value="R">R</option><option value="NC-17">NC-17</option></select></td></tr>
<tr><td>Company:</td> <td><input id="company" name="company" type="text" /></td></tr>
<tr><td>Genre:</td> <td></td></tr>
</table>
<table class="genre">
<tr><td><input type="checkbox" value="Action" name="g1" /> Action</td><td><input type="checkbox" value="Adult" name="g2" /> Adult</td></tr>
<tr><td><input type="checkbox" value="Animation" name="g3" /> Animation</td><td><input type="checkbox" value="Comedy" name="g4" /> Comedy</td></tr>
<tr><td><input type="checkbox" value="Crime" name="g5" /> Crime</td><td><input type="checkbox" value="Documentary" name="g6" /> Documentary</td></tr>
<tr><td><input type="checkbox" value="Drama" name="g7" /> Drama</td><td><input type="checkbox" value="Family" name="g8" /> Family</td></tr>
<tr><td><input type="checkbox" value="Fantasy" name="g9" /> Fantasy</td><td><input type="checkbox" value="Horror" name="g10" /> Horror</td></tr>
<tr><td><input type="checkbox" value="Musical" name="g11" /> Musical</td><td><input type="checkbox" value="Mystery" name="g12" /> Mystery</td></tr>
<tr><td><input type="checkbox" value="Romance" name="g13" /> Romance</td><td><input type="checkbox" value="Sci-Fi" name="g14" /> Sci-Fi</td></tr>
<tr><td><input type="checkbox" value="Short" name="g15" /> Short</td><td><input type="checkbox" value="Thriller" name="g16" /> Thriller</td></tr>
<tr><td><input type="checkbox" value="War" name="g17" /> War</td><td><input type="checkbox" value="Western" name="g18" /> Western</td></tr>
</table>
<input type="hidden" name="type" value="Movie" />
<?php
if (!$success2 && $post2)
	echo '<p id="ad_error" style="color: red">'.$error_msg2.'</p>';
else
	echo'<p id="ad_success" style="color:green">' . $success_msg2 . '</p>';

?>
<br />
<div class="submit">
<button type="submit">Insert</button>
</div>
</form>
</div>
</div>
</div>
</div>

<div class="form-wrap" id="form-wrap-id2" style="height: 250px; display: block; margin-top: 40px;">
<h1 class="form-header">Add to Movie</h1>
<div class="inner-form-wrap">
<form method="post" name="addToMovie" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
<table class="table-form">
<tr><td>Select One: </td><td><select name="aod"><option value="Actor" selected="selected">Actor</option><option value="Director">Director</option></select></td></tr>
<tr><td>First Name: </td><td><input id="a_first" name="first" type="text" /></td></tr>
<tr><td>Last Name:</td> <td><input id="a_last" name="last" type="text" /></td></tr>
<tr><td>Movie Title:</td> <td><input id="a_title" name="title" type="text" /></td></tr>
</table>
<input type="hidden" name="type" value="AddToMovie" />
<div class="submit">
<button type="submit">Insert</button>
</div>
</form>
</div>
</div>

</div>
</div>
</body>
</html>