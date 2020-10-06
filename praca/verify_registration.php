<?php
session_start();
include 'dbh.php';
if(isset($_GET['vkey']))
{
	//Proces weryfikacji
	
	$vkey = $_GET['vkey'];
	$_SESSION['conn'] = new mysqli($servername, $username, $password , $dbname);
	
	$resultSet = $_SESSION['conn']->query("SELECT verify,vkey FROM persons WHERE verify = 0 AND vkey = '$vkey' LIMIT 1");
	
	if($resultSet->num_rows == 1)
	{
		
		//validate the email
		$update = $_SESSION['conn']->query("UPDATE persons set verify = 1 WHERE vkey = '$vkey' LIMIT 1");
		
		if($update)
		{
			echo "Konto zostało zweryfikowane";
		
		}
		else
		{
			echo $_SESSION['conn']->error;
		}
	}
	else
	{
		
		echo "To konto jest nieprawidłowe lub już zweryfikowane";
	}
}
else
{
	die("Coś poszło nie tak...");
}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body> 

<a href = "zaloguj.php">Zaloguj się! </a>

<br>
</body>

</html>