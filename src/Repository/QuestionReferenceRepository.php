<?php

namespace App\Repository;

use App\Entity\QuestionReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionReference[]    findAll()
 * @method QuestionReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionReference::class);
    }

    // /**
    //  * @return QuestionReference[] Returns an array of QuestionReference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionReference
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
