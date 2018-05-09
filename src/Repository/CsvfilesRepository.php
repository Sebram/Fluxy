<?php

namespace App\Repository;

use App\Entity\Csvfiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Csvfiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Csvfiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Csvfiles[]    findAll()
 * @method Csvfiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvfilesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Csvfiles::class);
    }

//    /**
//     * @return Csvfiles[] Returns an array of Csvfiles objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Csvfiles
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
