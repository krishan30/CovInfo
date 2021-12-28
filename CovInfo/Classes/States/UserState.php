<?php

require_once ("State.php");


abstract class UserState implements State {
    private int $statusID;

    public function __construct(int $statusID){
        $this->statusID=$statusID;
    }

    public function getStatusID(): int
    {
        return $this->statusID;
    }
    public abstract function addPatient(User  $user);
    public abstract function startQuarantine(User  $user);
    public abstract function endQuarantine(User  $user);
    public abstract function reportDeath(User  $user);
}

