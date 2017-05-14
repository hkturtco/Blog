<!doctype html>

<html lang = "en">
<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link rel="stylesheet" href="../style/main.css">
	<link rel="stylesheet" href="../style/admin.css?v=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
</head>
<body>

<div class="manage">

	<h1>Blog Administration</h1>
	<p style="color:gray; font-weight:bold">You are currently accessing the account, <?php echo $_SESSION['user']; ?></p>
	
	<ul id='adminmenu' >
		<li><a href='index.php'>Blog</a></li>
		<li><a href='cat.php'>Categories</a></li>
		<li><a href='users.php'>Users</a></li>
		<li><a href="../" target="_blank">View Website</a></li>
		<li><a href='logout.php'>Logout</a></li>
	</ul>
	
	<div class='clear'></div>