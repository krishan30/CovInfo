<?php

require_once ("StateFactory.php");

class VaccinationStateFactory extends StateFactory{
     protected function factoryMethod(int $stateID) :VaccinationState
    {
        if($stateID===1){
            return new NotVaccinated();
        }elseif ($stateID===2){
            return new PartiallyVaccinated();
        }else{
            return new FullyVaccinated();
        }
    }
}