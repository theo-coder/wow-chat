<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        foreach ($categories as $idx => $category) {
            if ($this->getUser()) {
                if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles()) && !in_array($this->getUser()->getRoles()[0], $category->getAuthorizedRoles())) {
                    unset($categories[$idx]);
                }
            } elseif (!in_array("ROLE_USER", $category->getAuthorizedRoles())) {
                unset($categories[$idx]);
            }
        }

        return $this->render('index/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/a-propos', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }
}
