<?php


require_once("Person.php");
require_once ("classes.php");

class User extends Person
{
    private UserState $userState;
    private AccountState $accountState;
    private VaccinationState $vaccinationState;
    private int $userID;

         public function __construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber,int $userStatus, int $vaccinationStatus, $bloodType, $userType,int $userID,int $accountTypeID)
    {
        parent::__construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber, $userStatus, $vaccinationStatus, $bloodType, $userType);
        $accountStateFactory=new AccountStateFactory();
        $vaccinationStateFactory=new VaccinationStateFactory();
        $userStateFactory=new UserStateFactory();
        $this->userState=$userStateFactory->createState($userStatus);
        $this->accountState=$accountStateFactory->createState($accountTypeID);
        $this->vaccinationState=$vaccinationStateFactory->createState($vaccinationStatus);
        $this->userID=$userID;
    }

    public function getUserID(): int
    {
             return $this->userID;
    }

    public function setUserState(UserState $userState)
    {
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set status_id=:status_id  where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$this->getUserID(),':status_id'=>$userState->getStatusID()));
        $this->userState=$userState;
    }
    public function setAccountState(AccountState $accountState){
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set account_type=:account_type  where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$this->getUserID(),':account_type'=>$accountState->getAccountTypeID()));
        $this->accountState=$accountState;
    }

    public function setVaccinationState(VaccinationState $vaccinationState){
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set vaccine_status_id=:vaccine_status  where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$this->getUserID(),':vaccine_status'=>$vaccinationState->getVaccineStatusID()));
        $this->vaccinationState=$vaccinationState;
    }

    public function getUserState(): UserState
    {
           return $this->userState;
    }

    public function getAccountState(): AccountState
    {
        return $this->accountState;
    }

    public function getVaccinationState(): VaccinationState
    {
        return $this->vaccinationState;
    }

    /**
     * @throws Exception
     */
    public  function activateAccount(){
             $this->accountState->activate($this);
    }

    /**
     * @throws Exception
     */
    public  function deactivateAccount(){
            $this->accountState->deactivate($this);
    }

    /**
     * @throws Exception
     */
    public function getDose(){
        $this->vaccinationState->getDose($this);
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