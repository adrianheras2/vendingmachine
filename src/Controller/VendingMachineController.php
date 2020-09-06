<?php

namespace App\Controller;

use App\Entity\VendingMachine;
use App\Entity\Money;
use App\Entity\Product;
use Domain\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VendingMachineController extends AbstractController
{

    private $productService;

    public function __construct(ProductService $productservice)
    {
        $this->productService = $productservice;
    }

    /**
     * @Route("/api/product/{productName}/buy/{actions}", name="vending_machine")
     */
    public function buyProductAction(string $productName, string $actions)
    {
        $product = $this->productService->searchByName($productName);

        // TODO
        $product = [
            'name' => $product->getName(),
            'price' => $product->getPrice()
        ];


        $result = '';
        $vendingMachine = new VendingMachine();
        $aActions = explode(',', $actions);
        foreach($aActions as $action) {
            // if is a number
            if (is_numeric($action)) {
                $money = new Money();
                $money->setAmount(floatval($action));
                $vendingMachine->addInsertedMoney($money);
            } else if ($action === 'RETURN-COIN'){
                foreach ($vendingMachine->getInsertedMoney() as $insertedMoney){
                    $vendingMachine->removeInsertedMoney($insertedMoney);
                    $result .= $insertedMoney->getAmount() . ', ';
                }
                $result .= 'RETURN-COIN, ';
            } else {
                //TODO: if formato GET-ALGO
                $lala = explode('-', $action);
                $productName = $lala[1];

                // TODO: COMPROBAR QUE HAYA EL PRODUCTO

                // TODO: comprobar que hay dinero

                // TODO: si sí:
                $result .= $productName . ', ';

                // TODO: si sobra: devolver pasta
            }
        }

        return $this->json([
            'result' => $result
        ]);
    }
}
