<?php
require_once("AccountState.php");

class ActiveUser extends AccountState{

    public function __construct(){
        parent::__construct(2);
    }

    /**
     * @throws Exception
     */
    public function activate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function deactivate(User $user)
    {
        $user->setAccountState(new InactiveUser());
    }
}