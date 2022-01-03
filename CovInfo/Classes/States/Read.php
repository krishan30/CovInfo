<?php
require_once("NotificationState.php");

class Read extends NotificationState{

    public function  __construct()
    {
        parent::__construct(2);
    }

    /**
     * @throws Exception
     */
    public function ViewNotification(Notification $notification)
    {
        throw new Exception("Invalid state transition request");
    }
}