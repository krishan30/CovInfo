<?php

require_once ("State.php");


abstract class NotificationState implements State {
    private int $notificationID;

    public function __construct(int $notificationID){
        $this->$notificationID=$notificationID;
    }

    public function getNotificationID(): int
    {
        return $this->notificationID;
    }
    public abstract function ViewNotification(Notification $notification);
}



