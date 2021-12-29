<?php

require_once("UserState.php");
require_once("IUser.php");
class Quarantined extends UserState{

    public function __construct(){
        parent::__construct(2);
    }

    public function addPatient(IUser $user)
    {
        $user->setUserState(new Infected());
    }

    /**
     * @throws Exception
     */
    public function startQuarantine(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function endQuarantine(IUser $user)
    {
        $user->setUserState(new Healthy());
    }

    public function reportDeath(IUser $user)
    {
        $user->setUserState(new Deceased());
    }
}