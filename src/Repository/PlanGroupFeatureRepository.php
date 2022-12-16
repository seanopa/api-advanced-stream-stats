<?php

namespace App\Repository;

use App\Entity\PlanGroupFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanGroupFeature>
 *
 * @method PlanGroupFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanGroupFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanGroupFeature[]    findAll()
 * @method PlanGroupFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanGroupFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanGroupFeature::class);
    }

    public function save(PlanGroupFeature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlanGroupFeature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PlanGroupFeature[] Returns an array of PlanGroupFeature objects
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

//    public function findOneBySomeField($value): ?PlanGroupFeature
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
