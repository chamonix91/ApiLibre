<?php

namespace App\Repository;

use App\Entity\Departure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Departure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departure[]    findAll()
 * @method Departure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departure::class);
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
     * @param $from
     * @param $to
     * @return array
     */
    public function findByDatedeparture($from, $to)
    {


        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->where('u.datedeparture BETWEEN :from AND :to')
            ->setParameter('from', $from->format('Y-m-d') )
            ->setParameter('to', $to->format('Y-m-d') );

           /* ->from(Departure::class, 'u')
            ->where('u.datepdeparture LIKE :datepdeparture')
            ->setParameter('datepool', '%"'.$datepdeparture.'"%' );*/

        //dump($qb->getQuery()->getResult());die();
        return $qb->getQuery()->getResult();
    }
}
