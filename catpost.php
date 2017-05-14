<?php

	// include config
	require_once('./includes/config.php');
	$row = $blog->get_blog_cat_id_and_name();

	// header
	include('header.php');

	// Post in the category
	$blog->get_blog_cat_post($row['catID'], $row['catTitle']);

	// footer
	include('footer.php'); 
?>