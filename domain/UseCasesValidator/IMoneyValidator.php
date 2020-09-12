<?php

namespace Domain\UseCasesValidator;

interface IMoneyValidator extends IValidator
{
    public static function validate($something): bool;
}
