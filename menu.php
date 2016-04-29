<ul class="tabs">
	<li><a href="<?php echo $website_url?>/shop.php   ">Shop			</a></li>
	<li><a href="<?php echo $website_url?>/profile.php">My Profile	</a></li>
	<li><a href="<?php echo $website_url?>/cart.php	">Cart			</a></li>
	<li><a href="<?php echo $website_url?>/redeem.php	">Redeem		</a></li>
	<li><a href="<?php echo $website_url?>/logout.php	">Logout		</a></li>
	<li>
		<div class="search_bar">
			<form>
				<input type="text" name="search" class="search_input" value="Search:" onblur="if (this.value == '') {this.value = 'Search:';}" onfocus="if (this.value == 'Search:') {this.value = '';}">
				<button type="button" name="search_btn"  id="search_btn"  class="btn btn-primary" id="submit_btn" onclick="search_redirect()" value="search">Go</button>
			</form>
		</div>
	</li> 
	<li>
		<a href="<?php echo $website_url?>/profile.php">
		<?php
			echo "Hello, ".$_COOKIE['username'];
		?>
		</a>
	</li>
</ul>

<script>
function search_redirect()
{
	var href ='shop.php?search=' + $('.search_input').val();
	document.location.href= href;
}

$(document).ready(function()
{
	$(".search_input").keyup(function(event){
	    if(event.keyCode == 13){
	       search_redirect();
	    }
	});
});
</script>