<?php

require_once("UserState.php");

class Quarantined extends UserState{

    public function __construct(){
        parent::__construct(2);
    }

    public function addPatient(User $user)
    {
        $user->setUserState(new Infected());
    }

    /**
     * @throws Exception
     */
    public function startQuarantine(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function endQuarantine(User $user)
    {
        $user->setUserState(new Healthy());
    }

    public function reportDeath(User $user)
    {
        $user->setUserState(new Deceased());
    }
}