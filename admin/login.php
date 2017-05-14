<?php

// include config
require_once('../includes/config.php');

//redirect the authorized user to index page
if($user->is_logged_in())
{
	header('Location: index.php');
}

?>

<?php include('../header.php'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<div id="wrapper">

<p><img src="../images/home.png" style="height:15px; width:15px;"> Current Index: <a href="../">Homepage</a> >> Administation Zone</p>

<div id="login">
	<?php

	//process login form if submitted
	if(isset($_POST['submit'])){

  	// your secret key
		$privatekey = "";
		$response = $_POST["g-recaptcha-response"];

		$url = 'https://www.google.com/recaptcha/api/siteverify';

		$verify = file_get_contents($url . '?secret=' . $privatekey . '&response=' . $response); // have to turn on httpd_verify_dns
		$captcha_success = json_decode($verify);
	// check secret key
	  	
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		if($captcha_success->success){			
			if($user->login($username,$password)){
				header('Location: index.php');
				exit;
			} else {
				$message = '<p class="error">Wrong username or password</p>';
			}		
	
		} else {			
			$message = '<p class="error">Please prove you are not a robot.</p>';
		}
	}

	//end if submit
	if (isset($message)){
		echo $message;
	}

	?>

	<form id='loginform' action= "" method="post">
	<p><label>Username: </label><input type="text" name="username" value="" /> </p>
	<p><label>Password: </label><input type="password" name="password" value="" /> </p>
	<div class="g-recaptcha" data-sitekey=""></div>
	<p><input type="submit" name="submit" value="login" /> </p>
	
	</form>
	
</div>

<?php include('../footer.php'); ?>