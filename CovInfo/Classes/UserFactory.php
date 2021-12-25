<?php

require_once ("User.php");
require_once ("PDOSingleton.php");
require_once ("DataBaseAdapter.php");
require_once ("IFactory.php");
class UserFactory implements IFactory{
    private $user;

    public function build($user_id){
        $sqlStmt = "SELECT user.account_id,user.password,user.email_address,user.first_name,user.middle_name,user.last_name,user.nic_number,user.birth_day,gender.gender,district.name,province.prov_name,moh_division.moh_name,user.address,user.phone_number,status.status_name,vaccine_status.vaccine_status_name,blood_type.blood_type_name,user_type.user_type_name
                                            FROM user,gender,district,province,moh_division,status,vaccine_status,blood_type,user_type
                                            WHERE user_id = $user_id AND user.gender_id = gender.gender_id AND user.district_id = district.district_id AND user.province_id = province.province_id AND user.blood_type_id = blood_type.blood_type_id AND user.moh_division_id = moh_division.moh_division_id AND user.status_id = status.status_id AND user.vaccine_status_id = vaccine_status.vaccine_status_id AND user.user_type_id = user_type.user_type_id";
        $outPut = DataBaseAdapter::GetData($sqlStmt);
        $row = $outPut[0][0];
        $this->user = new User($row["account_id"],$row["password"],$row["email_address"],$row["first_name"],$row["middle_name"],$row["last_name"],$row["nic_number"],
            $row["birth_day"],$row["gender"],$row["name"],$row["prov_name"],$row["moh_name"],$row["address"],$row["phone_number"],$row["status_name"],$row["vaccine_status_name"],$row["blood_type_name"],$row["user_type_name"]);
        return $this->user;
    }
}