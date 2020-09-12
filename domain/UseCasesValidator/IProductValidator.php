<?php

namespace Domain\UseCasesValidator;

interface IProductValidator extends IValidator
{
    public static function validate($something): bool;
}
