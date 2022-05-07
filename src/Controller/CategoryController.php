<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{name}', name: 'show')]
    public function index(Category $category, BoardRepository $boardRepository): Response
    {
        $boards = $boardRepository->findBy(['category' => $category]);

        return $this->render('category/index.html.twig', [
            'boards' => $boards
        ]);
    }
}
