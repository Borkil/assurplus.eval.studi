<?php

namespace App\Repository;

use App\Entity\Sinister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sinister>
 *
 * @method Sinister|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sinister|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sinister[]    findAll()
 * @method Sinister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SinisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sinister::class);
    }

    public function save(Sinister $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sinister $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderByCreatedAt():array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s FROM App\Entity\Sinister s ORDER BY s.createdAt DESC'
        );

        return $query->getResult();
    }
//    /**
//     * @return Sinister[] Returns an array of Sinister objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sinister
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
