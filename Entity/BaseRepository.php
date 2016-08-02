<?php

namespace Aescarcha\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BaseRepository
 * This class adds a couple of methods to all repositories
 */
class BaseRepository extends EntityRepository
{
    public function getLast( $n = 1 )
    {
       $query = $this->createQueryBuilder('p')
       ->setMaxResults($n)
       ->orderBy('p.id', 'DESC')
       ->getQuery();
       if($n === 1 ){
            return $query->getOneOrNullResult();
        }
       return $query->getResult(); 
    }
}
