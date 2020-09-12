<?php

namespace App\Controller;

use Domain\Entity\Product;
use Domain\Entity\VendingMachine;
use InterfaceAdapters\Adapter\ApiAdapter;
use InterfaceAdapters\Presenter\ConsolePresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VendingMachineController extends AbstractController
{
    /**
     * @Route("/api/vendingmachine/actions/{actions}", name="buy_product_vending_machine")
     */
    public function vendingMachineActions(string $actions)
    {

        $vendingMachine = new VendingMachine();

        $adapter = new ApiAdapter();
        $presenter = new ConsolePresenter();
        $aActions = $adapter->adaptActions($actions);

        try {
            foreach($aActions as $action) {
                $vendingMachine = $vendingMachine->doAction($action, $vendingMachine);
            }
        } catch (\Exception $e) {
            return $presenter->presentError($e->getMessage());
        }

        return $presenter->presentResult($vendingMachine->getResult());
    }
}
