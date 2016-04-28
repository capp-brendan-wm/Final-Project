<?php
session_start();
$name = 'logUser';
setcookie($name, '', time()-1000);
setcookie($name, '', time()-1000, '/');
header("location: account.php?logout=true");
exit();