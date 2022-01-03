<?php
require_once("NotificationState.php");

class Unread extends NotificationState{

    public function  __construct()
    {
        parent::__construct(1);
    }

    public function ViewNotification(Notification $notification)
    {
        $notification->setCurrentNotificationState(new Read());
    }
}