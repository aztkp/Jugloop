<?php

 function h($s) {
   return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
 }

 function goHome() {
   header('Location: /');
   exit;
 }

 function timeEcho($time) {
   if (!$time) {
     return 0 . '分';
   } else {
   $tArry = explode(":", $time);
   $tArry[0] = (int)$tArry[0];
   $tArry[1] = (int)$tArry[1];
   $hour = $tArry[0];
   $min = $tArry[1];
   if ($tArry[0] === 0) {
     return $min . '分';
   } else {
     return $hour . '時間' . $min . '分';
   }
 }
 }
