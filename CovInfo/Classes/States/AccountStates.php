<?php

abstract class AccountState{
    public abstract function activate(User  $user);
    public abstract function Deactivate(User  $user);
}

class PreUser extends AccountState {

    public function activate(User $user)
    {
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set account_type=2  where account_id=:account_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':account_id'=>$user->getAccountID()));
        $user->setAccountState(new ActiveUser());
    }

    /**
     * @throws Exception
     */
    public function Deactivate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }
}

class ActiveUser extends AccountState{

    /**
     * @throws Exception
     */
    public function activate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

    public function Deactivate(User $user)
    {
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set account_type=3  where account_id=:account_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':account_id'=>$user->getAccountID()));
        $user->setAccountState(new InactiveUser());
    }
}

class InactiveUser extends AccountState
{


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
    public function Deactivate(User $user)
    {
        throw new Exception("Invalid state transition request");
    }

}


?>
