<?php

require_once ("State.php");


abstract class VaccinationState implements State {
    private int $vaccineStatusID;

    public function __construct(int $vaccineStatusID){
        $this->vaccineStatusID=$vaccineStatusID;
    }

    public function getVaccineStatusID(): int
    {
        return $this->vaccineStatusID;
    }

    protected static function getRequiredVaccinationDoseCount() :int{
        $connection = PDOSingleton::getInstance();
        $sql = "SELECT vaccine_doses FROM setting where setting_id=:setting_id ";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':setting_id'=>1));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result['vaccine_doses'];
    }

    public abstract function getDose(User  $user);
}