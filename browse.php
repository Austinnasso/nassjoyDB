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
<LINK REL=StyleSheet HREF="main.css" TYPE="text/css" MEDIA=screen>
</head>

<body>
<?php include "header.php" ?>
<div id="content">

<?php
	$type = $_GET['type'];
	$id = $_GET['id']; 
	
	if (!empty($type) && !empty($id))
	{
		if ($type == "Person")
		{
			$sql = "SELECT * FROM Actor WHERE id='{$id}'"; 
			$actor_result = mysql_query($sql, $db_connection);
			$isActor = $isDirector = 0;
			if (mysql_num_rows($actor_result))
				$isActor = 1;
				
			if ($isActor)
			{
				$row = mysql_fetch_array($actor_result, MYSQL_ASSOC); 
				$first = $row['first'];
				$last = $row['last']; 
				$dob = $row['dob'];
				$dod = $row['dod'];
				$gender = $row['sex'];
				$dob = date_create($dob); 
				$dob = date_format($dob, "m/d/y");
				
				if (!empty($dod))
				{
					$dod = date_create($dod); 
					$dod = date_format($dod, "m/d/y");
				}
				
			}
			
			$sql = "SELECT * FROM Director WHERE id='{$id}'"; 
			$director_result = mysql_query($sql, $db_connection);
			if (mysql_num_rows($director_result))
				$isDirector = 1;
			
			//NOT FOUND IF NEITHER ACTOR NOR DIRECTOR
			if (!$isActor && !$isDirector)
				goto label1; 
				
			
			//IF ONLY ACTOR, STORE INFO INTO VARIABLES
			if ($isDirector && !$isActor)
			{
				$row = mysql_fetch_array($director_result, MYSQL_ASSOC); 
				$first = $row['first'];
				$last = $row['last']; 
				$dob = $row['dob'];
				$dod = $row['dod'];
				$dob = date_create($dob); 
				$dob = date_format($dob, "m/d/y");
				
				if (!empty($dod))
				{
					$dod = date_create($dod); 
					$dod = date_format($dod, "m/d/y");
				}
				//PRINT NAME
				echo "<h1 class='search_header' style='text-align:center'>{$first} {$last} (Director)</h1>"; 
			}
		
			else if (!$isDirector && $isActor)
			{
				if ($gender == "Male")
					echo "<h1 class='search_header' style='text-align:center'>{$first} {$last} (Actor)</h1>"; 
				else
					echo "<h1 class='search_header' style='text-align:center'>{$first} {$last} (Actress)</h1>"; 
			}
			
			else if ($isDirector && $isActor)
			{
				if ($gender == "Male")
					echo "<h1 class='search_header' style='text-align:center'>{$first} {$last} (Actor/Director)</h1>"; 
				else
					echo "<h1 class='search_header' style='text-align:center'>{$first} {$last} (Actress/Director)</h1>"; 
			}
			
			echo "<p class='person_info'>Date of birth: {$dob} </p>";
			if (empty($dod)) 
				echo "<p class='person_info'>Date of death: N/A </p>"; 
			else
				echo "<p class='person_info'>Date of death: {$dod} </p>"; 
				
			//PRESENT MOVIES ACTED IN IN TABLE FORM
			if ($isActor)
			{
				$sql = "SELECT MovieActor.mid, MovieActor.aid, MovieActor.role, Movie.title, Movie.year FROM MovieActor INNER JOIN Movie ON MovieActor.mid = Movie.id WHERE MovieActor.aid ={$id}"; 
				$movies = mysql_query($sql, $db_connection);
				if (!mysql_num_rows($movies))
					echo "<h2>No movie roles on record.</h2>";
				else
				{
					echo "<table class='info-table'><tr><th>Movie</th><th>Role</th><th>Year</th></tr>";
					while ($row = mysql_fetch_array($movies, MYSQL_ASSOC))
						echo "<tr><td>{$row['title']}</td><td>{$row['role']}</td><td>{$row['year']}</td></tr>";
					echo "</table>"; 
				}
				
				
				
			}
			
			if ($isDirector)
			{
				$sql = "SELECT MovieDirector.mid, MovieDirector.did, Movie.title, Movie.year FROM MovieDirector INNER JOIN Movie ON MovieDirector.mid = Movie.id WHERE MovieDirector.did ={$id}"; 
				$movies = mysql_query($sql, $db_connection);
				if (!mysql_num_rows($movies))
					echo "<h2>No movies directed on record.</h2>";
				else
				{
					echo "<table class='info-table'><tr><th>Movies Directed</th><th>Year</th></tr>";
					while ($row = mysql_fetch_array($movies, MYSQL_ASSOC))
						echo "<tr><td>{$row['title']}</td><td style='width:100px;'>{$row['year']}</td></tr>";
					echo "</table>"; 
				}
			
				
			}
				
		
			
	
	
		}
		
		else if ($type == "Movie")
		{
			$sql = "SELECT * FROM Movie WHERE id='{$id}'"; 
			$result = mysql_query($sql, $db_connection);
			if (!mysql_num_rows($result))
				goto label1; 
			
		}
		
		
	}
	
	else
	{
		label1: 
			echo "<h1 class='search_header' style='text-align:center'> Page you're looking for cannot be found. </h1>"; 
	}
	
	



?>



</div>
</body>
</html>