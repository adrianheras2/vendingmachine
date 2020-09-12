<?php

namespace Domain\UseCases;

use Domain\Entity\VendingMachine;
use Domain\UseCasesValidator\MoneyValidator;
use Domain\UseCasesValidator\ProductValidator;
use App\Repository\EntityRepositoryInterface;

class VendingMachineActionFactory
{

    const GET = 'GET-';
    const RETURNCOIN = 'RETURN-COIN';
    const SERVICEMODE = 'SERVICE';

    /**
     * Factory method
     *
     * @param string $action
     * @param VendingMachine $vendingMachine
     * @return DefaultVendingMachineActionFactory|MoneyVendingMachineAction
     */
    public function create(string $action, VendingMachine $vendingMachine)
    {
        if (preg_match('|^SERVICE-(?<coinValue>\d+(\.\d*){0,1})-(?<count>\d+)$|', $action, $matches)) {
            // Action format: <float or integer>-<integer>
            return new setChangeVendingMachineAction(floatval($matches['coinValue']), intval($matches['count']), $vendingMachine, new MoneyValidator());
        } elseif (preg_match('|^SERVICE-(?<productName>[A-Za-z]+)-(?<count>\d+)|', $action, $matches)) {
            // Action format: <alphabetic word>-<integer>
            return new setItemsVendingMachineAction($matches['productName'], intval($matches['count']), $vendingMachine, new ProductValidator());
        } elseif (is_numeric($action)) {
            $action = floatval($action);
            return new InsertMoneyVendingMachineAction($action, $vendingMachine);
        } elseif ($action === self::RETURNCOIN) {
            return new ReturnMoneyVendingMachineAction($vendingMachine);
        } else if (ProductValidator::validateAction($action)) {
            return new GetProductVendingMachineAction($action, $vendingMachine, new ProductValidator());
        } else {
            throw new \InvalidArgumentException("The data you have entered is not valid");
        }
    }
}
