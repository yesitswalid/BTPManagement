<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
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
    public function remove(User $entity, bool $flush = true): void
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
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC')
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
        $query = $this->createQueryBuilder('u');
        $query
            ->where(
                $query->expr()->andX(
                    $query->expr()->orX(
                        $query->expr()->like('u.nom', ':word'),
                        $query->expr()->like('u.prenom', ':word'),
                        $query->expr()->like('u.adresse', ':word'),
                        $query->expr()->like('u.matricule', ':word')
                    )
                )
            )
            ->setParameter('word', '%' . $word . '%');
        $paginator = $this->getPaginationQuery($query, $page, $limit);
        return [$paginator[0]->getResult(), $paginator[1]];
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
