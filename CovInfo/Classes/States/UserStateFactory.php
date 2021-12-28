<?php


require_once ("StateFactory.php");

class UserStateFactory extends StateFactory{

     protected function factoryMethod(int $stateID) :UserState
    {
        if($stateID===1){
            return new Healthy();
        }elseif ($stateID===2){
            return new Quarantined();
        }elseif ($stateID===3){
            return new Infected();
        }else{
            return new Deceased();
        }
    }
}
