<?php

require_once("UserState.php");
require_once("IUser.php");
class Deceased extends UserState{

    public function __construct(){
        parent::__construct(4);
    }

    /**
     * @throws Exception
     */
    public function addPatient(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function startQuarantine(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function endQuarantine(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function reportDeath(IUser $user)
    {
        throw new Exception("Invalid state transition request");
    }
}