<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AdminAddCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editor', name: 'editor_')]
class EditorController extends AbstractController
{
    #[Route('/category/add/new', name: 'category_add')]
    public function categoryAdd(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();

        $categoryForm = $this->createForm(AdminAddCategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('editor/category/add.html.twig', [
            'form' => $categoryForm->createView()
        ]);
    }
}
