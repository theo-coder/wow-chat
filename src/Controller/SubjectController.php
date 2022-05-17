<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Subject;
use App\Form\MessageType;
use App\Form\SubjectType;
use App\Repository\BoardRepository;
use App\Repository\MessageRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sujet', name: 'subject_')]
class SubjectController extends AbstractController
{
    #[Route('/{categoryName}/{boardName}/{title}', name: 'show')]
    public function index(Subject $subject, string $categoryName, string $boardName, MessageRepository $messageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $message->setSubject($subject);
            $message->setAuthor($this->getUser());
            $message->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")));

            /** @var UploadedFile $files */
            $files = $messageForm->get('files')->getData();
            $files_url = [];

            foreach ($files as $file) {
                $filename = bin2hex(random_bytes(10));
                $extension = $file->guessExtension() ?? 'bin';
                $file->move('files', $filename . '.' . $extension);
                $files_url[] = $request->server->get('HTTP_ORIGIN') . "/files/" . $filename . '.' . $extension;
            }

            $message->setFiles($files_url);

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('subject_show', ['categoryName' => $categoryName, 'boardName' => $boardName, 'title' => $subject->getTitle()]);
        }

        $messages = $messageRepository->findBy(['subject' => $subject]);

        return $this->render('subject/index.html.twig', [
            'form' => $messageForm->createView(),
            'messages' => $messages,
            'categoryName' => $categoryName,
            'boardName' => $boardName,
            'subject' => $subject,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete')]
    public function delete(Subject $subject, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-subject', $submittedToken)) {
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('subject_show', ['categoryName' => $subject->getBoard()->getCategory()->getName(), 'boardName' => $subject->getBoard()->getName(), 'title' => $subject->getTitle()]);
    }

    #[Route('/nouveau/sujet', name: 'add')]
    public function add(BoardRepository $boardRepository, Request $request, EntityManagerInterface $em): Response
    {
        $boardName = $_GET['boardName'] ?? "";

        $subject = new Subject();

        $subject->setBoard($boardRepository->findBy(['name' => $boardName])[0]);

        $subjectForm = $this->createForm(SubjectType::class, $subject);
        $subjectForm->handleRequest($request);

        if ($subjectForm->isSubmitted() && $subjectForm->isValid()) {
            $subject->setAuthor($this->getUser());
            $subject->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));

            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('subject_show', ['title' => $subject->getTitle(), 'categoryName' => $subject->getBoard()->getCategory()->getName(), 'boardName' => $subject->getBoard()->getName()]);
        }

        return $this->render('subject/add.html.twig', [
            'form' => $subjectForm->createView()
        ]);
    }

    #[Route('/{id}/update', name: 'update')]
    public function update(Subject $subject, Request $request, EntityManagerInterface $em): Response
    {
        $subjectForm = $this->createForm(SubjectType::class, $subject)->remove('board');
        $subjectForm->handleRequest($request);

        if ($subjectForm->isSubmitted() && $subjectForm->isValid()) {
            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('subject_show', ['categoryName' => $subject->getBoard()->getCategory()->getName(), 'boardName' => $subject->getBoard()->getName(), 'title' => $subject->getTitle()]);
        }

        return $this->render('subject/edit.html.twig', [
            'title' => $subject->getTitle(),
            'form' => $subjectForm->createView()
        ]);
    }
}
