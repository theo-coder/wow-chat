<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sujet', name: 'subject_')]
class SubjectController extends AbstractController
{
    #[Route('/{title}', name: 'show')]
    public function index(Subject $subject): Response
    {
        return $this->render('subject/index.html.twig', [
            'subject' => $subject,
        ]);
    }

    #[Route('/{title}/supprimer', name: 'delete')]
    public function delete(Subject $subject, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-subject', $submittedToken)) {
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('app_index');
    }
}
