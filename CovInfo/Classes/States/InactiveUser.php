<?php
require_once("AccountState.php");

class InactiveUser extends AccountState
{
    public function __construct(){
        parent::__construct(3);
    }


    /**
     * @throws Exception
     */
    public function activate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    /**
     * @throws Exception
     */
    public function deactivate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

}
