<?php

require_once ("User.php");
require_once ("PDOSingleton.php");
require_once ("DataBaseAdapter.php");
require_once ("UserProxy.php");
require_once ("IFactory.php");

class UserProxyFactory implements IFactory{
    private $userProxy;

    public function build($user_id){
        $sqlStmt = "SELECT user.account_id,user.email_address,user.first_name,user.middle_name,user.last_name,user.nic_number,gender.gender,user.address,status.status_name,user_type.user_type_name
                                            FROM user,gender,status,user_type
                                            WHERE user_id = $user_id AND user.gender_id = gender.gender_id  AND user.status_id = status.status_id AND user.user_type_id = user_type.user_type_id";
        $outPut = DataBaseAdapter::GetData($sqlStmt);
        $row = $outPut[0][0];
        $this->userProxy = new UserProxy($row["account_id"],$row["email_address"],$row["first_name"],$row["middle_name"],$row["last_name"],$row["nic_number"],
            $row["gender"],$row["address"],$row["status_name"],$row["user_type_name"]);
        return $this->userProxy;
    }
}