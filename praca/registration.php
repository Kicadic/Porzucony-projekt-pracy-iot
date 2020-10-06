<?php
session_start();

if(isset($_POST['email']))
{
	//Udana walidacja
	$ok=true;
	
	//Poprawnosc loginu
	$nazwa = $_POST['nazwa'];
	
	//Sprawdzenie długosci nicka
	if((strlen($nazwa)<3) || (strlen($nazwa)>20))
		
		{
			$ok = false;
			$_SESSION['e_nazwa'] = "Login musi posiadać od 3 do 20 znaków!";
		}
		
		
		$email = $_POST['email'];
		$emailB = filter_var($email,FILTER_SANITIZE_EMAIL); //sprawdzenie czy to email
		
		if((filter_var($emailB,FILTER_VALIDATE_EMAIL) == false) || ($emailB!=$email))
		{
			$ok = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
		}
		
		if(ctype_alnum($nazwa)==false)
		{
			$ok = false;
			$_SESSION['e_nazwa'] = "Nazwa użytkownika może składać się tylko z liter i cyfr";
		}
		
		//Poprawnosc hasel
		
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$ok = false;
			$_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków";
		}
		if($haslo1!=$haslo2)
		{
			$ok = false;
			$_SESSION['e_haslo'] = "Podane hasła nie są identyczne!";
		}
		
		$haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT); //hashowanie haseł
		$_SESSION['p'] = $haslo_hash;
		
		//Podaj telefon 690008496
		
		$tel = $_POST['telefon'];
		
		if(strlen($tel)!=9)
		{
			$ok = false;
			$_SESSION['e_telefon'] = "Podaj poprawny numer telefonu!";
			
		}
		
		//Jesli telefon nie jest liczbą
		
		if(!is_numeric($tel))
		{
			$ok = false;
			$_SESSION['e_telefon'] = "Telefon musi być liczbą!";
			
		}
		
		//Akceptacja regulaminu
		
		if(!isset($_POST['regulamin']))
		{
			$ok = false;
			$_SESSION['e_regulamin'] = "Nie zaakceptowano regulaminu!";	
		}
		
		//Recaptcha
		
		$secret = "6LdmHJ8UAAAAADBXqgJ9epKSC95nigdNVD7UYMd-";
		//file get - zmiana pliku na ciąg znakow
		$sprawdzenie = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdzenie);
		
		if($odpowiedz->success == false)
		{
			$ok=false;
			$_SESSION['e_bot']="Potwierdź,że nie jesteś botem!";
		}
			
		require_once "dbh.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		
		try		//przechwytywanie wyjątków
		{
			$_SESSION['conn'] = new mysqli($servername, $username, $password , $dbname);
			if($_SESSION['conn']->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email juz istnieje?
				
				$rezultat = $_SESSION['conn']->query("SELECT id FROM persons WHERE email='$email'");
				if(!$rezultat) throw new Exception($_SESSION['conn']->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
					
				{
					$ok=false;
					$_SESSION['e_email']="Istnieje już konto o takim email";
				}
				
				//Czy login jest zarezerwowany?
				
				$rezultat = $_SESSION['conn']->query("SELECT id FROM persons WHERE user='$nazwa'");
				if(!$rezultat) throw new Exception($_SESSION['conn']->error);
				
				$ile_takich_nazw = $rezultat->num_rows;
				if($ile_takich_nazw>0)
					
				{
					$ok=false;
					$_SESSION['e_nazwa']="Istnieje już taka nazwa użytkownika";
				}
				
				//Czy telefon jest zarezerwowany?
				
					//Czy telefon to liczba
				
				$rezultat = $_SESSION['conn']->query("SELECT id FROM persons WHERE telefon='$tel'");
				if(!$rezultat) throw new Exception($_SESSION['conn']->error);
				
				$ile_telefonow = $rezultat->num_rows;
				if($ile_telefonow >0)
				{
					$ok=false;
					$_SESSION['e_telefon']="Telefon jest już zarezerwowany";
				}
				
		if($ok == true) //wszystkie testy zaliczone
		{
			// Generuj vkey
			$vkey = md5(time().$email);
			//dodajemy user'a do bazy
			
			if($_SESSION['conn']->query("INSERT INTO persons(id,user,haslo,email,telefon,vkey) VALUES(NULL,'$nazwa','$haslo_hash','$email','$tel','$vkey')"))
			
			{
				//Wyślij email
							
							$subject = "Weryfikacja email";
							$message = "<a href='http://localhost/praca/verify_registration.php?vkey=$vkey'>Zajerestruj konto</a>";
							$headers = "From:learnphp@yahoo.com \r\n";
							$headers .= "MIME-Version: 1.0" . "\r\n";
							$headers .="Content-type:text/html;charset=UTF-8" . "\r\n";
							
							mail($email,$subject,$message,$headers);
							
							$_SESSION['prawidlowarejestracja']=true;
							header('Location:witaj.php');
				
								
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
			
			echo'<span style="color:red;"> Błąd serwera! Zajerestruj się później.</span>';
			echo'<br /> Informacja dla dev:'.$err;
		}

	
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<title> Rejestracja - załóż konto! </title>
<script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script>
  <script>
  grecaptcha.ready(function() {
      grecaptcha.execute('reCAPTCHA_site_key', {action: 'homepage'}).then(function(token) {
         ...
      });
  });
  </script>
  
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <style>
	.error
	{
		color:red;
		margin-top:10px;
		margin-bottom:10px;
	}
  
  </style>
</head>

<body ng-app="myApp" ng-controller="userCtrl">
<div id="reg">
<form method="post">

	    <input class="w3-input w3-border" type="text" ng-model="text" ng-disabled="!edit" placeholder="Nazwa użytkownika" name="nazwa">
	
	<?php
		if(isset($_SESSION['e_nazwa']))
		{
				echo '<div class="error">'.$_SESSION['e_nazwa'].'</div>';
				unset($_SESSION['e_nazwa']);		
		}
	?>
	<br>
	 <input class="w3-input w3-border" type="text" ng-model="text" ng-disabled="!edit" placeholder="Podaj email" name="email">
	
		<?php
		if(isset($_SESSION['e_email']))
		{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);		
		}
	?>
	<br>
	<input class="w3-input w3-border" type="password" ng-model="passw1" placeholder="Podaj haslo" name = "haslo1">
	
		<?php
		if(isset($_SESSION['e_haslo']))
		{
				echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);		
		}
	?>
	<br>
	<input class="w3-input w3-border" type="password" ng-model="passw1" placeholder="Powtórz hasło" name = "haslo2">
	<br>
		 <input class="w3-input w3-border" type="text" ng-model="text" ng-disabled="!edit" placeholder="Telefon" name="telefon">
	
		<?php
		if(isset($_SESSION['e_telefon']))
		{
				echo '<div class="error">'.$_SESSION['e_telefon'].'</div>';
				unset($_SESSION['e_telefon']);		
		}
	?>
	<br>
	<br>
	<label>
	<input type="checkbox" ng-model="check" name = "regulamin"> Zaakceptuj regulamin
	</label>
	<br>
	<br>
	
	<?php
		if(isset($_SESSION['e_regulamin']))
		{
				echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);		
		}
	?>
	
	<div class="g-recaptcha" data-sitekey="6LdmHJ8UAAAAAMfH3PusAqcV6fzTFyDCJwtRCaSr"></div>
	
	<?php
		if(isset($_SESSION['e_bot']))
		{
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);		
		}
	?>
	
	<br >
	<input type="submit" value="Zarejestruj się" />
	</form>

</div>

</body>
</html>