<?php

    require_once('connect.php');
     
     function getProjects(){
         
         global $baseDD; 
         
         $R1=$baseDD->prepare("SELECT * FROM `mc_project`");
         if($R1->execute()){
             while($line=$R1->fetch()){
                 echo '<pre>'; 
                 print_r($line); 
                 echo '</pre>'; 
             } 
         }
    
     }
     
   ?>