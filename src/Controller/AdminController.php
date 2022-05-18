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
        /*Récupère tous les utilisateurs enregistré dans la bdd*/ 
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/utilisateur/{id}', name: 'user_update')]
    public function userUpdate(User $user, Request $request, EntityManagerInterface $em): Response
    {
        /*Créer un formulaire en passant les données de l'utilisateur récupéré*/
        $userForm = $this->createForm(AdminUpdateUserFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /*Enregistre les modifications de la BDD */
            $em->persist($user);
            $em->flush();

            /*Redirige vers la page de l'utilisateur modifié*/
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
        /*Récupere le token CSRF*/
        $submittedToken = $request->request->get('token');

        /*Si le token est valide*/
        if ($this->isCsrfTokenValid('delete-user', $submittedToken) && $this->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
            /*Supprime l'utilisateur récupéré dans la BDD*/
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user');
    }


    /*************** CATEGORY ***************/

    #[Route('/categorie', name: 'categorie')]
    public function category(CategoryRepository $categoryRepository): Response
    {
        /*Recupere les catégories presentes dans la BDD */
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categorie/{id}/supprimer', name: 'categorie_delete')]
    public function categoryDelete(Category $category, Request $request, EntityManagerInterface $em): RedirectResponse
    {
         /*Récupere le token CSRF*/
        $submittedToken = $request->request->get('token');

        /*Si le token est valide*/
        if ($this->isCsrfTokenValid('delete-category', $submittedToken)) {
            /*Supprime la catégorie récupéré dans la BDD*/
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
        /*Recupere les boards dans la BDD */
        $boards = $boardRepository->findAll();

        return $this->render('admin/board/index.html.twig', [
            'boards' => $boards
        ]);
    }

    #[Route('/board/ajouter/nouveau', name: 'board_add')]
    public function boardAdd(Request $request, EntityManagerInterface $em): Response
    {
        /*Créé un board vierge*/
        $board = new Board();

        /*Créé un formulaire de board en passant le board vierge en paramètre */
        $boardForm = $this->createForm(AdminAddBoardType::class, $board);
        $boardForm->handleRequest($request);

        if ($boardForm->isSubmitted() && $boardForm->isValid()) {
            /*Enregistre le nouveau board dans la BDD */
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
        /*Récupere le token CSRF*/
        $submittedToken = $request->request->get('token');

        /*Si le token est valide*/
        if ($this->isCsrfTokenValid('delete-board', $submittedToken)) {
            /*Supprime la catégorie récupéré dans la BDD*/
            $em->remove($board);
            $em->flush();
        }

        return $this->redirectToRoute('admin_board');
    }


    /*************** SUBJECT ***************/

    #[Route('/contenu', name: 'contenu')]
    public function contenu(SubjectRepository $subjectRepository): Response
    {
        /*Recupere les sujets de la BDD */
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
        /*Récupere le token CSRF*/
        $submittedToken = $request->request->get('token');
        /*Si le token est valide*/
        if ($this->isCsrfTokenValid('delete-subject', $submittedToken)) {
            /*Supprime lesujet récupéré dans la BDD*/
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('admin_contenu');
    }
}
