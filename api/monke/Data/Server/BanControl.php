<?php

$ban = $pdo->query("SELECT * FROM bans", PDO::FETCH_ASSOC);
   foreach($ban as $kontrol){
       if($kontrol['ip'] == $ip){ 
           header('Location:https://www.youtube.com/watch?v=U2Fjfqm-7g8');
       } 
   }

?>