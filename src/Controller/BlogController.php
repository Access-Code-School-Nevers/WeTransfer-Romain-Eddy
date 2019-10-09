<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use App\Entity\FileTransfer;
use Doctrine\ORM\EntityManagerInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/sendData", name="sendData")
     */
    public function sendData(Request $request, \Swift_Mailer $mailer)
    {
      // Create entity
      $fileTransfer = new FileTransfer();
      $fileTransfer->setMailFrom(filter_var(trim($request->request->get('mail_from')), FILTER_SANITIZE_EMAIL));
      $fileTransfer->setNameFrom($request->request->get('name_from'));
      $fileTransfer->setMailTo(filter_var(trim($request->request->get('mail_to')), FILTER_SANITIZE_EMAIL));
      $fileTransfer->setNameTo($request->request->get('name_to'));


      if($fileTransfer->getMailFrom() != false && $fileTransfer->getMailTo() != false){
        $files = $request->files->get('files');
        $nbElements = count($files);
        $tmpFiles = array();

        // Unique zip name
        $idZip = uniqid('zip_');

        $fileTransfer->setFileName($idZip);

        // Add files to images reporitory and create zip archive
        $zip = new ZipArchive;
        if ($zip->open('zip/'.$idZip.'.zip', ZipArchive::CREATE) === TRUE){
          $i=1;
          foreach($files as $file){
            $idImage = uniqid('img_');
            $name = $idImage.'.'.pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
            $tmpFiles[$i] = $file->move('images', $name);

            $zip->addFile('images/'.$name, $name);

            $i++;
          }
        }
        // All files are added, so close the zip file.
        $zip->close();


        // Create the message
        $message = (new \Swift_Message())
          ->setSubject('Fichiers envoyés par ' . $fileTransfer->getNameFrom())
          ->setFrom([$fileTransfer->getMailFrom()])
          ->setTo([$fileTransfer->getMailTo()])
          ->setBody(
              $this->renderView('email/sendMail.html.twig', [
                  'nomDestinataire' => $fileTransfer->getNameTo(),
                  'nomAuteur' => $fileTransfer->getNameFrom(),
                  'link' => 'zip/'.$fileTransfer->getFileName().'.zip'
              ]),
              'text/html'
          )
          ;

          $mailer->send($message);


          // Insert into DB
          $transferRepo = $this->getDoctrine()->getManager();
          $transferRepo->persist($fileTransfer);
          $transferRepo->flush();

          return new Response(1);
        }
        // Error
        else {
          return new Response(0);
        }
    }
}
