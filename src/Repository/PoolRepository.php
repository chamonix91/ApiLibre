<?php

namespace App\Repository;

use App\Entity\Pool;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pool[]    findAll()
 * @method Pool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pool::class);
    }

    // /**
    //  * @return Company[] Returns an array of Company objects
    //  */
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
    public function findOneBySomeField($value): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $datepool
     * @param $company
     * @return array
     */
    public function findByCompany($datepool, $company)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from(Pool::class, 'u')
            ->where('u.datepool LIKE :datepool')
            ->andWhere('u.company LIKE :company')
            ->setParameter('datepool', '%"'.$datepool.'"%' )
            ->setParameter('company', $company);

        //dump($qb->getQuery()->getResult());die();
        return $qb->getQuery()->getResult();
    }
}
