<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catégorie', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{name}', name: 'show')]
    public function index(Category $category, BoardRepository $boardRepository): Response
    {
        /*Recupere tous les board qui sont avec la categorie recupéré dans la BDD */
        $boards = $boardRepository->findBy(['category' => $category]);

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'boards' => $boards
        ]);
    }
}
