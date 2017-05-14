<?php
	require_once('./includes/config.php');

	$suggestw = $_REQUEST["suggestw"];
	$result = $blog->get_search_suggestion($suggestw);
	echo $result;
?>