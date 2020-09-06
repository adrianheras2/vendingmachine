<?php

namespace App\Entity;

use App\Repository\VendingMachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VendingMachineRepository::class)
 */
class VendingMachine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="vendingMachine")
     */
    private $availableProducts;

    /**
     * @ORM\OneToMany(targetEntity=Money::class, mappedBy="vendingMachine")
     */
    private $availableMoney;

    /**
     * @ORM\OneToMany(targetEntity=Money::class, mappedBy="vendingMachine")
     */
    private $insertedMoney;


    public function __construct()
    {
        $this->availableProducts = new ArrayCollection();
        $this->availableMoney = new ArrayCollection();
        $this->insertedMoney = new ArrayCollection();
    }

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

    public function addAvailableProduct(Product $availableProduct): self
    {
        if (!$this->availableProducts->contains($availableProduct)) {
            $this->availableProducts[] = $availableProduct;
            $availableProduct->setVendingMachine($this);
        }

        return $this;
    }

    public function removeAvailableProduct(Product $availableProduct): self
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

    /**
     * @return Collection|Money[]
     */
    public function getAvailableMoney(): Collection
    {
        return $this->availableMoney;
    }

    public function addAvailableMoney(Money $availableMoney): self
    {
        if (!$this->availableMoney->contains($availableMoney)) {
            $this->availableMoney[] = $availableMoney;
            $availableMoney->setVendingMachine($this);
        }

        return $this;
    }

    public function removeAvailableMoney(Money $availableMoney): self
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

    public function addInsertedMoney(Money $insertedMoney): self
    {
        if (!$this->insertedMoney->contains($insertedMoney)) {
            $this->insertedMoney[] = $insertedMoney;
            $insertedMoney->setVendingMachine($this);
        }

        return $this;
    }

    public function removeInsertedMoney(Money $insertedMoney): self
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

}
