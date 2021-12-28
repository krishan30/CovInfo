<?php

require_once("VaccinationState.php");

class PartiallyVaccinated extends VaccinationState{

    public function __construct(){
        parent::__construct(2);
    }

    public function getDose(User $user)
    {
            $user->setVaccinationState(new FullyVaccinated());
    }

}
