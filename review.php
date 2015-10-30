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
<div id="content">
<div class="review-box">
<h1 class="form-header">Write a review</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
<table class="table-form">
<tr><td>Username:</td><td><input id="username" name="username" type="text" /></td></tr>
<tr><td>Movie Title:</td><td><input id="m_title" name="title" type="text" /></td></tr>
<tr><td>Rating:</td> <td><input onfocus="formatRating(this)" id="rating" name="rating" type="number" min="1" max="5" size="2" maxlength="2" /></td></tr>
</table><br />
<textarea cols="55" rows="8" style="resize:none"></textarea><br /><br />
<div class="submit">
<button type="submit">Enter</button>
</div>
</form>
</div>
</div>

</div>
</body>
</html>