<?php
	$servername = "localhost";
	$database = "aze";
	$username = "aze_adm";
	//$password = ;
	$conn = null;

	$user_name = "testuser";
	//$user_password = ;
	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		print "Connected successfully\n";
	}
	catch(PDOException $e)
	{
		print "Connection failed: " . $e->getMessage();
	}

	$password_hash = password_hash($user_password, PASSWORD_DEFAULT);
	$statement = $conn->prepare("INSERT INTO USER (USERNAME, PASSWORD) VALUES (:username, :password)");
	$result = $statement->execute(array('username' => $user_name, 'password' => $password_hash));

	if ($result)
	{
		print "Yes.";
	}
	else
	{
		print "No u.";
	}

	$conn->close();

	//$password_hash = password_hash($user_password, PASSWORD_DEFAULT);
	//print "Passwort: " . $user_password . "\n";
	//print "Hash des Passworts: " . $password_hash . "\n";

	//if (password_verify($user_password_falsch, $password_hash))
	//{
	//	print "Jup.";
	//} else
	//{
	//	print "Nop.";
	//}

?>
