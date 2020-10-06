<?php
session_start();
require_once "dbh.php";
mysqli_report(MYSQLI_REPORT_STRICT);


if(isset($_POST['email']))
{
$ok = true;
$email = $_POST['email'];
$_SESSION['email'] = $email;

		try		//przechwytywanie wyjątków
		{
			$_SESSION['conn'] = new mysqli($servername, $username, $password , $dbname);
			if($_SESSION['conn']->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
		
			else
			{
				
				$result = $_SESSION['conn']->query("SELECT ID FROM persons WHERE email='$email'");
				if(!$result) throw new Exception($_SESSION['conn']->error);
				
				$ile = $result->num_rows;
				if($ile!=1)
					
				{	
					$ok = false;
					$_SESSION['e_forgot_email'] = "Nie ma takiego emaila w bazie!";
				}
			if($ok == true) //wszystkie testy zaliczone
			{
				// Generuj vkey
				$vkey = md5(time().$email);
				//Wyślij email
							$subject = "Weryfikacja email";
							$message = "<a href='http://localhost/praca/forgot_pass.php?vkey=$vkey'>Zmiana hasła</a>";
							$headers = "From:learnphp@yahoo.com \r\n";
							$headers .= "MIME-Version: 1.0" . "\r\n";
							$headers .="Content-type:text/html;charset=UTF-8" . "\r\n";
							
							mail($email,$subject,$message,$headers);
							header("Location:check_email.php");						
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

			Podaj email: <br> <input type="text" name="email"/> </br>

		
		<input type="submit" value= "Dalej" />
		</form>
							<?php
		if(isset($_SESSION['e_forgot_email']))
		{
				echo '<div class="error">'.$_SESSION['e_forgot_email'].'</div>';
				unset($_SESSION['e_forgot_email']);		
		}
	?>
		
</div>
</body>
</html>