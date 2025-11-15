<?php

namespace App\Repository;

use App\Entity\Post;
use App\Enum\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPosts()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->join('p.user', 'u')
            ->orderBy('p.publicationDate', 'DESC') 
            ->getQuery()
            ->getResult();
    }

    public function findAllPostsWithCommentCount()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->addSelect('COUNT(c.id) AS commentsCount')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function searchPosts(string $keyword) :array{
        return $this->createQueryBuilder('p')
        ->where('p.title LIKE :keyword OR p.content LIKE :keyword')
        ->setParameter('keyword', '%' . $keyword.'%')
        ->orderBy('p.publicationDate', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
   
    

    // public function findAllWithQuery()
    // {
    //     $qb = $this->createQueryBuilder('name');
    //     $qb
    //         ->addSelect('author')
    //         ->leftJoin('post.author', 'author');
    // }

    //    /**
    //     * @return Post[] Returns an array of Post objects
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

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
