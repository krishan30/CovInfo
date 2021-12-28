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

    public abstract function getDose(User  $user);
}