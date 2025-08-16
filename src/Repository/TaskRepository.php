<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByVisaTypeProfileId(int $profileId): array
    {
        return $this->createQueryBuilder('task') //starts to build the query from task table giving it the allias task can be anything you want t or task table anything really but it's better to use words that are meaningfull
            ->innerJoin('task.visaType', 'visa')   // starts to do the inner join to the visatypeprofile table and since it is doctrine and knowsall the relations i did i won't need to do the three inner joins
            ->andWhere('visa.id = :profileId') // here it is specifying that it should display the tasks where the visa id equals to the profile id in the user table
            ->setParameter('profileId', $profileId) //and finally here it is specifying that the the profileId is the $profileid passed into the parametter in the controller
            ->getQuery()
            ->getResult();
    }
    public function findAllTasks()
    {
        return $this-> createQueryBuilder('task')
        ->leftJoin('task.visaType', 'visa')
        ->addSelect('visa')
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
