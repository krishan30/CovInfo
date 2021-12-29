<?php

require_once("UserState.php");
require_once("IUser.php");
class Healthy extends UserState{

    public function __construct(){
        parent::__construct(1);
    }

    public function addPatient(IUser $user)
    {
        $user->setUserState(new Infected());
    }

    public function startQuarantine(IUser $user)
    {
        $user->setUserState(new Quarantined());
    }

    /**
     * @throws Exception
     */
    public function endQuarantine(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function reportDeath(IUser $user)
    {
        $user->setUserState(new Deceased());
    }
}