<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function list(PaginatorInterface $paginator, Request $request, User $user)
    {
        $dql = $this->createQueryBuilder('t')
            ->andWhere("t.user = :user")
            ->setParameter("user", $user)
            ->getQuery()
            ;
        return $paginator->paginate(
            $dql, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function lastDay(PaginatorInterface $paginator, Request $request, User $user)
    {
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $dql = $this->createQueryBuilder('t')
            ->andWhere("t.user = :user")
            ->setParameter("user", $user)
            ->andWhere("t.start_date = :yesterday")
            ->setParameter("yesterday", $yesterday)
            ->getQuery()
        ;

        return $paginator->paginate(
            $dql, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function lastWeek(PaginatorInterface $paginator, Request $request, User $user)
    {
        $lastWeek = date('Y-m-d',strtotime("-7 days"));
        $dql = $this->createQueryBuilder('t')
            ->andWhere("t.user = :user")
            ->setParameter("user", $user)
            ->andWhere("t.start_date >= :lastWeek")
            ->setParameter("lastWeek", $lastWeek)
            ->getQuery()
        ;

        return $paginator->paginate(
            $dql, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function lastMonth(PaginatorInterface $paginator, Request $request, User $user)
    {
        $lastMonth = date('Y-m-d',strtotime("-30 days"));
        $dql = $this->createQueryBuilder('t')
            ->andWhere("t.user = :user")
            ->setParameter("user", $user)
            ->andWhere("t.start_date >= :lastMonth")
            ->setParameter("lastMonth", $lastMonth)
            ->getQuery()
        ;

        return $paginator->paginate(
            $dql, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
