
<?php
require_once ("Iperson.php");
require_once ("Person.php");

abstract class PersonProxy implements Iperson
{
    private $user;

    private $user_id;
    private $accountID;
    private $emailAddress;
    private $firstName;
    private $middleName;
    private $lastName;
    private $NICNumber;
    private $gender;
    private $address;
    private $status;
    private $userType;

    /**
     * @param $accountID
     * @param $emailAddress
     * @param $firstName
     * @param $middleName
     * @param $lastName
     * @param $NICNumber
     * @param $gender
     * @param $address
     * @param $status
     * @param $userType
     */
    public function __construct($accountID, $emailAddress, $firstName, $middleName, $lastName, $NICNumber, $gender, $address, $status, $userType,$user_id)
    {
        $this->user_id = $user_id;
        $this->accountID = $accountID;
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->NICNumber = $NICNumber;
        $this->gender = $gender;
        $this->address = $address;
        $this->status = $status;
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }


    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getNICNumber()
    {
        return $this->NICNumber;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getFullName(): string
    {
        return $this->firstName." ".$this->middleName." ".$this->$this->lastName;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function getUserId(){
        return $this->user_id;
    }
}