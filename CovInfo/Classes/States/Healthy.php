<?php

require_once("UserState.php");

class Healthy extends UserState{

    public function __construct(){
        parent::__construct(1);
    }

    public function addPatient(User $user)
    {
        $user->setUserState(new Infected());
    }

    public function startQuarantine(User $user)
    {
        $user->setUserState(new Quarantined());
    }

    /**
     * @throws Exception
     */
    public function endQuarantine(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function reportDeath(User $user)
    {
        $user->setUserState(new Deceased());
    }
}