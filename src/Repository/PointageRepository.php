<?php

namespace App\Repository;

use App\Entity\Pointage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pointage>
 *
 * @method Pointage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pointage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pointage[]    findAll()
 * @method Pointage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pointage::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Pointage $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Pointage $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $query
     * @param $page
     * @param $limit
     * @return array
     */
    private function getPaginationQuery($query, $page, $limit)
    {
        $paginator = new Paginator($query);
        $total = $paginator->count();
        $pageCount = ceil($total / $limit);
        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page-1))
            ->setMaxResults($limit);
        return [$paginator->getQuery(), $pageCount];
    }


    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getPagination(int $page, int $limit = 10)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        $paginator = $this->getPaginationQuery($query, $page, $limit);
        return [$paginator[0]->getResult(), $paginator[1]];
    }


    /**
     * @param string $word
     * @param $page
     * @param $limit
     * @return array
     */

    //ToDo: méthode pour faire une recherche implementation dans le future.
    public function searchPagination(string $word, $page, $limit = 10)
    {
        $query = $this->createQueryBuilder('p');
        $query
            ->where(
                $query->expr()->andX(
                    $query->expr()->orX(
                        $query->expr()->like('p.duree', ':word')
                    ),
                    $query->expr()->isNotNull('c.date')
                )
            )
            ->setParameter('word', '%' . $word . '%');

        $paginator = $this->getPaginationQuery($query, $page, $limit);

        return [$paginator[0]->getResult(), $paginator[1]];

    }

    // /**
    //  * @return Pointage[] Returns an array of Pointage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pointage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
