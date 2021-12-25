<?php

class DataBaseAdapter
{

    //return an array of data from the database
    public static function GetData($sqlStatement){
        $out = array();
        $connection = PDOSingleton::getInstance();
        $stmt = $connection->query($sqlStatement);
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $newRow = $row  + [];
            $out[$i] = $newRow;
            $i++;
        }
        return array($out,$i);
    }




}