<?php

namespace Domain\UseCasesValidator;

interface IValidator
{
    public static function validate($something): bool;
}
