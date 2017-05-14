<div id='sidebar'>
	<!-------------------- Profile picture ------------------------>
	<div class="propiccontainer">
		<a href="../images/profile.jpeg" data-lightbox="roadtrip" data-title="Marlon C.'s profile picture">
			<img src="../images/profile.jpeg" id="propic">
		</a>
		<div class="propiccenter">Marlon C.</div>
	</div>

	<!-------------------- Recent post ------------------------>
	<h5 style="text-align: left;"># Recent Posts</h5>
	<div class="subside">
	<ul>
		<?php
			$blog->get_recent_post();
		?>
	</ul>
	</div>
	<br/>

	<!-------------------- Popular post ------------------------>
	<h5 style="text-align: left;"># Popular Posts</h5>
	<div class="subside">
		<ul>
			<?php
				$blog->get_popular_post();
			?>
		</ul>
	</div>
</div>