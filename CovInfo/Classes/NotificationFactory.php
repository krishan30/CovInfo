<?php

require_once ("PDOSingleton.php");
require_once ("Notification.php");

class NotificationFactory {

    public static function buildNotification(int $notificationID):?Notification
    {
        $connection=PDOSingleton::getInstance();
        $sqlStmt = "SELECT receiver_id,sent_date_time,read_status_id,notification_type.notification_type_heading FROM notification JOIN notification_type ON notification_type.notification_type_id=notification.notification_type_id  where notification_id=:notification_id";
        $stmt=$connection->prepare($sqlStmt);
        $stmt->execute(array(":notification_id"=>$notificationID));
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            $dateAndTime=explode(" ",$row['sent_date_time']);
            return new Notification($notificationID,$row['read_status_id'],$row['receiver_id'],$dateAndTime[0],$dateAndTime[1],$row['notification_type_heading']);
        }else{
            return null;
        }

    }
}
