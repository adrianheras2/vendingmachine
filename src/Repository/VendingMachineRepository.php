<?php

namespace App\Repository;

use App\Entity\VendingMachine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VendingMachine|null find($id, $lockMode = null, $lockVersion = null)
 * @method VendingMachine|null findOneBy(array $criteria, array $orderBy = null)
 * @method VendingMachine[]    findAll()
 * @method VendingMachine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VendingMachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VendingMachine::class);
    }
}
