<?php

namespace App\Repository;

use App\Entity\Chantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chantier>
 *
 * @method Chantier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chantier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chantier[]    findAll()
 * @method Chantier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chantier::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Chantier $entity, bool $flush = true): void
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
    public function remove(Chantier $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

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
        $query = $this->createQueryBuilder('c');
        $query
            ->where(
                $query->expr()->andX(
                    $query->expr()->orX(
                        $query->expr()->like('c.nom', ':word'),
                        $query->expr()->like('c.adresse', ':word')
                    ),
                    $query->expr()->isNotNull('c.startDate')
                )
            )
            ->setParameter('word', '%' . $word . '%');

        $paginator = $this->getPaginationQuery($query, $page, $limit);

        return [$paginator[0]->getResult(), $paginator[1]];

    }


    // /**
    //  * @return Chantiers[] Returns an array of Chantiers objects
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
    public function findOneBySomeField($value): ?Chantiers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
