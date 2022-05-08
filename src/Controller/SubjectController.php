<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use DateTimeImmutable;
use DateTimeZone;
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

    #[Route('/nouveau/sujet', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $subject = new Subject();
        $subjectForm = $this->createForm(SubjectType::class, $subject);
        $subjectForm->handleRequest($request);

        if ($subjectForm->isSubmitted() && $subjectForm->isValid()) {
            $subject->setAuthor($this->getUser());
            $subject->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));

            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('subject_show', ['title' => $subject->getTitle()]);
        }

        return $this->render('subject/add.html.twig', [
            'form' => $subjectForm->createView()
        ]);
    }
}
