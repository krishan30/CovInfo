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


interface IAdministrator
{

}


class MedicalOfficer extends User implements IAdministrator
{

}


class User extends Person
{

}


class Authority extends User implements IAdministrator
{

}


class VaccineRecord
{
    private $dose;
    private $vaccineType;
    private $date;
    private $batchNumber;
    private $person;
    private $remark;
    private $medicalOfficer;
    private $nextAppointment;

    /**
     * @return mixed
     */
    public function getDose()
    {
        return $this->dose;
    }

    /**
     * @param mixed $dose
     */
    public function setDose($dose)
    {
        $this->dose = $dose;
    }

    /**
     * @return mixed
     */
    public function getVaccineType()
    {
        return $this->vaccineType;
    }

    /**
     * @param mixed $vaccineType
     */
    public function setVaccineType($vaccineType)
    {
        $this->vaccineType = $vaccineType;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

    /**
     * @param mixed $batchNumber
     */
    public function setBatchNumber($batchNumber)
    {
        $this->batchNumber = $batchNumber;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getMedicalOfficer()
    {
        return $this->medicalOfficer;
    }

    /**
     * @param mixed $medicalOfficer
     */
    public function setMedicalOfficer($medicalOfficer)
    {
        $this->medicalOfficer = $medicalOfficer;
    }

    /**
     * @return mixed
     */
    public function getNextAppointment()
    {
        return $this->nextAppointment;
    }

    /**
     * @param mixed $nextAppointment
     */
    public function setNextAppointment($nextAppointment)
    {
        $this->nextAppointment = $nextAppointment;
    }


}

class UserBuilder{
    private $user;

    public function buildUser($user_id){
        $serverName = 'localhost';
        $username = 'root';
        $password = 'root';
        $connection = null;
        try{
            $connection = new PDO("mysql: host=$serverName;port=3307;dbname=CovInfo",$username,$password);
            $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
        $stmt = $connection->query("SELECT user.account_id,user.password,user.email_address,user.first_name,user.middle_name,user.last_name,user.nic_number,user.birth_day,gender.gender,district.name,province.prov_name,moh_division.moh_name,user.address,user.phone_number,status.status_name,vaccine_status.vaccine_status_name,blood_type.blood_type_name,user_type.user_type_name
                                            FROM user,gender,district,province,moh_division,status,vaccine_status,blood_type,user_type
                                            WHERE user_id = $user_id AND user.gender_id = gender.gender_id AND user.district_id = district.district_id AND user.province_id = province.province_id AND user.blood_type_id = blood_type.blood_type_id AND user.moh_division_id = moh_division.moh_division_id AND user.status_id = status.status_id AND user.vaccine_status_id = vaccine_status.vaccine_status_id AND user.user_type_id = user_type.user_type_id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->user = new User($row["account_id"],$row["password"],$row["email_address"],$row["first_name"],$row["middle_name"],$row["last_name"],$row["nic_number"],
                $row["birth_day"],$row["gender"],$row["name"],$row["prov_name"],$row["moh_name"],$row["address"],$row["phone_number"],$row["status_name"],$row["vaccine_status_name"],$row["blood_type_name"],$row["user_type_name"]);
        }
        return $this->user;
}
}

//$me = new User(1,"a","b","supun","dhananjaya","dasanayaka",777,1999-7-5,"male","ga","ga","ga","ga","ga","ga");

?>

