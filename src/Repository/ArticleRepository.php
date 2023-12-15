<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    public function getRecentArticles(int $count, ?string $search = null): \Doctrine\ORM\Query
    {
        $query = $this->createQueryBuilder('q')
            ->orderBy('q.createdAt', 'desc')
            ->setMaxResults($count);


        if ($search)
        {
            $query->andWhere('q.title like :search or q.body like :search')
            ->setParameter('search', '%'. $search .'%');
        }
    
        return $query->getQuery();
    }

}
