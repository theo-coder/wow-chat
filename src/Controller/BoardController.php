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
    #[Route('/{name}', name: 'show')]
    public function index(Board $board, SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findBy(['board' => $board]);

        return $this->render('board/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
