<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_blog')]
    public function index(PostRepository $postRepository,CategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findLastPosts();
        //dd($posts);

        $oldPosts = $postRepository->findOldPosts();

        $categories = $categoryRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'oldPosts' => $oldPosts,
            'categories' => $categories,
            'h1' => 'Last news'
        ]);
    }

    #[Route('/category/{slug}', name: 'post_category')]
    public function post_category(Category $category): Response
    {
        return $this->render('post/category.html.twig', [
            'posts' => $category->getPosts(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/post/add', name: 'post_add')]
    public function addPost(Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setActive(false);
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{slug}', name: 'post_view')]
    public function post(Post $post,Request $request, ManagerRegistry $doctrine): Response//param converteur
    {
        //Traitement du formulaire pour ajouter un commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Associer le user connecté
            $comment->setUser($this->getUser());
            //Associer  le post connecté
            $comment->setPost($post);
            //Persister le commentaire
            $em = $doctrine->getManager();
            $em->persist($comment);
            $em->flush();
            //Rediriger vers la même page, grâce au slug
            return $this->redirectToRoute('post_view', array('slug' => $post->getSlug()));
        }

        return $this->render('post/view.html.twig', [
            // Passer l'article à la vue
            'post' => $post,
            // Passer le formulaire à la vue
            'form'=> $form,
        ]);
    }


}
