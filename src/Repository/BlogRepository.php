<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }
    public function findSortedByUpvotes(): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.votes', 'v') // Left join votes
            ->select(
                'b', // Select the blog entity
                'SUM(CASE WHEN v.voteType = :upvote THEN 1 ELSE 0 END) AS HIDDEN upvoteCount' // Count only upvotes
            )
            ->setParameter('upvote', 'upvote')
            ->addGroupBy('b.id') // Group by blog ID
            ->orderBy('upvoteCount', 'DESC') // Order by upvoteCount in descending order
            ->getQuery()
            ->getResult();
    }
    public function findByKeyword($keyword): array
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.title LIKE :keyword OR b.details LIKE :keyword')
        ->setParameter('keyword', '%'.$keyword.'%')
        ->getQuery()
        ->getResult();
}
    
    

    

    
//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blog
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
