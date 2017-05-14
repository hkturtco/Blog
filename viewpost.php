<?php
	// include config
	require_once('./includes/config.php');
	$blog->update_post_view_count();
	$row = $blog->get_post_id();

	// header
	include('header.php'); 

	// get post
	$blog->get_view_post($row);

?>

	<!------------- Disque Comment Session ------------>

	<div id="commentsec">
		<div id="disqus_thread"></div>
	</div>

	<script src="../js/disque.js"></script>

	<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

<?php include('footer.php'); ?>
