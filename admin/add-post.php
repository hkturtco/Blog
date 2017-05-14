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
	
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<script>
		tinymce.init({
			selector: "textarea",
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
		});
	</script>

	<h2>Add Post</h2>

	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST);

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

	

	if(!isset($error)){

		try {
		$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate, postUser) VALUES (:postTitle, :postDesc, :postCont, :postDate, :postUser)');
		$stmt->execute(array(
			':postTitle' => $postTitle, 
			':postDesc' => $postDesc, 
			':postCont' => $postCont, 
			':postDate' => date('Y-m-d H:i:s'),
			':postUser' => $user->cur_username()
			));

		$postID = $db->lastInsertId();

		//add category
		//if(is_array($catID)){
			foreach($_POST as $catvalue){
				$ex = explode('-', $catvalue);
						echo var_dump($ex);
						if($ex[0]=='catlist'){
						$stmt = $db->prepare('INSERT INTO blog_cat_cats (postID, catID) VALUES (:postID, :catID)');
						$stmt->execute(array(
							':postID' => $postID, 
							':catID' => $ex[1]
						));
					}
			}
		//}

		//redirect to homepage
		header('Location: index.php?action=added');
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
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];} ?>'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='30'><?php if(isset($error)){ echo $_POST['postDesc'];} ?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='30'><?php if(isset($error)){ echo $_POST['postCont'];} ?></textarea></p>
		
		<fieldset>
		<legend><p>Categories</p></legend>
		<?php
		$stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
		while($row2 = $stmt2->fetch()){
			if(isset($_POST['catID'])){
				if(in_array($row2['catID'], $_POST['catID'])){
					$checked = "checked='checked'";
				} else {
					$checked = "";
				}
			} else {
				$checked = "";
			}
			echo "<p><input type='checkbox' name='catlist-".$row2['catID']."' value='catlist-".$row2['catID']."' $checked> ".$row2['catTitle']."<br/></p>";
		}
		?>
		</fieldset>
			
		<p><input type='submit' name='submit' value='Submit'></p>
	</form>
	
	<!--upload file -->
	<form action='upload.php' method='post'  target="_blank" enctype='multipart/form-data' style="background-color:rgba(255, 255,255,0.4)">
			<fieldset style="font-size:12px;">
			<legend><p>Select file to upload: </p></legend>
			<input type='file' name='uploadfile' id=uploadfile'>
			<input type='submit' name='submit' value='Submit'>
			</fieldset>
		</form>
	

</div>
</body>
</html>