<?php

abstract class UserState{
    public abstract function addPatient(User  $user);
    public abstract function startQuarantine(User  $user);
    public abstract function endQuarantine(User  $user);
    public abstract function reportDeath(User  $user);
}

class Healthy extends UserState{

    public function addPatient(User $user)
    {

    }

    public function startQuarantine(User $user)
    {

    }

    public function endQuarantine(User $user)
    {

    }

    public function reportDeath(User $user)
    {

    }
}

class Quarantined extends UserState{

    public function addPatient(User $user)
    {
        // TODO: Implement addPatient() method.
    }

    public function startQuarantine(User $user)
    {
        // TODO: Implement startQuarantine() method.
    }

    public function endQuarantine(User $user)
    {
        // TODO: Implement endQuarantine() method.
    }

    public function reportDeath(User $user)
    {
        // TODO: Implement reportDeath() method.
    }
}

class Infected extends UserState{

    public function addPatient(User $user)
    {
        // TODO: Implement addPatient() method.
    }

    public function startQuarantine(User $user)
    {
        // TODO: Implement startQuarantine() method.
    }

    public function endQuarantine(User $user)
    {
        // TODO: Implement endQuarantine() method.
    }

    public function reportDeath(User $user)
    {
        // TODO: Implement reportDeath() method.
    }
}

class Deceased extends UserState{

    public function addPatient(User $user)
    {
        // TODO: Implement addPatient() method.
    }

    public function startQuarantine(User $user)
    {
        // TODO: Implement startQuarantine() method.
    }

    public function endQuarantine(User $user)
    {
        // TODO: Implement endQuarantine() method.
    }

    public function reportDeath(User $user)
    {
        // TODO: Implement reportDeath() method.
    }
}
