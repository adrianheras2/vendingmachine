<?php

namespace Domain\Service;

use App\Repository\EntityRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class ProductService
{
    private $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Search by name
     *
     * @param string $name
     * @return object
     */
    public function searchByName(string $name): object
    {
        return $this->repository->searchByName($name);
    }

    /**
     * @return array
     */
    public function index(): array
    {
        return $this->repository->index();
    }

    /**
     * @param array $criteria
     * @return object|null
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param object $product
     * @return object
     */
    public function create(object $product): object
    {
        return $this->repository->create($product);
    }

    /**
     * @param int $id
     * @param array $pairs
     * @return object|null
     */
    public function update(int $id, array $pairs): ?object
    {
        return $this->repository->update($id, $pairs);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function delete(int $id): ?object
    {
        return $this->repository->delete($id);
    }
}
