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
				"insertdatetime media table contextmenu paste",
				"emoticons textcolor",
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignright aligncenter alignright alignjustify | bullist numlist outdent indent | link image | emoticons | forecolor backcolor"
		});
	</script>

	
	<?php

	//submit the form and process the submission
	if(isset($_POST['submit'])){
		
		$_POST = array_map( 'stripslashes', $_POST);

		//collect form data
		extract($_POST);

		//Check the valid input from the form
		if($postID ==''){
			$error[] = 'This post is missing a valid id.';
		}
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
				$stmt = $db->prepare('UPDATE blog_posts SET postTitle = :postTitle, postDesc = :postDesc, postCont = :postCont WHERE postID =:postID');
				$stmt->execute(array(
					':postTitle' => $postTitle, 
					':postDesc' => $postDesc, 
					':postCont' => $postCont, 
					':postID' => $postID
					));

				$stmt = $db->prepare('DELETE FROM blog_cat_cats WHERE postID = :postID');
				$stmt->execute(array(':postID' => $postID));

				$catlist = array();

				// if(isset($_POST['catIDlist'])){
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
			 	 // }



				//redirect to homepage
				header('Location: index.php?action=updated');
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
		$stmt = $db->prepare('SELECT postID, postTitle, postDesc, postCont FROM blog_posts WHERE postID = :postID');
		$stmt->execute(array(':postID' => $_GET['id']));
		$row = $stmt->fetch();
	} catch (PDOException $e){
		echo $e->getMessage();
	}

	?>

	<form  action='' method='post'>
		<h2>Edit Post</h2>

		<input type='hidden' name='postID' value='<?php echo $row['postID'];?>'>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php echo $row['postTitle'];?>'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='30'><?php echo $row['postDesc'];?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='30'><?php echo $row['postCont'];?></textarea></p>

		<fieldset>
		<legend><p>Categories</p></legend>
		<?php
		$stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
		while($row2 = $stmt2->fetch()){

			$stmt3 = $db->prepare('SELECT catID FROM blog_cat_cats WHERE catID=:catID AND postID=:postID');
			$stmt3->execute(array(':catID'=> $row2['catID'],':postID'=> $row['postID']));
			$row3 = $stmt3->fetch();

			if($row3['catID'] == $row2['catID']){
				$checked = 'checked="checked"';
			} else {
				$checked = '';
				
			}?>
		<p><input type='checkbox' name='catlist-<?= $row2['catID']; ?>' value='catlist-<?php echo $row2['catID']; ?>' <?php echo $checked;?>> <?php echo $row2['catTitle']; ?><br/></p>
		<?php }
		?>
		</fieldset>

		<p><input type='submit' name='submit' value='Update'></p>
	</form>
	
		<!--upload file -->
	<form action='upload.php' method='post'  target="_blank" enctype='multipart/form-data' >
			<fieldset style="font-size:12px;">
			<legend><p>Select file to upload: </p></legend>
			<input type='file' name='uploadfile' id=uploadfile'>
			<input type='submit' name='submit' value='Submit'>
			</fieldset>
		</form>

</div>
</body>
</html>