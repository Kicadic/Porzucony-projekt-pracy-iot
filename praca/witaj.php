<?php
session_start();
if(isset($_SESSION['prawidlowarejestracja']))
{	
	header('Location:dziekujemy.php');
	exit();
}
else
{
	
	unset($_SESSION['prawidlowarejestracja']);
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
</head>

<body>

</body>
</html>