<?php

namespace Domain\UseCases;

use Domain\Entity\Money;
use Domain\Entity\VendingMachine;

class InsertMoneyVendingMachineAction extends AbstractVendingMachineAction
{
    private $action;
    private $vendingMachine;

    public function __construct(float $action, VendingMachine $vendingMachine)
    {
        $this->action = $action;
        $this->vendingMachine = $vendingMachine;
    }

    public function doAction()
    {
        $money = $this->vendingMachine->getInsertedMoneyByAmount($this->action);
        if ($money === NULL) {
            $money = new Money();
            $money->setAmount($this->action);
            $money->setCount(0);
        }
        $count = $money->getCount();
        $count++;

        $money->setCount($count);
        $this->vendingMachine->updateInsertedMoney($money);

        $money = $this->vendingMachine->getAvailableMoneyByAmount($this->action);
        if ($money !== NULL) {
            $money->setCount($money->getCount() + 1);
            $this->vendingMachine->updateAvailableMoney($money);
        }

        return $this->vendingMachine;
    }
}
