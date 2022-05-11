<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\User;
use App\Form\AdminUpdateUserFormType;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        if ($this->isCsrfTokenValid('delete-user', $submittedToken)) {
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user');
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


    /*************** ADMINISTRATOR ***************/

    #[Route('/administrateur', name: 'administrator')]
    public function admin(UserRepository $userRepository): Response
    {
        $admins = $userRepository->findAdmin();

        return $this->render('admin/administrator/index.html.twig', [
            'admins' => $admins
        ]);
    }


    #[Route('/administrateur/{id}', name: 'administrator_update')]
    public function adminUpdate(User $admin): Response
    {
        return $this->render('admin/administrator/update.html.twig', [
            'admin' => $admin
        ]);
    }
}
