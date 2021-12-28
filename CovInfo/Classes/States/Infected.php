<?php
require_once("UserState.php");

class Infected extends UserState{

    public function __construct(){
        parent::__construct(3);
    }

    /**
     * @throws Exception
     */
    public function addPatient(User $user)
    {
        throw new Exception("Invalid state transition request");
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
