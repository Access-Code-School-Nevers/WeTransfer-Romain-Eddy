<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use App\Entity\FileTransfer;
use Doctrine\ORM\EntityManagerInterface;

class TransferController extends AbstractController
{
  /**
   * @Route("/", name="homeNL")
   */

  public function homeNL(Request $request)
  {
    return $this->redirect('/'.$request->getLocale(), 301);
  }

    /**
     * @Route("/{_locale}", name="home",  requirements={ "_locale" = "en|fr|es|it" })
     */

    public function index(Request $request)
    {
      include('language/'.$request->getLocale().'/home.php');

      return $this->render('site/index.html.twig', [
          'controller_name' => 'TransferController',
          'tabLang' => $tabLang
      ]);
    }


    /**
     * @Route("/conditionsUtilisation", name="cgu")
     */

    public function cgu()
    {
        return $this->render('site/useConditions.html.twig', [
            'controller_name' => 'TransferController',
            'page_title' => 'Conditions d\'utilisation'
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


        // Delete temporary file
        foreach($tmpFiles as $tmpFile){
          unlink($tmpFile);
        }


        // Create the message
        $message = (new \Swift_Message())
          ->setSubject('EasyTransfer - Fichiers envoyés par ' . $fileTransfer->getNameFrom())
          ->setFrom([$fileTransfer->getMailFrom()])
          ->setTo([$fileTransfer->getMailTo()]);

          $cid = $message->embed(\Swift_Image::fromPath('img/logo.png'));
          $message->setBody(
            $this->renderView('email/sendMail.html.twig', [
                'nomDestinataire' => $fileTransfer->getNameTo(),
                'nomAuteur' => $fileTransfer->getNameFrom(),
                'link' => 'zip/'.$fileTransfer->getFileName().'.zip',
                'imgLogo' => $cid
            ]),
            'text/html'
          );

          $mailer->send($message);


          // Insert into DB
          $transferRepo = $this->getDoctrine()->getManager();
          $transferRepo->persist($fileTransfer);
          $transferRepo->flush();

          return $this->json(['response' => '1', 'link' => '/zip/'.$idZip.'.zip']);
        }
        // Error
        else {
          return $this->json(['response' => '0']);
        }
    }
}
