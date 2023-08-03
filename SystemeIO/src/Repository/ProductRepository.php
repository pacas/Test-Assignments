<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        protected ManagerRegistry $registry,
        private EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $this->manager->persist($product);
        $this->manager->flush();
    }

}