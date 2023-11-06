<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/type', name: 'admin_type_')]
class TypeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository -> findAll();

        return $this->render('admin/type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addType(Request $request, ManagerRegistry $doctrine): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $em = $doctrine->getManager();
            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter un type de lieu installation:'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateType(Type $type, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier le type'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Type $type, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
       $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
       if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($type);
            $em->flush();
            $this->addFlash('success', 'Type supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_type_index');
    }

}
