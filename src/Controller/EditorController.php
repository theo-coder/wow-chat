<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Subject;
use App\Form\AdminAddBoardType;
use App\Form\AdminAddCategoryType;
use App\Form\SubjectType;
use App\Repository\CategoryRepository;
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

    #[Route('/category/{id}/board/add/new', name: 'board_add')]
    public function boardAdd(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, $id): Response
    {
        $board = new Board();

        $board->setCategory($categoryRepository->find($id));

        $boardForm = $this->createForm(AdminAddBoardType::class, $board);
        $boardForm->handleRequest($request);

        if ($boardForm->isSubmitted() && $boardForm->isValid()) {
            $em->persist($board);
            $em->flush();

            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('editor/board/add.html.twig', [
            'form' => $boardForm->createView()
        ]);
    }

    #[Route('/category/{id}/subject/edit', name: 'subject_edit')]
    public function editSubject(Subject $subject, Request $request, EntityManagerInterface $em): Response
    {
        $subjectForm = $this->createForm(SubjectType::class, $subject);
        $subjectForm->handleRequest($request);

        if ($subjectForm->isSubmitted() && $subjectForm->isValid()) {
            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('admin_contenu_update', ['id' => $subject->getId()]);
        }

        return $this->render('editor/subject/edit.html.twig', [
            'title' => $subject->getTitle(),
            'form' => $subjectForm->createView()
        ]);
    }
}
