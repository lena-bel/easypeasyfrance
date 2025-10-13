<?php

namespace App\Repository;

use App\Entity\AppointmentSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppointmentSlot>
 */
class AppointmentSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentSlot::class);
    }

    public function findAllOrdered() :array{
        return $this->createQueryBuilder('s')
        ->orderBy('s.date', 'ASC')
        ->addOrderBy('s.time','ASC')
        ->getQuery()
        ->getResult();
    }

    // public function findDuplicateSlot(\DateTimeInterface $date, \DateTimeInterface $time): ?AppointmentSlot{
    //     return $this->createQueryBuilder('s')
    //     ->where('s.date = :date')
    //     ->andWhere('s.time = :time')
    //     ->setParameters([
    //         'date' => $date,
    //         'time' => $time,
    //     ])
    //     ->getQuery()
    //     ->getOneOrNullResult();

    // }
    //    /**
    //     * @return AppointmentSlot[] Returns an array of AppointmentSlot objects
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

    //    public function findOneBySomeField($value): ?AppointmentSlot
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
