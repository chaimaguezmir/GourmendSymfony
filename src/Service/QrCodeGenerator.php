<?php
 
 namespace App\Service;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;

use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;
use App\Entity\Reservation;


class QrCodeGenerator 
{
 
public function createQrCode( Reservation $reservation): ResultInterface
{
    // Récupérez les informations du reservation
    $id = $reservation->getId();
    $nom = $reservation->getCustomerName();
    $number = $reservation->getNumberPersonnes();
    $status = $reservation->getStatus();
    $datetime = $reservation->getDateTime()->format('Y-m-d');   
    $tableid = $reservation->getTableid();

    $info = "
    $id
    $number
    $nom
    $status
    $datetime
    $tableid
    ";

    // Générez le code QR avec les informations du reservation
    $result = Builder::create()
        ->writer(new SvgWriter())
        ->writerOptions([])
        ->data($info)
        ->encoding(new Encoding('UTF-8'))
        ->size(200)
        ->margin(10)
        ->labelText('Vous trouvez vos informations ici')
        ->labelFont(new NotoSans(20))
        ->validateResult(false)
        ->build();

    return $result;
}}