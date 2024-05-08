<?php

// src/Controller/SmsController.php

namespace App\Controller;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    //La vue du formulaire d'envoie du sms
    #[Route('/envoyersms', name: 'app_sms1')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig', ['smsSent' => false]);
    }

    //Gestion de l'envoie du sms
    #[Route('/sms/send', name: 'send_sms_app_123', methods: ['POST'])]
    public function sendSms(Request $request, SmsGenerator $smsGenerator): Response
    {
        $number = $request->request->get('number');

        $name = 'Notreatment';
        $text = 'Message de confirmation';

        $number_test = $_ENV['twilio_to_number'];


        $smsGenerator->sendSms($number_test, $name, $text);

        return $this->render('sms/index.html.twig', ['smsSent' => true]);
    }
}
