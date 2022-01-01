<?php


require_once("Person.php");
require_once ("States/IUser.php");

class User extends Person implements IUser
{
    private UserState $userState;
    private AccountState $accountState;
    private VaccinationState $vaccinationState;
    private int $userID;

         public function __construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber,int $userStatus, int $vaccinationStatus, $bloodType, $userType,$medical ,int $userID,int $accountTypeID)
    {
        parent::__construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber, $bloodType, $userType,$medical);
        $accountStateFactory=new AccountStateFactory();
        $vaccinationStateFactory=new VaccinationStateFactory();
        $userStateFactory=new UserStateFactory();
        $this->userState=$userStateFactory->createState($userStatus);
        $this->accountState=$accountStateFactory->createState($accountTypeID);
        $this->vaccinationState=$vaccinationStateFactory->createState($vaccinationStatus);
        $this->userID=$userID;
    }

    public function updateProfile($emailAddress,$firstName,$middleName,$lastName,$nicNo,$dob,$gender,$district,$province,$mohDiv,$address,$phoneNo,$bloodType,$medicalRemarks){
             $this->setEmailAddress($emailAddress,$this->userID);
             $this->setFirstName($firstName,$this->userID);
             $this->setMiddleName($middleName,$this->userID);
             $this->setLastName($lastName,$this->userID);
             $this->setNICNumber($nicNo,$this->userID);
             $this->setDOB($dob,$this->userID);
             $this->setGender($gender,$this->userID);
             $this->setDistrict($district,$this->userID);
             $this->setProvince($province,$this->userID);
             $this->setMOHDivision($mohDiv,$this->userID);
             $this->setAddress($address,$this->userID);
             $this->setPhoneNumber($phoneNo,$this->userID);
             $this->setBloodType($bloodType,$this->userID);
             $this->setMedicalRemarks($medicalRemarks,$this->userID);
    }

    public static function createNewUser($emailAddress,$firstName,$middleName,$lastName,$nicNo,$dob,$gender,$district,$province,$mohDiv,$address,$phoneNo,$bloodType,$medicalRemarks){
             $connection = PDOSingleton::getInstance();
             $accountId = mb_substr($dob,0,4).mb_substr($dob,5,2).mb_substr($dob,8,2);
             $countSql = "SELECT COUNT(user_id) FROM user WHERE(o_birth_day =:o_birth_day)";
             $countstmt = $connection->prepare($countSql);
             $countstmt->execute(array(':o_birth_day'=>$dob));
             $count = $countstmt->fetchColumn();
             if($count<10){
                 $accountId = $accountId."000".$count;
             }elseif ($count < 100){
                 $accountId = $accountId."00".$count;
             }elseif($count < 1000){
                 $accountId = $accountId."0".$count;
             }else{
                 $accountId = $accountId.$count;
             }
             $sql = "INSERT INTO `user` (`account_id`, `password`, `email_address`, `first_name`, `middle_name`, `last_name`, `nic_number`, `birth_day`,`o_birth_day`, `gender_id`, `district_id`, `province_id`, `moh_division_id`, `address`, `phone_number`, `user_type_id`, `status_id`, `vaccine_status_id`, `blood_type_id`, `account_type`, `medical_remarks`) VALUES (:account_id, '0a7e3fd172f123df0bed7a8fafa11f75', :email_address, :first_name, :middle_name, :last_name, :nic_number, :birth_day,:o_birth_day, :gender_id, :district_id, :province_id, :moh_division_id, :address, :phone_number, 1, 1, 1, :blood_type_id, 1, :medical_remarks)";
             $stmt = $connection->prepare($sql);
             $stmt->execute(array("account_id"=>$accountId, "email_address"=>$emailAddress, "first_name"=>$firstName, "middle_name"=>$middleName, "last_name"=>$lastName, "nic_number"=>$nicNo, "birth_day"=>$dob, "o_birth_day"=>$dob,"gender_id"=>$gender, "district_id"=>$district, "province_id"=>$province, "moh_division_id"=>$mohDiv, "address"=>$address, "phone_number"=>$phoneNo, "blood_type_id"=>$bloodType, "medical_remarks"=>$medicalRemarks));

             $userIdSql = $connection->query("SELECT user_id FROM user WHERE account_id = $accountId");
             $row = $userIdSql->fetch(PDO::FETCH_ASSOC);
             return $row["user_id"];
    }

    public function updateMyProfile($emailAddress,$address,$phoneNo,$bloodType,$medicalRemarks){
        $this->setEmailAddress($emailAddress,$this->userID);
        $this->setAddress($address,$this->userID);
        $this->setPhoneNumber($phoneNo,$this->userID);
        $this->setBloodType($bloodType,$this->userID);
        $this->setMedicalRemarks($medicalRemarks,$this->userID);
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