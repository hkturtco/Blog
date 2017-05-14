<?php

class User{
private $db;

public function __construct($db){
	$this->db = $db;
}

public function is_logged_in(){
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
		return true;
	}
}

public function create_hash($value){
	return $hash = password_hash($value, PASSWORD_DEFAULT);
}

private function verify_hash($password, $hash){
	return $hash == crypt($password, $hash);
}

private function get_user_hash($username){
	try{
		$stmt = $this->db->prepare('SELECT password FROM blog_members WHERE username = :username');
		$stmt->execute(array('username' => $username));

		$row = $stmt->fetch();
		return $row['password'];
	} catch(PDOException $e){
		echo '<p class="error">'.$e->getMessage().'</p>';
	}
}

public function login($username, $password){
	$hashed = $this->get_user_hash($username);
	

	if($this->verify_hash($password, $hashed) == true){
		$_SESSION['loggedin'] = true;
		$_SESSION['user'] = $username;
		return true;
	}
}

public function cur_username(){
	return $_SESSION['user'];
}

public function logout(){
	session_destroy();
}

}

?>