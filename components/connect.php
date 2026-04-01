<?php

$db_name = 'mysql:host=127.0.0.1;port=3306;dbname=food_db';

$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);

?>