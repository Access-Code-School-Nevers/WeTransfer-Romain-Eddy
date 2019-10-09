<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

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
      // retrieves $_GET and $_POST variables respectively
      $mailFrom = filter_var(trim($request->request->get('mail_from')), FILTER_SANITIZE_EMAIL);
      $mailTo = filter_var(trim($request->request->get('mail_to')), FILTER_SANITIZE_EMAIL);
      $files = $request->files->get('files');
      $nbElements = count($files);
      $tmpFiles = array();

      // Unique zip name
      $idZip = uniqid('zip_');

      // // Add files to images reporitory and create zip archive
      // $zip = new ZipArchive;
      // if ($zip->open('zip/'.$idZip.'.zip', ZipArchive::CREATE) === TRUE){
      //   $i=1;
      //   foreach($files as $file){
      //     $idImage = uniqid('img_');
      //     $name = $idImage.'.'.pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
      //     $tmpFiles[$i] = $file->move('images', $name);
      //
      //     $zip->addFile('images/'.$name, $name);
      //
      //     $i++;
      //   }
      // }
      // // All files are added, so close the zip file.
      // $zip->close();

      // Create the message
      $message = (new \Swift_Message())
        // Give the message a subject
        ->setSubject('Your subject')
        // Set the From address with an associative array
        ->setFrom([$mailFrom])
        // Set the To addresses with an associative array (setTo/setCc/setBcc)
        ->setTo([$mailTo])
        // Give it a body
        ->setBody('Here is the message itself')
        // And optionally an alternative body
        ->addPart('<q>Here is the message itself</q>', 'text/html')
        // Optionally add any attachments
        // ->attach(\Swift_Attachment::fromPath($files))
        ;

        $mailer->send($message);

        return new Response(
            '<html><body>mail From : '.$mailFrom.'<br>mail to : '.$mailTo.'<br></body></html>'
        );
    }
}
