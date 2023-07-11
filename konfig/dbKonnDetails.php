<?php
//Sunucu Tarafı
$servername = "localhost"; //localhost
$database = "newTrial_DB";  //yaslsbvj_
$username = "newTrial_DB_User";  //yaslsbvj_
$password = "p@2*byy3LBGI!Rgl";

$dsn = "mysql:host=$servername;dbname=$database;charset=UTF8";

try {
	$konn = new PDO($dsn, $username, $password, [
		PDO::ATTR_EMULATE_PREPARES => false, 
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	  ]);

	if ($konn) {
		//echo "Connected to the $database database successfully!";
	}
} catch (PDOException $e) {
	echo $e->getMessage();
}
?>