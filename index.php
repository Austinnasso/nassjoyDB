<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nassjoy Database</title>
<LINK REL=StyleSheet HREF="main.css" TYPE="text/css" MEDIA=screen>
</head>

<body>
<?php include "header.php" ?>

<h1> 
<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	
	$raw_input = explode(" ", $_GET['search_bar']); 
	$sql = "SELECT * FROM Actor WHERE ";
	foreach ($raw_input as $x)
		$sql = $sql . "first
	
	
}

?>
</h1>

</div>


</body>
</html>