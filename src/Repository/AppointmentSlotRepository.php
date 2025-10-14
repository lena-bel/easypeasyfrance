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

    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAvailableSlots(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isBooked = :booked')
            ->setParameter('booked', false)
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function isSlotTaken(\DateTimeInterface $date, \DateTimeInterface $time): bool
    {
        return (bool) $this->createQueryBuilder('a')
            ->andWhere('a.preferredDate = :date')
            ->andWhere('a.preferredTime = :time')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('time', $time->format('H:i:s'))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAvailableSlotsQueryBuilder()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isBooked = :booked')
            ->setParameter('booked', false)
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.time', 'ASC');
    }

    public function findSlotWithAppointment(int $id): ?AppointmentSlot
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.appointment', 'a')
            ->addSelect('a')
            ->leftJoin('a.user', 'u')
            ->addSelect('u')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
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
