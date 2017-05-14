<?php
	// include config
	require_once('./includes/config.php');
	
	// header
	include('header.php');

	// Sidebar
	include('sidebar.php');

	// Main blog
	$blog->get_blog_post($pages);	
		
	// footer
	include('footer.php'); 
?>
