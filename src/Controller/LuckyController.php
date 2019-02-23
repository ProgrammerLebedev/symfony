<?php

// src/AppBundle/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function number()
    {
        $stream = imap_open('{imap.mail.ru:993/imap/ssl}INBOX', 'seotest@bk.ru', '1825dec14') or die("can't connect: " . imap_last_error());;

        $emails = imap_search($stream,'ALL');
        $n = 0;
        foreach($emails as $mail){
          $headers = (Array)imap_headerinfo($stream, $mail);
          $subjects = (Array)imap_mime_header_decode($headers['subject']);
          $subject = $subjects[0]->text;
          $result[] = $subject;
          $n++;
          if($n>100){
              break;
          }
          
        }
        return $this->render('lucky/number.html.twig', array(
            'subjects' => $result
        ));
    }
}