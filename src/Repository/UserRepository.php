<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllUsers()
    {
        return $this->createQueryBuilder('user')
            ->innerJoin('user.profile', 'visa')
            ->getQuery()
            ->getResult();
    }

    public function findUsersBySearch(string $searchName)
    {
        return $this->createQueryBuilder('user')
            ->where('user.firstName like :term OR user.lastName LIKE :term')
            ->setParameter('term', '%' . $searchName . '%')
            ->getQuery()
            ->getResult();
    }

    public function getTotalNumberOfUsers(): int
    {
        return  $this->createQueryBuilder('user')
            ->select('COUNT(user.id)')
            ->where('user.roles LIKE :role')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function getTotalNumberOfActiveUsers(): int
    {
        return  $this->createQueryBuilder('user')
            ->select('COUNT(user.id)')
            ->where('user.roles LIKE :role AND user.isActive= 1')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery()
            ->getSingleScalarResult();
    }
   
    public function getTotalNumberPerVisaType() :array{
        return $this->createQueryBuilder('user')
        -> select('profile.name AS visaType , COUNT(user.id) AS total ')
        ->join('user.profile','profile')
        ->groupBy('profile.name')
        ->getQuery()
        ->getResult();
    }
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
