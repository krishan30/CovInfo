<?php
require_once ("User.php");
require_once ("UserFactory.php");
require_once ("PersonProxy.php");

class UserProxy extends PersonProxy
{

    public function getUser()
    {
        if($this->getUser() == null){
            $userFactory = new UserFactory();
            $user = $userFactory->build($this->getUserId());
            $this->setUser($user);
        }

        return $this->getUser();

    }
}