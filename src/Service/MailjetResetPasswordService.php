<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetResetPasswordService
{
    private $apiKey ='402bc36dc5c7453753538d3dc1fa6afb';
    private $apiSecret ='1abb28a768835f0ac9a1bbffff669354';
    public function sendMail($toEmail,$toName,$subject,$token):void
    {
        $mj= new Client($this->apiKey,$this->apiSecret,true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "benslimen.louay@esprit.tn",
                        'Name' => "no-replyMagic mind"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName,
                        ]
                    ],
                    'TemplateID' => 5872759,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [

                        "content" => "<h1>Hi!</h1>

<p>To reset your password, please visit the following link</p>

<a href='http://127.0.0.1:8000/reset-password/reset/".$token."'>RESET</a>

<p>This link will expire in .</p>

<p>Cheers!</p>",


                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //  $response->success() ;
    }
}