<?php

require_once("VaccinationState.php");

class NotVaccinated extends VaccinationState{

    public function __construct(){
        parent::__construct(1);
    }

    public function getDose(User $user)
    {
        $user->setVaccinationState(new PartiallyVaccinated());
    }

}