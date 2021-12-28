<?php

require_once ("StateFactory.php");

class AccountStateFactory extends StateFactory {
     protected function factoryMethod(int $stateID) :AccountState
    {
        if($stateID===1){
            return new PreUser();
        }elseif ($stateID===2){
            return new ActiveUser();
        }else{
            return new InactiveUser();
        }
    }

}
