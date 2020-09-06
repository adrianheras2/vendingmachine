<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $container;

    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Product::class);
        $this->container = $container;
    }


    /**
     * Search products by name
     *
     * Clean Architecture: Layer 4th (Frameworks and Drivers)
     *
     * @param string $name
     * @return array
     */
    public function searchByName(string $name): object
    {
        $criteria = [
            'name' => $name
        ];

        $product = $this->findOneBy($criteria);
        return $product;
    }


    /**
     * @param object $product
     * @return object
     */
    public function create(object $product): object
    {
        $em = $this->container->get('doctrine')->getManager();

        $criteria = [
            'name' => $product->getName(),
            'price' => $product->getPrice()
        ];

        if (!$this->findBy($criteria)) {
            $em->persist($product);
            $em->flush();
        }
        return $product;
    }
}
