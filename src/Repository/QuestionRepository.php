<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @return Question[] Returns an array of Question objects
     */
    public function findAllAskedOrderByNewest()
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.askedAt IS NOT NULL')
            ->orderBy('q.askedAt', 'DESC')
            ->getQuery() // Pour effectivement créer la query
            ->getResult(); // Récupère les résultats
    }

    /**
     * @param $value
     * @return int|mixed|string
     */
    public function searchByName($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.name LIKE :val')
            ->setParameter('val', '%'. $value . '%')
            ->getQuery()
            ->getResult()
        ;
    }

}
