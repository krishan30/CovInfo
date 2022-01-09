<?php

class Notification{

    private int $notificationRecordID;
    private int $receiverID;
    private NotificationState $currentNotificationState;
    private String $receivedDate;
    private String $receivedTime;
    private String $notificationTypeHeading;

    public function __construct(int $notificationRecordID,int $readStatus,int $receiverID,String $receivedDate,String $receivedTime,String $notificationTypeHeading)
    {
        $this->notificationRecordID=$notificationRecordID;
        $this->receiverID=$receiverID;
        $this->receivedDate=$receivedDate;
        $this->notificationTypeHeading=$notificationTypeHeading;
        $this->receivedTime=$receivedTime;
        $notificationStateFactory=new NotificationStateFactory();
        $this->currentNotificationState=$notificationStateFactory->createState($readStatus);
    }

    public function getReceiverID(): int
    {
        return $this->receiverID;
    }

    public function getReceivedDate(): string
    {
       return $this->receivedDate;
    }

    public function getReceivedTime(): string
    {
        return $this->receivedTime;
    }

    public function getNotificationTypeHeading(): string
    {
        return $this->notificationTypeHeading;
    }

    public function  getCurrentNotificationState(): NotificationState
    {
        return $this->currentNotificationState;
    }

    public function  setCurrentNotificationState(NotificationState $notificationState){
        $connection = PDOSingleton::getInstance();
        $sql = "UPDATE notification set read_status_id=:read_status_id  where notification_id=:notification_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':notification_id'=>$this->notificationRecordID,':read_status_id'=>$notificationState->getNotificationID()));
        $this->currentNotificationState=$notificationState;
    }

    public function  markNotificationAsViewed(){
        $this->currentNotificationState->ViewNotification($this);
    }

}
