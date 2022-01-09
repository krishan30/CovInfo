<?php

require_once ("PDOSingleton.php");

class Person
{
    private $accountID;
    private $password;
    private $emailAddress;
    private $firstName;
    private $middleName;
    private $lastName;
    private $NICNumber;
    private $DOB;
    private $gender;
    private $district;
    private $province;
    private $MOHDivision;
    private $address;
    private $phoneNumber;
    private $vaccinationRecords;
    private $qurontineRecords;
    private $infectionRecord;
    private $deathRecord;
    private $bloodType;
    private $userType;
    private $medicalRemarks;

    /**
     * @param $accountID
     * @param $password
     * @param $emailAddress
     * @param $firstName
     * @param $lastName
     * @param $NICNumber
     * @param $DOB
     * @param $gender
     * @param $district
     * @param $province
     * @param $MOHDivision
     * @param $address
     * @param $phoneNumber
     * @param $vaccinationRecords
     * @param $qurontineRecords
     * @param $infectionRecord
     * @param $deathRecord
     */
    public function __construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber,$bloodType,$userType,$medical)
    {
        $this->medicalRemarks = $medical;
        $this->accountID = $accountID;
        $this->password = $password;
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->NICNumber = $NICNumber;
        $this->DOB = strtotime($DOB);
        $this->gender = $gender;
        $this->district = $district;
        $this->province = $province;
        $this->MOHDivision = $MOHDivision;
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->bloodType = $bloodType;
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getMedicalRemarks()
    {
        return $this->medicalRemarks;
    }

    /**
     * @param mixed $medicalRemarks
     */
    public function setMedicalRemarks($medicalRemarks,$user_id): void
    {
        $this->medicalRemarks = $medicalRemarks;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set medical_remarks=:medical_remarks where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":medical_remarks"=>$medicalRemarks));
    }



    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;

    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType,$user_id): void
    {
        $connection = PDOSingleton::getInstance();

        $gQ = $connection->query("SELECT user_type_name FROM user_type WHERE user_type_id = $userType");
        while ($row = $gQ->fetch(PDO::FETCH_ASSOC)){
            $this->userType = $row["user_type_name"];
        }

        $sql = "UPDATE user set user_type_id=:user_type_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":user_type_id"=>$userType));

        if($userType != 1){
            $isFound = false;
            $adsql = $connection->query("SELECT administrator_id FROM administrator WHERE user_id = $user_id");
            while ($row = $adsql->fetch(PDO::FETCH_ASSOC)){
                $isFound = true;
                break;
            }

            if(!$isFound){
                $adSql = "INSERT INTO administrator(user_id) VALUES(:user_id)";
                $adStmt = $connection->prepare($adSql);
                $adStmt->execute(array("user_id"=>$user_id));
            }
        }
    }



    /**
     * @return mixed
     */
    public function getBloodType()
    {
        return $this->bloodType;
    }

    /**
     * @param mixed $bloodType
     */
    public function setBloodType($bloodType,$user_id): void
    {
        $this->bloodType = $bloodType;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set blood_type_id=:blood_type_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":blood_type_id"=>$bloodType));
    }

    /**
     * @return mixed
     */
    public function getAccountID()
    {
        return $this->accountID;
    }

    /**
     * @param mixed $accountID
     */
    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password,$user_id)
    {
        $this->password = $password;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set password=:password where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":password"=>$password));
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress,$user_id)
    {
        $this->emailAddress = $emailAddress;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set email_address=:email_address where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":email_address"=>$emailAddress));

    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName,$user_id)
    {
        $this->firstName = $firstName;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set first_name=:first_name where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":first_name"=>$firstName));
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName,$user_id)
    {
        $this->lastName = $lastName;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set last_name=:last_name where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":last_name"=>$lastName));
    }

    /**
     * @return mixed
     */
    public function getNICNumber()
    {
        return $this->NICNumber;
    }

    /**
     * @param mixed $NICNumber
     */
    public function setNICNumber($NICNumber,$user_id)
    {
        $this->NICNumber = $NICNumber;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set nic_number=:nic_number where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":nic_number"=>$NICNumber));
    }

    /**
     * @return mixed
     */
    public function getDOB()
    {
        return $this->DOB;
    }

    /**
     * @return mixed
     */
    public function getDOBString()
    {
        return date("Y-m-d",$this->getDOB());
    }


    /**
     * @param mixed $DOB
     */
    public function setDOB($DOB,$user_id)
    {
        $this->DOB = $DOB;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set birth_day=:birth_day where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":birth_day"=>$DOB));

    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender,$user_id)
    {
        $connection = PDOSingleton::getInstance();
        $gQ = $connection->query("SELECT gender FROM gender WHERE gender_id = $gender");
        while ($row = $gQ->fetch(PDO::FETCH_ASSOC)){
            $this->gender = $row["gender"];
        }
        $sql = "UPDATE user set gender_id=:gender_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":gender_id"=>$gender));
    }

    /**
     * @return mixed
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param mixed $middleName
     */
    public function setMiddleName($middleName,$user_id): void
    {
        $this->middleName = $middleName;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set middle_name=:middle_name where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":middle_name"=>$middleName));

    }

    /**
     * @return mixed
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param mixed $district
     */
    public function setDistrict($district,$user_id)
    {
        $connection = PDOSingleton::getInstance();
        $gQ = $connection->query("SELECT name FROM district WHERE district_id = $district");
        while ($row = $gQ->fetch(PDO::FETCH_ASSOC)){
            $this->district = $row["name"];
        }
        $sql = "UPDATE user set district_id=:district_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":district_id"=>$district));
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province,$user_id)
    {
        $connection = PDOSingleton::getInstance();
        $gQ = $connection->query("SELECT prov_name FROM province WHERE province_id = $province");
        while ($row = $gQ->fetch(PDO::FETCH_ASSOC)){
            $this->province = $row["prov_name"];
        }
        $sql = "UPDATE user set province_id=:province_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":province_id"=>$province));
    }

    /**
     * @return mixed
     */
    public function getMOHDivision()
    {
        return $this->MOHDivision;
    }

    /**
     * @param mixed $MOHDivision
     */
    public function setMOHDivision($MOHDivision,$user_id)
    {
        $connection = PDOSingleton::getInstance();
        $gQ = $connection->query("SELECT moh_name FROM moh_division WHERE moh_division_id = $MOHDivision");
        while ($row = $gQ->fetch(PDO::FETCH_ASSOC)){
            $this->MOHDivision = $row["moh_name"];
        }
        $sql = "UPDATE user set moh_division_id=:moh_division_id where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":moh_division_id"=>$MOHDivision));
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address,$user_id)
    {
        $this->address = $address;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set address=:address where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":address"=>$address));
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber,$user_id)
    {
        $this->phoneNumber = $phoneNumber;
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE user set phone_number=:phone_number where user_id=:user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$user_id,":phone_number"=>$phoneNumber));
    }

    /**
     * @return mixed
     */
    public function getVaccinationRecords()
    {
        return $this->vaccinationRecords;
    }

    /**
     * @param mixed $vaccinationRecords
     */
    public function setVaccinationRecords($vaccinationRecords)
    {
        $this->vaccinationRecords = $vaccinationRecords;
    }

    /**
     * @return mixed
     */
    public function getQurontineRecords()
    {
        return $this->qurontineRecords;
    }

    /**
     * @param mixed $qurontineRecords
     */
    public function setQurontineRecords($qurontineRecords)
    {
        $this->qurontineRecords = $qurontineRecords;
    }

    /**
     * @return mixed
     */
    public function getInfectionRecord()
    {
        return $this->infectionRecord;
    }

    /**
     * @param mixed $infectionRecord
     */
    public function setInfectionRecord($infectionRecord)
    {
        $this->infectionRecord = $infectionRecord;
    }

    /**
     * @return mixed
     */
    public function getDeathRecord()
    {
        return $this->deathRecord;
    }

    /**
     * @param mixed $deathRecord
     */
    public function setDeathRecord($deathRecord)
    {
        $this->deathRecord = $deathRecord;
    }

    public function getAge(){
        $today = date("Y-m-d");
        $age = date_diff(date_create($this->getDOBString()),date_create($today));
        return $age->format("%y");

    }

    public function getFullName(): string
    {
        return $this->firstName." ".$this->middleName." ".$this->$this->lastName;
    }

}

?>