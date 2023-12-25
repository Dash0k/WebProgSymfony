<?php

namespace App\Repository;

use App\Entity\CommentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Self_;

/**
 * @extends ServiceEntityRepository<CommentStatus>
 *
 * @method CommentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentStatus[]    findAll()
 * @method CommentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentStatus::class);
    }

    public function getStatusNew(): CommentStatus
    {
        return $this->findOneBy(['name' => CommentStatus::STATUS_NEW]);
    }

    public function getStatusRejected(): CommentStatus
    {
        return $this->findOneBy(['name' => CommentStatus::STATUS_REJECTED]);
    }

    public function getStatusPublished(): CommentStatus
    {
        return $this->findOneBy(['name' => CommentStatus::STATUS_PUBLISHED]);
    }
}
