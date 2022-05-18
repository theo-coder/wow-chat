<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message', name: 'message_')]
class MessageController extends AbstractController
{
    #[Route('/{id}/supprimer', name: 'delete')]
    public function index(Message $message, Request $request, EntityManagerInterface $em): Response
    {
        /*Récupere le token CSRF*/
        $submittedToken = $request->request->get('token');

        /*Si le token est valide*/
        if ($this->isCsrfTokenValid('delete-message', $submittedToken)) {
            /*Supprime le message récupéré dans la BDD*/
            $em->remove($message);
            $em->flush();
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
