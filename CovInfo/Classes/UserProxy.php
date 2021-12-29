<?php
require_once ("User.php");
require_once ("UserFactory.php");
require_once ("PersonProxy.php");
require_once ("States/IUser.php");
class UserProxy extends PersonProxy implements IUser
{
    private UserState $userState;

    public function __construct($accountID, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $gender, $address, $status, $userType, $user_id)
    {
        parent::__construct($accountID, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $gender, $address, $status, $userType, $user_id);
        $userStateFactory=new UserStateFactory();
        $this->userState=$userStateFactory->createState($user_id);
    }

    public function getUser()
    {
        if($this->getUser() == null){
            $userFactory = new UserFactory();
            $user = $userFactory->build($this->getUserId());
            $this->setUser($user);
        }

        return $this->getUser();

    }
    public function setUserState(UserState $userState)
    {
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set status_id=:status_id  where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$this->getUserID(),':status_id'=>$userState->getStatusID()));
        $this->userState=$userState;
    }

    public function getUserState(): UserState
    {
        return $this->userState;
    }

    /**
     * @throws Exception
     */
    public  function addAsPatient(){
        $this->userState->addPatient($this);
    }
    /**
     * @throws Exception
     */
    public  function startQuarantine(){
        $this->userState->startQuarantine($this);
    }

    /**
     * @throws Exception
     */
    public  function endQuarantine(){
        $this->userState->endQuarantine($this);
    }

    /**
     * @throws Exception
     */
    public  function reportAsDeath(){
        $this->userState->reportDeath($this);
    }


}