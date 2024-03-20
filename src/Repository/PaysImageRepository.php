<?php

namespace App\Repository;

use App\Entity\PaysImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaysImage>
 *
 * @method PaysImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaysImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaysImage[]    findAll()
 * @method PaysImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaysImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaysImage::class);
    }

//    /**
//     * @return PaysImage[] Returns an array of PaysImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PaysImage
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
