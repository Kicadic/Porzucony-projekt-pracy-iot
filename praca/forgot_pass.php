<?php
session_start();
require_once "dbh.php";
mysqli_report(MYSQLI_REPORT_STRICT);



if(isset($_POST['pass1']))
{
$email = $_SESSION['email'];
$ok = true;

		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
		if((strlen($pass1)<8) || (strlen($pass1)>20))
		{
			$ok = false;
			$_SESSION['e_pass'] = "Hasło musi posiadać od 8 do 20 znaków";
		}
		if($pass1!=$pass2)
		{
			$ok = false;
			$_SESSION['e_pass'] = "Podane hasła nie są identyczne!";
		}
		
		$haslo_hash = password_hash($pass1,PASSWORD_DEFAULT); //hashowanie haseł
		try		//przechwytywanie wyjątków
		{

			$_SESSION['conn'] = new mysqli($servername, $username, $password , $dbname);
			
			if($_SESSION['conn']->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
				else
				{
			
					if($ok == true) 
					{
						// Generuj vkey
				
						if($_SESSION['conn']->query("UPDATE persons set haslo = '$haslo_hash',vkey = '$vkey' WHERE email ='$email'"))

						{
							
							$_SESSION['prawidlowa_zmiana']=true;
						header("Location:changed_pass.php");
							
						}
							else
							{
								throw new Exception($_SESSION['conn']->error);
							}
			
					}
						$_SESSION['conn']->close();
			
			
		
				}
		}
		catch(Exception $err)
		{
			
			echo'<span style="color:red;"> Błąd serwera!.</span>';
			echo'<br /> Informacja dla dev:'.$err;
		}
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="style.css">
 <style>
	.error
	{
		color:red;
		margin-top:10px;
		margin-bottom:10px;
	}
  
  </style>
</head>

<body>
		<form method="post">

			<input class="w3-input w3-border" type="password" ng-model="passw1" placeholder="Nowe hasło" name = "pass1"> <br>
			<input class="w3-input w3-border" type="password" ng-model="passw2" placeholder="Powtórz hasło" name = "pass2">
			<br> <br>
		
		<input type="submit" value= "Wyślij" />
		</form>
	<?php
		if(isset($_SESSION['e_pass']))
		{
				echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
				unset($_SESSION['e_pass']);		
		}
	?>
		
</div>
</body>
</html>