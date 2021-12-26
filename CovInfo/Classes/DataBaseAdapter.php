<?php

require_once "Target.php";

class DataBaseAdapter implements Target{
    private PDO $connection;

    public function __construct(PDO $PDO){
        $this->connection=$PDO;
    }

    public function selectAllFromTable(String $sqlQuery,array $parameters)
    {
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->execute($parameters);
        return $stmt->fetchAll(PDO:: FETCH_ASSOC);
    }

    public function selectOneFromTable(String $sqlQuery,array $parameters)
    {
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->execute($parameters);
        return $stmt->fetch(PDO:: FETCH_ASSOC);
    }

    public function updateTable(String $sqlQuery,array $parameters)
    {
        $stmt=$this->connection->prepare($sqlQuery);
        return $stmt->execute($parameters);
    }

    public function insertToTable(String $sqlQuery,array $parameters)
    {
        $stmt = $this->connection->prepare($sqlQuery);
        return $stmt->execute($parameters);
    }

    public function deleteFromTable(String $sqlQuery,array $parameters)
    {
        $stmt = $this->connection->prepare($sqlQuery);
        return $stmt->execute($parameters);
    }

}