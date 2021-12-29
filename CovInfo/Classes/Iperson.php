<?php

interface Iperson
{
    public function getAccountID();
    public function getUser();
    public function getUserType();
    public function getEmailAddress();
    public function getFirstName();
    public function getLastName();
    public function getNICNumber();
    public function getGender();
    public function getMiddleName();
    public function getAddress();
    public function getFullName(): string;


}