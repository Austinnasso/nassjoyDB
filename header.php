<script type="text/javascript">

function focusTextGlow(obj)
{
	var x = document.getElementsByClassName("search_textbox");
	x[0].classList.add("boxShadowSubscribe");
}


function blurTextGlow(obj)
{
	var x = document.getElementsByClassName("search_textbox");
	x[0].classList.remove("boxShadowSubscribe");
}


</script>

<div class="content-div">
<h1 id="title"> Nassjoy Movie Database </h1>

<div id="user_menu">
<div class="center" style="width: 90%;">
<a href="index.php">
<div class="user_menu_item" style="margin-left: 20px;">
<p>Search</p>
</div>
</a>

<a href="addactor.php">
<div class="user_menu_item">
<p>Contribute</p>
</div>
</a>

<a href="addmovie.php">
<div class="user_menu_item">
<p>Add Movie</p>
</div>
</a>


<a>
<div class="user_menu_item">
<p>Comment</p>
</div>
</a>

<a>
<div class="user_menu_item">
<p>Actor2Movie</p>
</div>
</a>

<a>
<div class="user_menu_item">
<p>Director2Movie</p>
</div>
</a>
</div>
</div>

<div class="search-wrap">
<div class="search_textbox">
<form id="search">
<input class="user_input" id="search_bar" type="text" name="search_bar" value="Search movies, actors, directors and more..." onblur="blurTextGlow(this); if(this.value==''){ this.value='Search movies, actors, directors and more...'; this.style.color='#BBB';}" onfocus="focusTextGlow(this); if(this.value=='Search movies, actors, directors and more...'){this.value=''; this.style.color='#000';}" onmouseover="this.style.color='#000';" onmouseout="if(this.value=='Search movies, actors, directors and more...'){this.style.color='#BBB'}"/>
</form>
</div>
</div>
