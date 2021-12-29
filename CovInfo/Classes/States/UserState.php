<?php

require_once ("State.php");
require_once("IUser.php");

abstract class UserState implements State {
    private int $statusID;

    public function __construct(int $statusID){
        $this->statusID=$statusID;
    }

    public function getStatusID(): int
    {
        return $this->statusID;
    }
    public abstract function addPatient(IUser  $user);
    public abstract function startQuarantine(IUser  $user);
    public abstract function endQuarantine(IUser $user);
    public abstract function reportDeath(IUser  $user);
}

