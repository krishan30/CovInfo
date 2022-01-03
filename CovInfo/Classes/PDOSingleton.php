<?php

class PDOSingleton
{
    private static $instance;

    /**
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if(self::$instance == null){
            $serverName = 'localhost';
            $username = 'root';
            $password = 'root';
            try{
                self::$instance = new PDO("mysql: host=$serverName;dbname=CovInfo;port=3307",$username,$password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch (PDOException $e){
                echo $e->getMessage();
            }
        }
        return self::$instance;
    }

}