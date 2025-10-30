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




    public function findAvailableAppointmentByUser($user): ?Appointment
    {
        return $this->createQueryBuilder('apt')
            ->andWhere('apt.user = :user')
            ->andWhere('apt.status != :cancelled')
            ->setParameter('user', $user)
            ->setParameter('cancelled', 'cancelled')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAvailableAppointments(): array
    {
        $start = new \DateTime('today');
        $end = (new \DateTime('today'))->setTime(23, 59, 59);

        return $this->createQueryBuilder('apt')
            ->join('apt.appointmentSlot', 'slot')           
            ->andWhere('apt.status LIKE :status')           
            ->andWhere('slot.date BETWEEN :start AND :end') 
            ->setParameter('status', '%active%')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function getAllAppointments(): int
    {
        return $this->createQueryBuilder('apt')
            ->select('COUNT(apt.id)')
            ->where('apt.status LIKE :status')
            ->setParameter('status', '%active%')
            ->getQuery()
            ->getSingleScalarResult();
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
