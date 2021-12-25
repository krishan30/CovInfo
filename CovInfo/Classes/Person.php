<?php

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
    private $vaccinationStatus;
    private $status;
    private $bloodType;
    private $userType;

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
     * @param $vaccinationStatus
     * @param $status
     */
    public function __construct($accountID, $password, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $DOB, $gender, $district, $province, $MOHDivision, $address, $phoneNumber, $status, $vaccinationStatus,$bloodType,$userType)
    {
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
        $this->status = $status;
        $this->vaccinationStatus = $vaccinationStatus;
        $this->bloodType = $bloodType;
        $this->userType = $userType;
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
    public function setUserType($userType): void
    {
        $this->userType = $userType;
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
    public function setBloodType($bloodType): void
    {
        $this->bloodType = $bloodType;
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
    public function setPassword($password)
    {
        $this->password = $password;
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
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
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
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
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
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
    public function setNICNumber($NICNumber)
    {
        $this->NICNumber = $NICNumber;
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
    public function setDOB($DOB)
    {
        $this->DOB = $DOB;
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
    public function setGender($gender)
    {
        $this->gender = $gender;
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
    public function setMiddleName($middleName): void
    {
        $this->middleName = $middleName;
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
    public function setDistrict($district)
    {
        $this->district = $district;
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
    public function setProvince($province)
    {
        $this->province = $province;
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
    public function setMOHDivision($MOHDivision)
    {
        $this->MOHDivision = $MOHDivision;
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
    public function setAddress($address)
    {
        $this->address = $address;
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
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
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

    /**
     * @return mixed
     */
    public function getVaccinationStatus()
    {
        return $this->vaccinationStatus;
    }

    /**
     * @param mixed $vaccinationStatus
     */
    public function setVaccinationStatus($vaccinationStatus)
    {
        $this->vaccinationStatus = $vaccinationStatus;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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