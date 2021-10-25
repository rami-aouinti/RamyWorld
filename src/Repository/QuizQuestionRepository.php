<?php

namespace App\Repository;

use App\Entity\QuizCategory;
use App\Entity\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestion[]    findAll()
 * @method QuizQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

    public function getQuestions($id)
    {
        $qb = $this->createQueryBuilder('q');
        $qb->leftJoin('q.answers', 'a');
        $qb->join('q.categories', 'c')
            ->where($qb->expr()->eq('c.id', $id));

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QuizQuestion[] Returns an array of QuizQuestion objects
     */
    public function findByCategory(array $value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.categories IN :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?QuizQuestion
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
