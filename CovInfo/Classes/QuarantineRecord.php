<?php

/*class QuarantineRecord{
    protected PDO $connection;
    private int $userId;
    private String $startDate;
    private String $endDate;
    private int $administratorId;
    private int $quarantinePlaceId;



    protected function __construct(int $userId,String $startDate,String $endDate,int $administratorId,$quarantinePlaceId){
            $this->userId=$userId;
            $this->startDate=$startDate;
            $this->endDate=$endDate;
            $this->administratorId=$administratorId;
            $this->quarantinePlaceId=$quarantinePlaceId;
            $this->connection=PDOSingleton::getInstance();
            $sql = "INSERT INTO  quarantine_record (user_id, start_date, end_date, administrator_id,place_id) VALUES (:user_id,:start_date,:end_date,:administrator_id,:place_id)";
            $stmt = $this->connection->prepare($sql);

    }


    public static function CreateInstance(int $userId,String $startDate,String $endDate,int $administratorId,$quarantinePlaceId): QuarantineRecord
    {
            return new QuarantineRecord($userId,$startDate,$startDate,$endDate,$administratorId,$quarantinePlaceId);
    }

    public static function getInstance(int $userId,String $endDate): QuarantineRecord
    {


        return new QuarantineRecord($userId,$startDate,$startDate,$endDate,$administratorId,$quarantinePlaceId);
    }

}
*/