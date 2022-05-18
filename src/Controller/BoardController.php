<?php

namespace App\Controller;

use App\Entity\Board;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/board', name: 'board_')]
class BoardController extends AbstractController
{
    #[Route('/{categoryName}/{name}', name: 'show')]
    public function index(Board $board, string $categoryName, SubjectRepository $subjectRepository): Response
    {
        /*Recupere tous les sujets qui sont avec le board recupéré dans la BDD */
        $subjects = $subjectRepository->findBy(['board' => $board]);

        return $this->render('board/index.html.twig', [
            'categoryName' => $categoryName,
            'boardName' => $board->getName(),
            'subjects' => $subjects,
        ]);
    }
}
