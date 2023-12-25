<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\Events;
use App\Repository\CommentStatusRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Comment::class)]
class CommentEntityListener
{
    public function __construct(
        private readonly Security $security,
        private readonly CommentStatusRepository $commentStatusRepository)
    {
        
    }
    public function prePersist(Comment $comment, PrePersistEventArgs $event): void
    {
        $comment->setCreatedAt(new \DateTimeImmutable());
        
        if ($this->security->getUser()) {
            # пользователь авторизован
            /** @var User $user */
            $user = $this->security->getUser();
            $comment->setAuthor($user)
                ->setEmail($user->getEmail())
                ->setStatus($this->commentStatusRepository->getStatusPublished());
        } else {
            # пользователь не авторизован
            $comment->setStatus($this->commentStatusRepository->getStatusNew());
        }
    }
}