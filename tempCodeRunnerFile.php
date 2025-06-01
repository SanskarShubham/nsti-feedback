<?php
$pass = 1234;
$enc = password_hash($pass, PASSWORD_DEFAULT);
echo $enc;

?>