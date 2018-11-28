<?php

$_SG['server'] = 'mysql.hostinger.com.br';
$_SG['user'] = 'u444405254_tcc';
$_SG['password'] = 'TCC-app-2018';
$_SG['base'] = 'u444405254_tcc';
$_SG['tuser'] = 'usuario';
$_SG['tclient'] = 'cliente';

$conn = mysqli_connect($_SG['server'], $_SG['user'], $_SG['password']);
if (!$conn) {
  die('<p>Connection failed: <p>' . mysqli_connect_error());
}
mysqli_select_db($conn, $_SG['base']);
?>