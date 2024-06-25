<?php
   session_start();
   unset($_SESSION['email']);  
   unset($_SESSION['user_id']); 
   //Destroy the session
   session_destroy();   
   //Redirect the user to login page
   header('Location: /aniqlo/account/login');
?>