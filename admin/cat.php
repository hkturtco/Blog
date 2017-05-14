<?php

// include config
require_once('../includes/config.php');

//redirect the unauthorized user to login page
if(!$user->is_logged_in())
{
	header('Location: login.php');
	exit;
}

if (isset($_GET['delcat'])){
	$stmt = $db->prepare('DELETE FROM blog_cats WHERE catID = :catID');
	$stmt->execute(array(':catID' => $_GET['delcat']));

	$stmt2 = $db->prepare('DELETE FROM blog_cat_cats WHERE catID = :catID');
	$stmt2->execute(array(':catID' => $_GET['delcat']));

	header('Location: cat.php?action=deleted');
	exit;
}
?>

	<?php include('menu.php');?>

	<script language="JavaScript" type="text/javascript">
	function delcat(id, title)
	{
		if(confirm("Are you sure you want to delete '"+ title + "'")){
			window.location.href = 'cat.php?delcat=' + id;
		}
	}
	</script>


	<?php

	if(isset($_GET['action'])){
		echo '<h3>Category '.$_GET['action'].'.</h3>';
	}

	?>

	<table class="admin">
	<tr>
		<th>Title</th>
		<th>Action</th>
	</tr>
	<?php
	try{
		$stmt = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle DESC');
		while($row = $stmt->fetch()){
			echo '<tr>';
			echo '<td>'.$row['catTitle'].'</td>';
			?>

			<td>
				<a href="edit-cat.php?id=<?php echo $row['catID'];?>">Edit</a> |
				<a href="javascript:delcat('<?php echo $row['catID'];?>','<?php echo $row['catTitle'];?>')">Delete</a>
			</td>

			<?php

			echo '</tr>';
		}
	} catch (PDOException $e){
		echo $e->getMessage();
	}
	?>
	</table>

	<a href='add-cat.php'><div class="managebutton">Add Category</div></a>
	
</div>
</body>
</html>