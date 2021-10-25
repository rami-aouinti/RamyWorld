<?php

namespace App\Repository;

use App\Entity\QuizCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizCategory[]    findAll()
 * @method QuizCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizCategory::class);
    }

    // /**
    //  * @return QuizCategory[] Returns an array of QuizCategory objects
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
    public function findOneBySomeField($value): ?QuizCategory
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
