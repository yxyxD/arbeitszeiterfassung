<?php
	session_start();

	$servername = "localhost";
	$database = "aze";
	$username = "aze_adm";
	$password = "*+Pa\$\$w0rD69+*";
	$conn = null;

	if(isset($_GET['login']))
	{
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			print "Connection failed: " . $e->getMessage();
		}
		$user_name = $_POST['user'];
		$user_password = $_POST['password'];
	
		$statement = $conn->prepare("SELECT * FROM USER WHERE USERNAME = :username");
		$result = $statement->execute(array('username' => $user_name));
		$user = $statement->fetch();

		if ($user !== false && password_verify($user_password, $user['PASSWORD']))
		{
			$_SESSION['user_id'] = $user['USER_ID'];
			$_SESSION['user_name'] = $user['USERNAME'];
			header('Location: /main.html');
		}
		else
		{
			$errorMessage = "Nutzername oder Passwort ungültig<br>";
		}
	}

?>
<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title> Arbeitszeiterfassung </title>

	<link rel="stylesheet" type="text/css" href="./layout.css">
	<link rel="stylesheet" type="text/css" href="./login.css">
</head>

<body>
	<main>

		<!-- Login mask -->
		<div id="outer">
			<div id="middle">
				<div id="inner">
					<p id="loginTitle" class="heading">
						Login
					</p>

					<?php
						if (isset($errorMessage))
						{
					?>
						<p id="errorMessage" style="background-color: red;">
						<?php
							echo $errorMessage;
						?>
						</p>
					<?php
						}
					?>

					<form action="?login=1" method="post">
						<div class="centering">
							<input type="text" class="loginInput" name="user" placeholder="Nutzername">
						</div>
						<div class="centering">
							<input type="password" class="loginInput" name="password" placeholder="Passwort">
						</div>
						<div class="centering">
							<input type="submit" class="loginButton" value="Login">
						</div>
					</form>
				</div>
			</div>
		</div>

	</main>
</body>

</html>
