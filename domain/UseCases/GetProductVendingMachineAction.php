<?php

namespace Domain\UseCases;

use Domain\Entity\VendingMachine;
use Domain\Entity\Money;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Domain\UseCasesValidator\IValidator;

class GetProductVendingMachineAction extends AbstractVendingMachineAction
{
    private $action;
    private $vendingMachine;
    private $validator;


    public function __construct(string $action, VendingMachine $vendingMachine, IValidator $validator)
    {
        $this->action = $action;
        $this->vendingMachine = $vendingMachine;
        $this->validator = $validator;
    }

    /**
     * Get product action
     *
     * - if there is enought money, it returns the product
     *
     * - in other case: it returns error: no enough money
     *
     * @return array
     * @throws \Exception
     */
    public function doAction()
    {
        if (!$this->validator->validateAction($this->action)) {
            throw new NotFoundHttpException("The vending machine action is not valid");
        }

        $result = $this->vendingMachine->getResult();

        $aAction = explode('-', $this->action);
        $productName = $aAction[1];

        // Checking & getting the asked product exists
        $product = $this->vendingMachine->getAvailableProductByName($productName);
        if (($product === null) || ($product->getCount() === 0)) {
            $result[] = 'NO-PRODUCT-AVAILABLE';
            $this->vendingMachine->setResult($result);
            return $this->vendingMachine;
        }

        // Checkin if there is enough money
        $insertedMoney = $this->vendingMachine->getInsertedMoney();
        $cInsertedMoney = 0;
        foreach ($insertedMoney as $money) {
            $cInsertedMoney += $money->getAmount() * $money->getCount();
        }

        $diffMoney = $cInsertedMoney - $product->getPrice();
        if ($diffMoney < 0) {
            $result[] = 'NO-ENOUGH-MONEY';
            $this->vendingMachine->setResult($result);
            return $this->vendingMachine;
        }

        // Now there is one less product
        $productCount = $product->getCount();
        $productCount--;
        $product->setCount($productCount);
        $this->vendingMachine->updateAvailableProduct($product);

        $result[] = $productName;

        // Only return with the available returning coin amounts
        $result = array_merge($result, $this->returnAmount($diffMoney));

        $this->vendingMachine->setResult($result);
        return $this->vendingMachine;

    }


    private function returnAmount(float $amount)
    {
        $result = [];
        foreach (VendingMachine::AVAILABLE_RETURN_COINS as $coinAmount) {

            $money = $this->vendingMachine->getAvailableMoneyByAmount($coinAmount);
            $nCoins = ($money !== NULL) ?  $money->getCount() : 0;

            // While there are available iesim type coins at vending machine
            while ($nCoins > 0){

                // If we returns alls
                if ($amount <= 0){
                    break 2;
                }

                $diff = $amount - $coinAmount;

                // TODO: see why sometimes $diff, i.e. is the result of float(0.05) - float(0.05), and is not zero,
                // and its a very big number, sometimes positive, sometimes negative
                if (($diff < 0) && (strlen(strval($diff)) < 10)) {
                    // Continuoing with the next coin amount
                    break;
                }

                $amount -= $coinAmount;
                $count = $money->getCount();
                $money->setCount(--$count);

                $result[] = $money;

                $this->vendingMachine->updateAvailableMoney($money);

                if ($count <= 0) {
                    break;
                }
            }
        }

        if ($amount > 0){
            // This can happen because the available return coins are not the same as the availabe insert coins
            $result[] = 'NO-MORE-AVAILABLE-MONEY';
        }

        return $result;
    }


}
