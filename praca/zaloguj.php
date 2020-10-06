<?php
session_start();
require_once "dbh.php";
if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
{	
	header('Location:index.php');
	exit();
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
<div id="zaloguj">
<form action="wejscie.php" method="post">
	 <input class="w3-input w3-border" type="text" placeholder="Nazwa użytkownika" name="login"> <br>
	<input class="w3-input w3-border" type="password" placeholder="Hasło" name = "haslo"> <br> <br>
	<input type="submit" name="but" value= "Zaloguj się" />
</form>
<br>
		<form action="registration.php" method="post">
			<input type="submit" value= "Rejestracja" />
		</form>
		<br>
		<a href="forgot.php" >Zapomniałem hasła</a>
		<br>
		<br>

<?php
	if(isset($_SESSION['blad']))
	{
	echo $_SESSION['blad'];
	}
?>
</div>
</body>
</html>