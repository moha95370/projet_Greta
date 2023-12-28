<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format catégory
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin_category_index');
        }
        
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter une catégorie'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateCategory(Category $category, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/add.html.twig', [
            'h1' => 'Modifier une catégorie'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
       $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
       if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_category_index');
    }

}
