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

	<h2>Edit Category</h2>

	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST);

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if($catID ==''){
			$error[] = 'This category is missing a valid id.';
		}
		if($catTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if(!isset($error)){

			try {
				$stmt = $db->prepare('UPDATE blog_cats SET catTitle = :catTitle WHERE catID =:catID');
				$stmt->execute(array(
					':catTitle' => $catTitle, 
					':catID' => $catID
					));

				//redirect to homepage
				header('Location: cat.php?action=updated');
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

	try {
		$stmt = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catID = :catID');
		$stmt->execute(array(':catID' => $_GET['id']));
		$row = $stmt->fetch();
	} catch (PDOException $e){
		echo $e->getMessage();
	}

	?>

	<form action='' method='post'>

		<input type='hidden' name='catID' value='<?php echo $row['catID'];?>'>

		<p><label>Title</label><br />
		<input type='text' name='catTitle' value='<?php echo $row['catTitle'];?>'></p>

		<p><input type='submit' name='submit' value='Update'></p>
	</form>

</div>
</body>
</html>