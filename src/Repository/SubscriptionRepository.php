<?php

namespace App\Repository;

use App\Entity\Plan;
use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 *
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function save(Subscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Subscription[] Returns an array of Subscription objects
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

//    public function findOneBySomeField($value): ?Subscription
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function disableDefault(\App\Entity\Membership $membership)
    {
        $subscription = $this->findOneBy(['membership' => $membership, 'external_id' => null, 'active' => true]);

        if (!empty($subscription)) {
            $subscription
                ->setActive(false)
                ->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')))
            ;
            $this->save($subscription, true);
        }
    }

    public function create(\App\Entity\Membership $membership, Plan $plan, ?string $external_subscription_id, $startDate, $endDate)
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $subscription = new Subscription();
        $subscription
            ->setMembership($membership)
            ->setPlan($plan)
            ->setExternalId($external_subscription_id)
            ->setCreatedAt($date)
            ->setUpdatedAt($date)
            ->setActive(true)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ;

        $this->save($subscription, true);

        return $subscription;
    }
}
