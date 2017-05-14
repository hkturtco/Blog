<?php

	// include config
	require_once('./includes/config.php');

	$query = $_GET['query'];
	$query = htmlspecialchars($query);


	include('header.php'); 

	$blog->get_search_result($query);

	include('footer.php'); 

?>