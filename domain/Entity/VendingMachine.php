<?php

namespace Domain\Entity;

use App\Repository\VendingMachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\UseCases\VendingMachineActionFactory;



class VendingMachine
{
    const PRODUCT_PRICES = [
        'WATER' => 0.65,
        'JUICE' => 1.00,
        'SODA' => 1.50
    ];

    const AVAILABLE_PRODUCTS = [
        'WATER' ,
        'JUICE' ,
        'SODA'
    ];

    const AVAILABLE_RETURN_COINS = [0.25, 0.10, 0.05];      //ordered from higher to lower
    const AVAILABLE_COINS = [1, 0.25, 0.10, 0.05];      //ordered from higher to lower

    private $id;

    private $availableProducts;

    private $availableMoney;

    private $insertedMoney;

    private $result;


    public function __construct()
    {
        $this->availableProducts = new ArrayCollection();
        $this->availableMoney = new ArrayCollection();
        $this->insertedMoney = new ArrayCollection();
    }

    public function doAction(string $action, VendingMachine $vendingMachine)
    {
        $vendingMachineActionFactory = new VendingMachineActionFactory();
        $oAction = $vendingMachineActionFactory->create($action, $vendingMachine);
        $vendingMachine = $oAction->doAction();

        return $vendingMachine;
    }

    // Getters, setters, updaters, adders and removers

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getAvailableProducts(): Collection
    {
        return $this->availableProducts;
    }

    public function getAvailableProductByName(string $productName)
    {
        $product = NULL;
        foreach ($this->availableProducts as $item){
            if ($item->getName() == $productName) {
                $product = $item;
                break;
            }
        }
        return $product;
    }

    private function addAvailableProduct(Product $availableProduct): self
    {
        if (!$this->availableProducts->contains($availableProduct)) {
            $this->availableProducts[] = $availableProduct;
            $availableProduct->setVendingMachine($this);
        }

        return $this;
    }

    private function removeAvailableProduct(Product $availableProduct): self
    {
        if ($this->availableProducts->contains($availableProduct)) {
            $this->availableProducts->removeElement($availableProduct);
            // set the owning side to null (unless already changed)
            if ($availableProduct->getVendingMachine() === $this) {
                $availableProduct->setVendingMachine(null);
            }
        }

        return $this;
    }

    public function updateAvailableProduct(Product $availableProduct): self
    {
        $product = $this->getAvailableProductByName($availableProduct->getName());
        if ($product !== NULL) {
            $this->removeAvailableProduct($product);
        }
        return $this->addAvailableProduct($availableProduct);
    }

    /**
     * @return Collection|Money[]
     */
    public function getAvailableMoney(): Collection
    {
        return $this->availableMoney;
    }

    public function getAvailableMoneyByAmount(string $amount)
    {
        $result = NULL;
        foreach ($this->availableMoney as $money){
            if ($money->getAmount() == $amount) {
                $result = $money;
                break;
            }
        }
        return $result;
    }

    private function addAvailableMoney(Money $availableMoney): self
    {
        if (!$this->availableMoney->contains($availableMoney)) {
            $this->availableMoney[] = $availableMoney;
            $availableMoney->setVendingMachine($this);
        }

        return $this;
    }

    public function updateAvailableMoney(Money $availableMoney): self
    {
        $money = $this->getAvailableMoneyByAmount($availableMoney->getAmount());
        if ($money !== NULL) {
            $this->removeAvailableMoney($money);
        }
        return $this->addAvailableMoney($availableMoney);
    }

    private function removeAvailableMoney(Money $availableMoney): self
    {
        if ($this->availableMoney->contains($availableMoney)) {
            $this->availableMoney->removeElement($availableMoney);
            // set the owning side to null (unless already changed)
            if ($availableMoney->getVendingMachine() === $this) {
                $availableMoney->setVendingMachine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Money[]
     */
    public function getInsertedMoney(): Collection
    {
        return $this->insertedMoney;
    }

    public function getInsertedMoneyByAmount(string $amount)
    {
        $result = NULL;
        foreach ($this->insertedMoney as $money){
            if ($money->getAmount() == $amount) {
                $result = $money;
                break;
            }
        }
        return $result;
    }

    private function addInsertedMoney(Money $insertedMoney): self
    {
        if (!$this->insertedMoney->contains($insertedMoney)) {
            $this->insertedMoney[] = $insertedMoney;
            $insertedMoney->setVendingMachine($this);
        }

        return $this;
    }

    private function removeInsertedMoney(Money $insertedMoney): self
    {
        if ($this->insertedMoney->contains($insertedMoney)) {
            $this->insertedMoney->removeElement($insertedMoney);
            // set the owning side to null (unless already changed)
            if ($insertedMoney->getVendingMachine() === $this) {
                $insertedMoney->setVendingMachine(null);
            }
        }

        return $this;
    }

    public function updateInsertedMoney(Money $insertedMoney): self
    {
        $money = $this->getInsertedMoneyByAmount($insertedMoney->getAmount());
        if ($money !== NULL) {
            $this->removeInsertedMoney($money);
        }
        return $this->addInsertedMoney($insertedMoney);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

}
