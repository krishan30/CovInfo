<?php

require_once("UserState.php");

class Deceased extends UserState{

    public function __construct(){
        parent::__construct(4);
    }

    /**
     * @throws Exception
     */
    public function addPatient(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function startQuarantine(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function endQuarantine(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function reportDeath(User $user)
    {
        throw new Exception("Invalid state transition request");
    }
}