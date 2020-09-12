<?php

namespace Domain\UseCases;

use Domain\Entity\VendingMachine;

abstract class AbstractVendingMachineAction
{
    private $action;
    private $vendingMachine;

    abstract public function doAction();

    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return VendingMachine
     */
    public function getVendingMachine(): VendingMachine
    {
        return $this->vendingMachine;
    }
}
