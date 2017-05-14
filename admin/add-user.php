<?php

// include config
require_once('../includes/config.php');

//redirect the unauthorized user to login page
if(!$user->is_logged_in())
{
	header('Location: login.php');
	exit;
}
?>

	<?php include('menu.php');?>
	
	<h2>Add User</h2>

	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if($password ==''){
			$error[] = 'Please enter the password.';
		}
		if($passwordConfirm ==''){
			$error[] = 'Please enter the password.';
		}
		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}
		if($email ==''){
			$error[] = 'Please enter the Email address.';
		}

	

	if(!isset($error)){

		$hashedpassword = $user->create_hash($password);

		try {
		$stmt = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username, :password, :email)');
		$stmt->execute(array(
			':username' => $username, 
			':password' => $hashedpassword, 
			':email' => $email, 
			));

		//redirect to homepage
		header('Location: users.php?action=added');
		exit;
	} catch (PDOException $e){
		echo $e->getMessage();
	}
	}
}

	if (isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}

	?>

	<form action='' method='post'>
		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

		<p><label>Password</label><br />
		<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>

		<p><input type='submit' name='submit' value='Add User'></p>
	</form>

</div>
</body>
</html>