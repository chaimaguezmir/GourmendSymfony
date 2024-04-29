<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test'),IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('ClientSide/base.html.twig', [
            'name' => 'chaima',
        ]);
    } #[Route('/home', name: 'app_test1')]
    public function index1(): Response
    {
        return $this->render('ClientSide/base.html.twig', [
            'name' => 'chaima',
        ]);
    }
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
       return $this->render('ClientSide/base.html.twig', [
            'name' => 'chaima',
        ]);
        // ...
    }
}
