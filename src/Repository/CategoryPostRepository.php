<?php

namespace App\Repository;

use App\Entity\CategoryPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryPost>
 *
 * @method CategoryPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryPost[]    findAll()
 * @method CategoryPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryPost::class);
    }

    public function findRandomCategoryPost(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM category_post 
            WHERE true
            ORDER BY RAND()
            LIMIT 1
        ';

        $resultSet = $conn->executeQuery($sql)->fetchAssociative();

        $qb = $this->createQueryBuilder('cp')
            ->where('cp.id = :id')
            ->setParameter('id', $resultSet['id']);
        $query = $qb->getQuery();

        return $query->execute();
    }

//    /**
//     * @return CategoryPost[] Returns an array of CategoryPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryPost
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
