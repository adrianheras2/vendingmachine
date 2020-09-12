<?php

namespace Domain\UseCases;

use Domain\Entity\VendingMachine;

class ReturnMoneyVendingMachineAction extends AbstractVendingMachineAction
{

    private $vendingMachine;

    const GET = 'GET-';
    const RETURNCOIN = 'RETURN-COIN';

    public function __construct(VendingMachine $vendingMachine)
    {

        $this->vendingMachine = $vendingMachine;
    }

    public function doAction()
    {
        $result =  $this->vendingMachine->getResult();
        foreach ($this->vendingMachine->getInsertedMoney() as $money){
            for ($i=1; $i<=$money->getCount(); $i++) {
                $result[] = $money;
            }
            $money->setCount(0);
            $this->vendingMachine->updateInsertedMoney($money);
        }

        $this->vendingMachine->setResult($result);
        return $this->vendingMachine;

    }


}
