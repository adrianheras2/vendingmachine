<?php

namespace Domain\Entity;

class Money
{
    private $id;
    private $amount;
    private $vendingMachine;
    private $count;

    public function __toString()
    {
        return  number_format($this->amount, 2, '.', '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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
