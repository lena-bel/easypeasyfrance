<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }


//     public function isSlotTaken(\DateTimeInterface $date, \DateTimeInterface $time): bool
// {
//     return (bool) $this->createQueryBuilder('a')
//         ->andWhere('a.preferredDate = :date')
//         ->andWhere('a.preferredTime = :time')
//         ->setParameter('date', $date->format('Y-m-d'))
//         ->setParameter('time', $time->format('H:i:s'))
//         ->getQuery()
//         ->getOneOrNullResult();
// }

    public function findByUser($user) : ?Appointment
    {
        return $this->createQueryBuilder('a')
        ->andWhere('a.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function findAvailableSlots() :array{
        return $this->createQueryBuilder('s')
        ->andWhere('s.isBooked = :booked')
        ->setParameter('booked', false)
        ->orderBy('s.date', 'ASC')
        ->addOrderBy('s.time', 'ASC')
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Appointment[] Returns an array of Appointment objects
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

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
