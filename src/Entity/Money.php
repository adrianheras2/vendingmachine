<?php

namespace App\Entity;

use App\Repository\MoneyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoneyRepository::class)
 */
class Money
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=VendingMachine::class)
     */
    private $vendingMachine;

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
}
