<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubjectController extends Controller
{
    /**
     * @Route("/subject/")
     */
    public function indexAction(Request $request,$page = 1, $per_page = 25)
    {
        $form = $this->createForm('App\Form\SubjectForm');
        $form->handleRequest($request); //Получаем данные введенные в форму. 
        $info = $form->getData();
        $page = (isset($info['number'])) ? ($info['number']): false ;
        $stream = imap_open('{imap.mail.ru:993/imap/ssl}INBOX', 'seotest@bk.ru', '1825dec14') or die("Не удалось подключиться: " . imap_last_error());
        $count_emails = imap_num_msg($stream);
        $pageAll = ceil($count_emails/$per_page);
        if($page>$pageAll || !is_numeric($page) || !$page || $page < 1){
            $result = array(
                '0' => 'Введена неккоректная страница'
            );
            $start = $limit = $page =  0;
        }
        else{
        $limit = ($per_page * $page);
        $start = ($limit - $per_page) + 1;
        $start = ($start < 1) ? 1 : $start;
        $numberOfSubject = $limit - $per_page;
        $limit = (($limit - $start) != ($per_page-1)) ? ($start + ($per_page-1)) : $limit;
        $limit = ($count_emails < $limit) ? $count_emails : $limit;
        $msgs = range($start, $limit);
        $headers = (Array)imap_fetch_overview($stream, implode($msgs, ','), 0);
        
        foreach($headers as $key=>$mail){
          $subjects = (Array)imap_mime_header_decode($headers[$key]->subject);
          $subject = $subjects[0]->text;  
          $result[$numberOfSubject] = $subject; 
          $numberOfSubject++; 
        }

    }
        return $this->render('subject/index.html.twig', array(
            'subjects' => $result,
            'start' =>$start,
            'limit' =>$limit,
            'count' =>$count_emails,
            'page' =>$page,
            'pageAll' =>$pageAll,
            'form' => $form->createView()
        ));
    }
}