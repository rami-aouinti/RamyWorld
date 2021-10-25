<?php

namespace App\Repository;

use App\Entity\QuizResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizResponse[]    findAll()
 * @method QuizResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizResponse::class);
    }

    // /**
    //  * @return QuizResponse[] Returns an array of QuizResponse objects
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
    public function findOneBySomeField($value): ?QuizResponse
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
