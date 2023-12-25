<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\AuthorizedCommentType;
use App\Form\UnauthorizedCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article', name: 'article.')]
class ArticleController extends AbstractController
{
    #[Route('/{article}', name: 'show')]
    public function index(Article $article, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = (new Comment())
            ->setArticle($article);
            
        $form = $this->createForm($this->getUser() ? AuthorizedCommentType::class : UnauthorizedCommentType::class, $comment);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $commentRepository->save($comment, true);

            if ($comment->isStatusPublished()) {
                $this->addFlash('success', 'Комментарий опубликован');
            } elseif ($comment->isStatusNew()) {
                $this->addFlash('success', 'Комментарий будет опубликован после проверки модератором');
            }

            return $this->redirectToRoute('article.show', ['article' => $article->getId()]);
        }

        return $this->render('article/index.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
}
