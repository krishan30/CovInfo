<?php

require_once ("State.php");


abstract class AccountState implements State {
    private int $accountTypeID;

    public function __construct(int $accountTypeID){
        $this->$accountTypeID=$accountTypeID;
    }

    public function getAccountTypeID(): int
    {
        return $this->accountTypeID;
    }
    public abstract function activate(User  $user);
    public abstract function deactivate(User  $user);
}