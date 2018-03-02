<?php
class Membre
{

	/*
	 * Inscription
	 *
	 * */
	public function Register($name, $email, $username, $password)
	{
		try {
			$db = DB();
			$query = $db->prepare("INSERT INTO membre(name, email, username, password) VALUES (:name,:email,:username,:password)");
			$query->bindParam("name", $name, PDO::PARAM_STR);
			$query->bindParam("email", $email, PDO::PARAM_STR);
			$query->bindParam("username", $username, PDO::PARAM_STR);
			$enc_password = hash('sha256', $password);
			$query->bindParam("password", $enc_password, PDO::PARAM_STR);
			$query->execute();
			return $db->lastInsertId();
		} catch (PDOException $e) {
			exit($e->getMessage());
			// echo 'Échec lors de la connexion : ' . $e->getMessage();
		}
	}

	/*
	 * Check Username
	 *
	 * */
	public function isUsername($username)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT id_me FROM membre WHERE username=:username");
			$query->bindParam("username", $username, PDO::PARAM_STR);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	/*
	 * Get Username
	 *
	 * */
	public function getUsername($id)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT username FROM membre WHERE id_me=:id_me");
			$query->bindParam("id_me", $id, PDO::PARAM_STR);
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result->username;
			
			return $query;
			
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	/*
	 * Check Email
	 *
	 * */
	public function isEmail($email)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT id_me FROM membre WHERE email=:email");
			$query->bindParam("email", $email, PDO::PARAM_STR);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	/*
	 * Login
	 *
	 * */
	public function Login($username, $password)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT id_me FROM membre WHERE (username=:username OR email=:username) AND password=:password");
			$query->bindParam("username", $username, PDO::PARAM_STR);
			$enc_password = hash('sha256', $password);
			$query->bindParam("password", $enc_password, PDO::PARAM_STR);
			$query->execute();
			if ($query->rowCount() > 0) {
				$result = $query->fetch(PDO::FETCH_OBJ);
				return $result->id_me;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	/*
	 * On récupere les infos de l'utilisateur
	 *
	 * */
	public function UserDetails($id_me)
	{
		try {
			$db = DB();
			$query = $db->prepare("SELECT id_me, name, username, email FROM membre WHERE id_me=:id_me");
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->execute();
			if ($query->rowCount() > 0) {
				return $query->fetch(PDO::FETCH_OBJ);
			}
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function getUserImg($id_me){
		try {
			$db = DB();
			$query = $db->prepare("SELECT name_img FROM profile_img WHERE user_id=:id_me");
			$query->bindParam("id_me", $id_me, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch();
			if ($query->rowCount() > 0) {
				$img = 'uploads/user/'.$result["name_img"];
			}else{
				$img = 'assets/images/user.png';
			}
			return $img;
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}
	
	public function NotLogged($title, $message)
	{
		echo '
		<div class="container errBlock"></div>
		<div class="container errMsg">
					<h1>'. $title .'</h1>      
					<p>'. $message .'</p>   
			</div>
		';
		header( "refresh:2;url=login.php" );
	}
}