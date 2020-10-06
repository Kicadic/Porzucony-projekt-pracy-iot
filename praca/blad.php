<?php

session_start();

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
Najpierw,

<a href = "wejscie.php" style="background-color:#155A5C">Zaloguj się! </a>
<br>

<?php
	if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>

</body>
</html>