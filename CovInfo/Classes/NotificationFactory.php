<?php
class NotificationFactory {

    public static function buildNotification(int $notificationID): ?Notification
    {
        $connection=PDOSingleton::getInstance();
        $sqlStmt = "SELECT receiver_id,sent_date_time,read_status_id,notification_message FROM notification where notification_id=$notificationID";
        $stmt=$connection->query($sqlStmt);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            $dateAndTime=explode(" ",$row['sent_date_time']);
            return new Notification($notificationID,$row['read_status_id'],$row['receiver_id'],$dateAndTime[0],$dateAndTime[1],$row['notification_message']);
        }else{
            return null;
        }

    }
}
