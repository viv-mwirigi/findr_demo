<?php

require_once "databaseconn.php";


// echo '<pre>';
//     echo print_r($_GET).'<br>';
//     echo '</pre>';
//     exit; 

 $returnThis = $_GET['to'];
 $keep = $_GET['keep'];
 $returnThis += 1;

header("Location: Dashboard.php?username=$keep&move=next&returnThis=$returnThis");    
    
?>