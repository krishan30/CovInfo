<?php

require_once ("StateFactory.php");

class NotificationStateFactory extends StateFactory{

     protected function factoryMethod(int $stateID) :NotificationState
    {
        if($stateID===1){
            return new  Unread();
        }else{
            return new Read();
        }
    }
}
