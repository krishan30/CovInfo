<?php

$serverName = 'localhost';
$username = 'root';
$password = 'root';

try{
    $connection = new PDO("mysql: host=$serverName;port=3307;dbname=CovInfo",$username,$password);
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    echo $e->getMessage();
}

?>
