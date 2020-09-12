<?php

namespace Domain\UseCasesValidator;


use Domain\Entity\VendingMachine;

class ProductValidator implements IProductValidator
{
    public static function validate($productName): bool
    {
        // Validating action is GET-something alphanumeric
        return preg_match('|^[a-zA-Z\d]+$|', $productName) &&
            in_array($productName, VendingMachine::AVAILABLE_PRODUCTS);
    }


    public static function validateAction($action): bool
    {
        // Validating action is GET-something alphanumeric
        return preg_match('|^GET-[a-zA-Z\d]+$|', $action);
    }

}
