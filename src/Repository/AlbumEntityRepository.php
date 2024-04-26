<?php

namespace App\Repository;

use App\Entity\AlbumEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AlbumEntity>
 *
 * @method AlbumEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumEntity[]    findAll()
 * @method AlbumEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumEntity::class);
    }

    //    /**
    //     * @return AlbumEntity[] Returns an array of AlbumEntity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AlbumEntity
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
