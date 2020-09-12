<?php

namespace Domain\UseCasesValidator;


use Domain\Entity\VendingMachine;

class MoneyValidator implements IMoneyValidator
{
    public static function validate($coinValue): bool
    {
        return in_array($coinValue, VendingMachine::AVAILABLE_COINS);
    }
}
