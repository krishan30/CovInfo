<?php
require_once("AccountState.php");

class PreUser extends AccountState  {

    public function __construct(){
        parent::__construct(1);
    }

    public function activate(User $user)
    {
        $user->setAccountState(new ActiveUser());
    }

    /**
     * @throws Exception
     */
    public function deactivate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }
}