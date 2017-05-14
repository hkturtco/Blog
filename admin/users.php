<?php

// include config
require_once('../includes/config.php');

//redirect the unauthorized user to login page
if(!$user->is_logged_in())
{
	header('Location: login.php');
	exit;
}

if(isset($_GET['deluser'])){
	if($_GET['deluser'] !='1'){
	$stmt = $db->prepare('SELECT username FROM blog_members WHERE memberID = :memberID');
	$stmt->execute(array(':memberID' => $_GET['deluser']));

	$row = $stmt->fetch();
	$row['username'];
	$stmt = $db->prepare('DELETE FROM blog_members WHERE memberID = :memberID');
	$stmt->execute(array(':memberID' => $_GET['deluser']));

	header('Location: users.php?action=deleted');
	if($_SESSION['user'] == $row['username']){
		header('Location: logout.php');
	}
	exit;
}
}

?>

	<?php include('menu.php');?>

	<script language="JavaScript" type="text/javascript">
	function deluser(id, title)
	{
		if(confirm("Are you sure you want to delete '"+ title + "'")){
			window.location.href = 'users.php?deluser='+id;
		}
	}
	</script>

	<?php

	if(isset($_GET['action'])){
		echo '<h3>User '.$_GET['action'].'.</h3>';
	}

	?>

	<table class="admin">
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Action</th>
	</tr>
	<?php
	try{
		$stmt = $db->query('SELECT memberID, username, email FROM blog_members ORDER BY username');
		while($row = $stmt->fetch()){
			echo '<tr>';
			echo '<td>'.$row['username'].'</td>';
			echo '<td>'.$row['email'].'</td>';
			?>

			<td>
				<a href="edit-user.php?id=<?php echo $row['memberID'];?>">Edit</a> |
				<?php if($row['memberID'] != 1){ ?>
				<a href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')">Delete </a>
				<?php } ?>
			</td>

			<?php

			echo '</tr>';
		}
	} catch (PDOException $e){
		echo $e->getMessage();
	}
	?>
	</table>

	<a href='add-user.php'><div class="managebutton">Add User</div></a>
	
</div>
</body>
</html>
