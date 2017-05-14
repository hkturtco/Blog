<?php

class Blog{

	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	public function get_blog_post() {

		echo "<div id='wrapper'>";

		$pages = new Paginator('5','p');

		// Current location
		echo "<div class='curlocation'><p><img src='../images/home.png' style='height:15px; width:15px;'> Current Index: <a href='./'>Homepage</a></p></div>";

		echo "<div id='main'>";

		try {
				$stmt = $this->db->query('SELECT postID FROM blog_posts');
				$pages->set_total($stmt->rowCount());

				$stmt = $this->db->query('SELECT postID, postTitle, postDesc, postDate, postCount, postUser FROM blog_posts ORDER BY postID DESC '.$pages->get_limit());

				while($row = $stmt->fetch()){
					
					if($row['postUser']=='root'){
						$row['postUser']='Marlon C.';
					}

					echo '
					<div id="blogbox">
						<h1 class="blogtitle"><img src="./images/art.png" style="height:18px; width:18px;"> <a href="viewpost?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>
					<div class="blogcontent">

					<p>'.$row['postDesc'].'</p>
					
					</div> <div class="clear"></div>

					<p align="center"><a href="viewpost?id='.$row['postID'].'"><button>Read More</button></a></p>
					<p align="right" style="color:rgba(255,255,255,0.5);font-family:Abel;"><img src="./images/time.png" style="height:13px; width:13px;"> Posted by <span style="color:#ff5500; font-size:15px;"><b>' . $row['postUser'] .'</b></span> on '.date('jS M Y H:i:s',strtotime($row['postDate'])).' in ';


					$stmt2 = $this->db->prepare('SELECT blog_cats.catID, catTitle FROM blog_cats, blog_cat_cats WHERE blog_cats.catID = blog_cat_cats.catID AND blog_cat_cats.postID = :postID');
					$stmt2->execute(array(':postID'=> $row['postID']));

					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

					$links = array();
					
					foreach($catRow as $cat){
						$links[] = "<a href='catpost?id=".$cat['catID']."'>".$cat['catTitle']."</a>";
					}
					
					echo implode(", ", $links);

					echo ' with '.$row['postCount'].' Views</p>';
					echo'</div>';
				}
				
		} catch(PDOException $e){
			echo $e->getMessage();
		}

		echo "<div class='pagination' align='center'>";
		echo $pages->page_links();
		echo "</div>";
		echo "</div>";
	}

	public function get_blog_cat_list(){

		try{
			$stmt4 = $this->db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle DESC');
			while($row4 = $stmt4->fetch()){
				echo "<a href='../catpost?id=".$row4['catID']."'>".$row4['catTitle']."</a>";
			}
		} catch (PDOException $e){
			echo $e->getMessage();
		}
	}

	public function get_blog_cat_id_and_name(){
		$stmt = $this->db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catID=:catID');
		$stmt->execute(array(':catID'=> $_GET['id']));
		$row = $stmt->fetch();

		if($row['catID'] == ''){
			header('Location: ./');
			exit;
		}
		return $row;
	}

	public function get_blog_cat_post($catID, $catTitle){

		echo "<div id='wrapper'>";

		echo "<div class='curlocation'>
		<p><img src='./images/home.png' style='height:15px; width:15px;''> Current Index: <a href='./''>Homepage</a> >> Posts in " . $catTitle . "</p></div>";
		
		try {
			
			$stmt = $this->db->prepare('SELECT blog_posts.postID, blog_posts.postTitle, blog_posts.postDesc, blog_posts.postDate FROM blog_posts, blog_cat_cats WHERE blog_posts.postID = blog_cat_cats.postID AND blog_cat_cats.catID = :catID ORDER BY postID DESC');
			$stmt->execute(array(':catID'=> $catID));

			while($row = $stmt->fetch()){

				echo '<div id="blog">';
				echo '<h1 class="blogtitle"><img src="./images/art.png" style="height:20px; width:20px;"> <a href="viewpost?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>';

				echo '<div class="blogcontent">';
				echo '<p>'.$row['postDesc'].'</p>';
				echo '</div> <div class="clear"></div>';

				echo '<p align="right"><a href="viewpost?id='.$row['postID'].'">Read More</a></p>';
				
				echo '<p align="right" style="color:rgba(255,255,255,0.5);font-family:Abel;"><img src="./images/time.png" style="height:13px; width:13px;"> Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).' in ';

				$stmt2 = $this->db->prepare('SELECT blog_cats.catID, catTitle FROM blog_cats, blog_cat_cats WHERE blog_cats.catID = blog_cat_cats.catID AND blog_cat_cats.postID = :postID');
				$stmt2->execute(array(':postID'=> $row['postID']));

				$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

				$links = array();
				foreach($catRow as $cat){
					$links[] = "<a href='catpost?id=".$cat['catID']."'>".$cat['catTitle']."</a>";
				}
				echo implode(", ", $links);
				
				echo '</p>';
			echo'</div>';
			echo '<p>';
			}
		} catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function get_search_result($query){

		echo "<div id='wrapper'>";

		echo "<div class='curlocation'><p><img src='./images/home.png' style='height:15px; width:15px;'> Current Index: <a href='./'>Homepage</a> >> Posts with the keyword \"" . $query . "\"</p></div>";

		try {
			$stmt = $this->db->prepare('SELECT * FROM blog_posts WHERE postTitle LIKE :query OR postCont LIKE :query OR LOWER(postTitle) LIKE :query OR LOWER(postCont) LIKE :query OR UPPER(postTitle) LIKE :query OR UPPER(postCont) LIKE :query');
			$stmt->execute(array(':query'=> '%'.$query.'%'));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 0 || $query==''){
				echo '<div style="color:red; height:200px;" align="middle">Sorry, We cannot find the result.</div>';
			} else {
				$stmt->execute(array(':query'=> '%'.$query.'%'));

				while($row = $stmt->fetch()){

					echo '<div id="blog">';
					echo '<h1 class="blogtitle"><img src="./images/art.png" style="height:20px; width:20px;"> <a href="viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>';
					echo '<hr/>';
					
					echo '<div class="blogcontent">';
					echo '<p>'.$row['postDesc'].'</p>';
					echo '</div> <div class="clear"></div>';
					
					echo '<p align="right"><a href="viewpost.php?id='.$row['postID'].'">Read More</a></p>';
					
					echo '<p align="right" style="color:rgba(255,255,255,0.5);font-family:Abel;"><img src="./images/time.png" style="height:13px; width:13px;"> Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).' in ';

					$stmt2 = $this->db->prepare('SELECT blog_cats.catID, catTitle FROM blog_cats, blog_cat_cats WHERE blog_cats.catID = blog_cat_cats.catID AND blog_cat_cats.postID = :postID');
					$stmt2->execute(array(':postID'=> $row['postID']));

					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

					$links = array();
					foreach($catRow as $cat){
						$links[] = "<a href='catpost.php?id=".$cat['catID']."'>".$cat['catTitle']."</a>";
					}
					echo implode(", ", $links);
					
					echo '</p>';
					echo'</div>';
					echo '<p>';
				}
			}

		} catch(PDOException $e){

			echo $e->getMessage();
		}
	}

	public function get_search_suggestion($query){

		try {
			$stmt = $this->db->prepare('SELECT postTitle FROM blog_posts WHERE postTitle LIKE :query OR postCont LIKE :query OR LOWER(postTitle) LIKE :query OR LOWER(postCont) LIKE :query OR UPPER(postTitle) LIKE :query OR UPPER(postCont) LIKE :query');
			$stmt->execute(array(':query'=> '%'.$query.'%'));

			if($stmt->rowCount() == 0 || $query==''){
				return '';
			} else {
				while($row = $stmt->fetch()){
					echo "[" . $row['postTitle'] . "]&nbsp;&nbsp;&nbsp;&nbsp;";
				}
			}

		} catch(PDOException $e){

			echo $e->getMessage();
		}
	}

	public function get_recent_post(){

			$stmt = $this->db->query('SELECT postTitle, postID FROM blog_posts ORDER BY postID DESC LIMIT 10');
			while($row = $stmt->fetch()){
				echo '<li><a href="./viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a></li>';
			}
	}

	public function get_popular_post(){

			$stmt = $this->db->query('SELECT postCount, postTitle, postID FROM blog_posts ORDER BY postCount DESC LIMIT 10');
			while($row = $stmt->fetch()){
				echo '<li><a href="./viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a> ('.$row['postCount'].' Views)</li>';
			}
	}

	public function update_post_view_count(){
		$upcoun=$this->db->prepare('UPDATE blog_posts SET postCount = postCount+1 WHERE postID =:postID');
		$upcoun->execute(array(':postID' => $_GET['id']));
	}

	public function get_post_id(){
		$stmt = $this->db->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts WHERE postID = :postID');
		$stmt->execute(array(':postID' => $_GET['id']));
		$row = $stmt->fetch();

		if($row['postID'] == ''){
			header('Location: ./');
			exit;
		} else {
			return $row;
		}
	}

	public function get_view_post($row){
		echo "<div id='wrapper'>
				<div class='curlocation'>
					<p>
					<img src='./images/home.png' style='height:15px; width:15px;'>
		 			Current Index: <a href='./'>Homepage</a> >> " . $row['postTitle'] . 
					"</p>
	 			</div>";

		echo '<div id="blog">';
			echo '<h1><img src="./images/art.png" style="height:20px; width:20px;"> '.$row['postTitle'].'</h1>';
			echo '<p>Posted on '.date('jS M Y', strtotime($row['postDate'])).'</p>';
			echo '<p>'.$row['postCont'].'</p>';
			echo '<p align="right">Share: <img style="vertical-align:middle;" src="./images/facebookshare.png"> <img style="vertical-align:middle;" src="./images/googleshare.png"></p>';
		echo '</div>';
	}

}

?>