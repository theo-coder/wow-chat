<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\AdminAddBoardType;
use App\Form\AdminAddCategoryType;
use App\Form\AdminUpdateUserFormType;
use App\Repository\BoardRepository;
use App\Repository\CategoryRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /*************** USER ***************/

    #[Route('/utilisateur', name: 'user')]
    public function user(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/utilisateur/{id}', name: 'user_update')]
    public function userUpdate(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $userForm = $this->createForm(AdminUpdateUserFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_user_update', ['id' => $user->getId()]);
        }

        return $this->render('admin/user/update.html.twig', [
            'user' => $user,
            'form' => $userForm->createView()
        ]);
    }

    #[Route('/utilisateur/{id}/supprimer', name: 'user_delete')]
    public function userDelete(User $user, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-user', $submittedToken) && $this->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user');
    }


    /*************** CATEGORY ***************/

    #[Route('/categorie', name: 'categorie')]
    public function category(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categorie/ajouter/nouvelle', name: 'categorie_add')]
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

        return $this->render('admin/category/add.html.twig', [
            'form' => $categoryForm->createView()
        ]);
    }

    #[Route('/categorie/{id}/supprimer', name: 'categorie_delete')]
    public function categoryDelete(Category $category, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-category', $submittedToken)) {
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin_categorie');
    }

    #[Route('/categorie/{id}/modifier', name: 'categorie_update')]
    public function categorieUpdate(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('admin/category/update.html.twig', []);
    }


    /*************** BOARD ***************/

    #[Route('/board', name: 'board')]
    public function board(BoardRepository $boardRepository): Response
    {
        $boards = $boardRepository->findAll();

        return $this->render('admin/board/index.html.twig', [
            'boards' => $boards
        ]);
    }

    #[Route('/board/ajouter/nouveau', name: 'board_add')]
    public function boardAdd(Request $request, EntityManagerInterface $em): Response
    {
        $board = new Board();

        $boardForm = $this->createForm(AdminAddBoardType::class, $board);
        $boardForm->handleRequest($request);

        if ($boardForm->isSubmitted() && $boardForm->isValid()) {
            $em->persist($boardForm);
            $em->flush();
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $boardForm->createView()
        ]);
    }

    #[Route('/board/{id}/supprimer', name: 'board_delete')]
    public function boardDelete(Board $board, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-board', $submittedToken)) {
            $em->remove($board);
            $em->flush();
        }

        return $this->redirectToRoute('admin_board');
    }


    /*************** SUBJECT ***************/

    #[Route('/contenu', name: 'contenu')]
    public function contenu(SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findAll();

        return $this->render('admin/subject/index.html.twig', [
            'subjects' => $subjects
        ]);
    }


    #[Route('/contenu/{id}', name: 'contenu_update')]
    public function contenuUpdate(Subject $subject): Response
    {
        return $this->render('admin/subject/update.html.twig', [
            'subject' => $subject
        ]);
    }

    #[Route('/contenu/{id}/supprimer', name: 'contenu_delete')]
    public function contenuDelete(Subject $subject, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-subject', $submittedToken)) {
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('admin_contenu');
    }
}
