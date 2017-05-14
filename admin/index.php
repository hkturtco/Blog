<?php

	// include config
	require_once('../includes/config.php');

	//redirect the unauthorized user to login page
	if(!$user->is_logged_in())
	{
		header('Location: login.php');
		exit;
	}

	if(isset($_GET['delpost'])){
		$stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID');
		$stmt->execute(array(':postID' => $_GET['delpost']));

		$stmt = $db->prepare('DELETE FROM blog_cat_cats WHERE postID = :postID');
		$stmt->execute(array(':postID' => $_GET['delpost']));

		header('Location: index.php?action=deleted');
		exit;
	}
	include('menu.php');

	if(isset($_GET['action'])){
		echo '<h3>Post '.$_GET['action'].'.</h3>';
		header("refresh:1;url=index.php");
	}

	?>

	<script language="JavaScript" type="text/javascript">
		function delpost(id, title)
		{
			if(confirm("Are you sure you want to delete '"+ title + "'")){
				window.location.href = 'index.php?delpost=' + id;
			}
		}
	</script>

	<table class="admin">
	<tr>
		<th>Title</th>
		<th>Date</th>
		<th>Action</th>
	</tr>
	<?php
		try{
			$stmt = $db->query('SELECT postID, postTitle, postDate FROM blog_posts ORDER BY postID DESC');
			while($row = $stmt->fetch()){
				echo '<tr>';
				echo '<td>'.$row['postTitle'].'</td>';
				echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
				?>

				<td>
					<a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> |
					<?php $pattern = addslashes($row['postTitle']); ?>
					<a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $pattern;?>')">Delete </a>
				</td>

				<?php

				echo '</tr>';
			}
		} catch (PDOException $e){
			echo $e->getMessage();
		}
	?>
	</table>
<a href='add-post.php'><div class="managebutton">Add Post</div></a>
	
	
</div>
</body>
</html>