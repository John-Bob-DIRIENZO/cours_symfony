<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @return Criteria
     */
    public static function createApprovedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('status', Answer::APPROVED));
    }

    /**
     * @param int $max
     * @return Answer[]
     * @throws QueryException
     */
    public function findAllApproved(int $max = 10): array
    {
        return $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $max
     * @return Answer[]
     * @throws QueryException
     */
    public function findMostPopular(string $search = null, int $max = 10): array
    {
        $queryBuilder = $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            ->innerJoin('answer.user', 'user')
            ->addSelect('user')
            ->orderBy('answer.votes', 'DESC');

        if ($search !== null) {
            $queryBuilder
                ->innerJoin('answer.question', 'question')
                ->andWhere('answer.content LIKE :searchTerm 
                OR question.name LIKE :searchTerm 
                OR user.firstName LIKE :searchTerm 
                OR user.lastName LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        return $queryBuilder
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

}
