<?php

require_once("VaccinationState.php");

class FullyVaccinated extends VaccinationState{

    public function __construct(){
        parent::__construct(3);
    }

    /**
     * @throws Exception
     */
    public function getDose(User $user)
    {
        throw new Exception("Invalid state transition request");
    }


}