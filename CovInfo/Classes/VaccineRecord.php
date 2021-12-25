<?php

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