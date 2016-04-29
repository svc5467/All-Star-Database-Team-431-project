<?php
	include "variables.php";

	$error = "";
	$redirection_notice = "You must login to access that page";
	$access_denied = "";
	$dbh = new PDO("sqlite:".$database_url);
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		// Checks that the fields were filled.
     	if($password && $username)
     	{
     		// Counts the number of rows that have the same email. The number is stored in variable "count".
			$statement = $dbh->query("SELECT COUNT(*) FROM Sellers WHERE login_name='".$username."'")->fetch();
			$count = $statement[0];
						
			// If the user exists, we continue, if not, error is thrown.
			if($count != 0)
			{
				$result = $dbh->query("SELECT * FROM Sellers WHERE login_name='".$username."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
				
				$dbpassword = $result['pass'];
			
				if($password == $dbpassword)
				{
					// user as been authenticated
					// set cookie
					setcookie('username', $_POST['username'], time()+60*60);
					header("location: $website_url/shop.php");
				}
				else
				{
					$error = "<font color=\"red\" size=\"2\">Try again.</font>";
				}
			}
			else
			{
				$error = "<font color=\"red\" size=\"2\">Try again.</font>";
			}
     	}
     	else
     	{
	     	$error = "<font color=\"red\" size=\"2\"> Please complete all fields.</font>";
     	}
	}
	else
	{
		if(isset($_GET['access_denied']))
			$access_denied = $_GET['access_denied'];

		$username = "";
		$password = "";
	}
		

?>

<html>
	<body>
		<h1>
			Login
		</h1>
		<h6>
			<?php 
				echo $error; 
				if ($access_denied == "true")
					echo $redirection_notice
			?>
		</h6>
		
		<form method="post" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<table>
				<tr>
					<td>
						<input type="text" name="username" maxlength="10" size="15" placeholder="Username"
						 value="<?php echo $username;?>">
					</td>
				</tr>
				<tr>
					<td>
						<input type="password" name="password" maxlength="50" size="15" placeholder="Password"> 
					</td>
				</tr>
			</table>
			<p>
				<!--<a href="javascript:history.go(-1)" class="links">&#8592; Back</a>-->
				<input type="submit" class="links" value="Login">
			</p>
		</form>
	</body>
</html>