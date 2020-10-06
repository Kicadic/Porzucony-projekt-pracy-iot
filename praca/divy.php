<?php

session_start();

?>
<div class="d-none d-md-block">
<section class="logo">
  <div class="container-fluid"></div>
  <h1 class="text-responsive"> Platforma IoT  </h1> 
  </section>
</div>
  <header>
  <nav class="navbar navbar-expand-md bg-primary navbar-dark "> 
  
  
   <a class="navbar-brand" href="index.php"> <img src="./img/home.png" class="d-inline-block align-bottom" alt="">  </a>
 
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">

  <span class="navbar-toggler-icon"> </span> </button>
  
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
   
     
        <a class="nav-link bg-primary text-white" href="pomiar.php" >Pomiary RL</a>
      
        <a class="nav-link bg-primary text-white" href="history.php">Historia</a> 
      
		<a class="nav-link bg-primary text-white" href="options.php">Ustawienia</a>
		
       <a class="nav-link bg-primary text-white" href="projekt.php">O projekcie</a>
	  
	  			<div class="nav navbar-nav ml-auto border border-dark">
				
			<?php
		if (!isset($_SESSION['zalogowany'])) 
		{
	
		
	echo '<a class="nav-link bg-primary text-white" href="zaloguj.php"> Zaloguj </a>';
		}
 
else{
	
echo '<a class="nav-link bg-primary text-white" href="logout.php"> Wyloguj </a>';
 
}
?>	 		
</div>
</nav>
  
 
  </nav>
  </header>
