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
        $this->userState=$userStateFactory->createState($status);
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

    public function isNewNotificationsAvailable() :bool{
        $connection = PDOSingleton::getInstance();
        $sql = "SELECT read_status_id FROM notification where receiver_id=:receiver_id AND read_status_id=1 ";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':receiver_id'=>$this->getUserID()));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function getNewNotificationCount():int {
        $connection = PDOSingleton::getInstance();
        $sql = "SELECT  COUNT(read_status_id) AS NumberOfNewNotifications FROM notification where receiver_id=:receiver_id AND read_status_id=1 ";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':receiver_id'=>$this->getUserID()));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result["NumberOfNewNotifications"];
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