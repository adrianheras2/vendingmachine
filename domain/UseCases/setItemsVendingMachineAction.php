<?php

namespace Domain\UseCases;

use Domain\Entity\Product;
use Domain\Entity\VendingMachine;
use Domain\UseCasesValidator\IProductValidator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class setItemsVendingMachineAction extends AbstractVendingMachineAction
{

    private $vendingMachine;
    private $validator;

    public function __construct(string $productName, int $count, VendingMachine $vendingMachine, IProductValidator $validator)
    {
        $this->productName = $productName;
        $this->count = $count;
        $this->vendingMachine = $vendingMachine;
        $this->validator = $validator;
    }

    public function doAction()
    {
        if (!$this->validator->validate($this->productName)) {
            throw new NotFoundHttpException("The product name is not valid");
        }

        $result = $this->vendingMachine->getResult();

        $product = new Product();
        $product->setName($this->productName);
        $product->setCount($this->count);
        $product->setPrice(VendingMachine::PRODUCT_PRICES[$this->productName]);

        $this->vendingMachine->updateAvailableProduct($product);

        $result[] = "SERVICE-" . $this->count . "x" . $this->productName;

        $this->vendingMachine->setResult($result);
        return $this->vendingMachine;

    }
}
