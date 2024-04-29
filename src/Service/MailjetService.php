<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetService
{
    private $apiKey ='402bc36dc5c7453753538d3dc1fa6afb';
    private $apiSecret ='1abb28a768835f0ac9a1bbffff669354';
   public function sendMail($toEmail,$toName,$subject,$link,$expiresAtMessageKey):void
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

                       "content" => " <h1>Hi! Please confirm your email!</h1>

<p>
    Please confirm your email address by clicking the following link: <br><br>
    <a href=".$link.">Confirm my Email</a>.
    This link will expire in  ".$expiresAtMessageKey.".
</p>

<p>
    Cheers!
</p>",


                   ]
               ]
           ]
       ];
       $response = $mj->post(Resources::$Email, ['body' => $body]);
     //  $response->success() ;
   }
}