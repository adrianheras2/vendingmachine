<?php

namespace Domain\UseCases;

use Domain\Entity\VendingMachine;
use Domain\Entity\Money;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Domain\UseCasesValidator\IMoneyValidator;

class setChangeVendingMachineAction extends AbstractVendingMachineAction
{
    private $action;
    private $vendingMachine;
    private $validator;


    public function __construct(float $coinValue, int $count, VendingMachine $vendingMachine, IMoneyValidator $validator)
    {
        $this->coinValue = $coinValue;
        $this->count = $count;
        $this->vendingMachine = $vendingMachine;
        $this->validator = $validator;
    }

    public function doAction()
    {
        if (!$this->validator->validate($this->coinValue)) {
            throw new NotFoundHttpException("The coin value is not valid");
        }

        $result = $this->vendingMachine->getResult();

        $money = new Money();
        $money->setAmount($this->coinValue);
        $money->setCount($this->count);

        $this->vendingMachine->updateAvailableMoney($money);
        $result[] = "SERVICE-" . $this->count . "x" . number_format($this->coinValue, 2, '.', '');

        $this->vendingMachine->setResult($result);
        return $this->vendingMachine;

    }
}
