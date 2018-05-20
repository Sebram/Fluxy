<?php

namespace App\Repository;

use App\Fluxy\Entity\Habitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Habitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habitation[]    findAll()
 * @method Habitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class HabitationRepository extends \Doctrine\ORM\EntityRepository {  

    public function findByDistinctFields($value)
    {
        return $this->createQueryBuilder('h')
            ->select('h.'.strtolower($value))
            ->distinct(true)
            ->getQuery()
            ->getResult()
        ;
    }
    

    public function updateFields($field, $oldval, $newval)
    {
        if($this->createQueryBuilder('h')
            ->update(Habitation::class, 'h')
            ->set('h.'.strtolower($field), '?1')
            ->where('h.'.strtolower($field).' = ?2')
             ->setParameter(1, $newval)
             ->setParameter(2, $oldval)
            ->getQuery()
            ->execute()
            ) {
            return true;
         }
    }
    
}
