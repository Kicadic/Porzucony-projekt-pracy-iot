<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
</head>

<body>
Dziękujemy za rejestrację w serwisie! 

<a href = "wejscie.php">Zaloguj się! </a>
<br>

<?php
	if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>

</body>
</html>