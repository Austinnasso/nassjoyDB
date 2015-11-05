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
function formatRating(obj)
{
	$(obj).mask('0');
}
</script>
<?php include "header.php" ?>
<?php
$post=0; 
$success=0;
$error_msg;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$rating=htmlspecialchars($_POST['rating']);
	$title=htmlspecialchars($_POST['m_title']);
	$username=htmlspecialchars($_POST['username']);
	$comment=htmlspecialchars($_POST['comment']);
	$empty_err=$rating_err=$check_movie=0;
	$post=1;
	if(empty($title)||empty($rating)||empty($username)) {
		$empty_err=1;
	}
	else {
		if($rating>5||$rating<0) {
			$rating_err=1;
		}
		else {
			$sql="SELECT * FROM Movie WHERE title='{$title}'";
			$result = mysql_query($sql, $db_connection);
			if (mysql_num_rows($result) == 0) {
				$error_msg="Movie doesn't exist!";
				$check_movie=1;
			}
			else {
				$date = new DateTime();
				$sql_getMovie="SELECT id FROM Movie WHERE title='{$title}'";
				$value_mid=mysql_fetch_object(mysql_query($sql_getMovie));
				$id_movie=$value_mid->id;
				$sql = "INSERT INTO Review (name, time, mid, rating, comment) VALUES ('{$username}', NOW(), '{$id_movie}', '{$rating}', '{$comment}')";
				$result = mysql_query($sql, $db_connection);
				if (!$result) {
					die('Invalid query: ' . mysql_error());
				}
				else
					$success=1;
			}
		}
	}
	if($empty_err) {
		$error_msg="*Invalid input. Try Again.";
	}
	if($rating_err) {
		$error_msg="Rating must be an integer between 0 and 5.";
	}
}
?>
<div id="content">
<div class="review-box">
<h1 class="form-header">Write a review</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
<table class="table-form">
<tr><td>Username:</td><td><input id="username" name="username" type="text" /></td></tr>
<tr><td>Movie Title:</td><td><input id="m_title" name="m_title" type="text" /></td></tr>
<tr><td>Rating:</td> <td><input onfocus="formatRating(this)" id="rating" name="rating" type="number" min="1" max="5" size="2" maxlength="2" /></td></tr>
</table><br />
<textarea cols="55" rows="8" style="resize:none" id="comment" name="comment"></textarea><br /><br />
<div class="submit">
<button type="submit">Enter</button>
</div>
<?php 

if (!$success && $post)
	echo '<p id="ad_error" style="color: red">'.$error_msg.'</p>'; 
else if ($success && $post)
	echo'<p id="ad_success" style="color:green">Review Successfully Inserted!</p>';
?>
</form>
</div>
</div>

</div>
</body>
</html>