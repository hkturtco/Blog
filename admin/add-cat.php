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

	<h2>Add Category</h2>

	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST);

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if($catTitle ==''){
			$error[] = 'Please enter the Category.';
		}

	

	if(!isset($error)){

		try {
		$stmt = $db->prepare('INSERT INTO blog_cats (catTitle) VALUES (:catTitle)');
		$stmt->execute(array(
			':catTitle' => $catTitle, 
			));

		//redirect to homepage
		header('Location: cat.php?action=added');
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
		<p><label>Title</label><br />
		<input type='text' name='catTitle' value='<?php if(isset($error)){ echo $_POST['catTitle'];} ?>'></p>

		<p><input type='submit' name='submit' value='Submit'></p>
	</form>

</div>
</body>
</html>