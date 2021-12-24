<?php
abstract class NotificationState{
    public abstract function ViewNotifications();
}

class Unread extends NotificationState{

    public function ViewNotifications()
    {

    }
}

class Read extends NotificationState{

    public function ViewNotifications()
    {
        // TODO: Implement ViewNotifications() method.
    }
}