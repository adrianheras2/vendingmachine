<?php

namespace Domain\Entity;

class Product
{
    private $id;
    private $name;
    private $price;
    private $vendingMachine;
    private $count;

    public function __toString()
    {
        $product = [
          'name' => $this->getName(),
          'price' => $this->getPrice(),
        ];
        return json_encode($product);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getVendingMachine(): ?VendingMachine
    {
        return $this->vendingMachine;
    }

    public function setVendingMachine(?VendingMachine $vendingMachine): self
    {
        $this->vendingMachine = $vendingMachine;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }


}
