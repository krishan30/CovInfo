<?php

abstract class StateFactory{
    public function createState(int $stateID)
    {
        return $this->factoryMethod($stateID);
    }

    protected abstract function factoryMethod(int $stateID);
}
