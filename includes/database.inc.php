<?php

  $connString = "mysql:host=localhost;dbname=bookcrm";
  $user = "bookrep"; 
  $password = "book@rep20";

   
  $pdo = new PDO($connString,$user,$password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   



?>
