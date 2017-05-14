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

	<h2>Edit User</h2>

	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST);

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if(strlen($password) >0){
		if($password ==''){
			$error[] = 'Please enter the password.';
		}
		if($passwordConfirm ==''){
			$error[] = 'Please enter the password.';
		}
		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}}
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if($email ==''){
			$error[] = 'Please enter the Email address.';
		}

		if(!isset($error)){

			try {
				if(isset($password)){
					$hashedpassword = $user->create_hash($password);

					$stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID =:memberID');
					$stmt->execute(array(
						':username' => $username, 
						':password' => $hashedpassword, 
						':email' => $email, 
						':memberID' => $memberID
				));
				} else {
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID =:memberID');
					$stmt->execute(array(
					':username' => $username, 
					':email' => $email, 
					':memberID' => $memberID
					));
				}	
				//redirect to homepage
				header('Location: users.php?action=updated');
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

	//Fetch the id and prepare the MySQL execution
	try {
		$stmt = $db->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID = :memberID');
		$stmt->execute(array(':memberID' => $_GET['id']));
		$row = $stmt->fetch();
	} catch (PDOException $e){
		echo $e->getMessage();
	}

	?>

	<form action='' method='post'>

		<input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>'>

		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php echo $row['username'];?>'></p>

		<p><label>Password (only to change)</label><br />
		<input type='password' name='password' value=''></p>

		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value=''></p>

		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php echo $row['email'];?>'></p>

		<p><input type='submit' name='submit' value='Update User'></p>
	</form>

</div>
</body>
</html>