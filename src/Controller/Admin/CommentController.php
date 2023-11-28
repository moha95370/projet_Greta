<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/comment', name: 'admin_comment_')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Comment $comment, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_comment_index');
    }
}
