<?php

$serverName = 'localhost';
$username = 'root';
$password = '';

try{
    $connection = new PDO("mysql: host=$serverName;dbname=CovInfo",$username,$password);
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    echo $e->getMessage();
}

?>
