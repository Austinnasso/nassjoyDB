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

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['search_bar']))
{
	
	$raw_input = explode(" ", $_GET['search_bar']); 
	$sqlActor = "SELECT * FROM Actor WHERE ";
	$sqlDirector = "SELECT * FROM Director WHERE ";
	$sqlMovie = "SELECT * FROM Movie WHERE ";
	$first = 1;
	foreach ($raw_input as $x)
	{
		if ($first)
		{
			$add1 = "(first LIKE '%" . $x . "%' OR last LIKE '%" . $x . "%') ";
			$sqlActor = $sqlActor . $add1;
			$sqlDirector = $sqlDirector . $add1; 
			$sqlMovie = $sqlMovie . "title LIKE '%" . $x . "%' ";
			$first = 0;
		}
		
		else 
		{
			$add1 = "AND (first LIKE '%" . $x . "%' OR last LIKE '%" . $x . "%') ";
			$sqlActor = $sqlActor . $add1;
			$sqlDirector = $sqlDirector . $add1;
			$sqlMovie = $sqlMovie . "AND title LIKE '%" . $x . "%' ";
		}
		
	}	
	
	
	//QUERY DATABASE
	$actors = mysql_query($sqlActor, $db_connection);
	$num_actors = mysql_num_rows($actors); 
	$results = " Results"; 
	
	if ($num_actors == 1)
		$results = " Result";
		

	echo '<h1 class="search_header">' . $num_actors . $results . ' in Actors' . '</h1>'; 
	$index = 1;
	while ($row = mysql_fetch_array($actors, MYSQL_ASSOC)) 
	{
		echo "<p>{$index}. <a href='browse.php?id={$row['id']}&type=Person'>{$row['first']} {$row['last']}</a></p>";
		$index++;
	}
	
	$directors = mysql_query($sqlDirector, $db_connection);
	$num_directors = mysql_num_rows($directors); 
	
	if ($num_directors == 1)
		$results = " Result";
	else
		$results = " Results";
	echo '<br /><h1 class="search_header">' . $num_directors . $results . ' in Directors' . '</h1>'; 
	$index = 1;
	while ($row = mysql_fetch_array($directors, MYSQL_ASSOC)) 
	{
		echo "<p>{$index}. <a href='browse.php?id={$row['id']}&type=Person'>{$row['first']} {$row['last']}</a></p>";
		$index++;
	}
	
	$movies = mysql_query($sqlMovie, $db_connection);
	$num_movies = mysql_num_rows($movies); 
	
	if ($num_movies == 1)
		$results = " Result";
	else
		$results = " Results";
	echo '<br /><h1 class="search_header">' . $num_movies . $results . ' in Movies' . '</h1>'; 
	$index = 1;
	while ($row = mysql_fetch_array($movies, MYSQL_ASSOC)) 
	{
		echo "<p>{$index}. <a href='browse.php?id={$row['id']}&type=Movie'>{$row['title']}</a></p>";
		$index++;
	}
}
else
	echo '<h1 class="search_header">Use search bar above to search our records!</h1>';

?>

</div>


</div>
</body>
</html>