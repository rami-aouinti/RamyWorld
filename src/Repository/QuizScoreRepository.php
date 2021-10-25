<?php

namespace App\Repository;

use App\Entity\QuizScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizScore[]    findAll()
 * @method QuizScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizScore::class);
    }

    // /**
    //  * @return QuizScore[] Returns an array of QuizScore objects
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
    public function findOneBySomeField($value): ?QuizScore
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
