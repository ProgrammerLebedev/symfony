<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AllImagesController extends Controller
{
    /**
     * @Route("/allimages/")
     */
    public function indexAction(Request $request,$url = 'https://mail.ru')
    {
        $form = $this->createForm('App\Form\AllImagesForm');
        $form->handleRequest($request); //Получаем данные введенные в форму. 
        $info = $form->getData();
        $url = (isset($info['url'])) ? ($info['url']): 'https://mail.ru' ;
        $script = (isset($info['script'])) ? ($info['script']): '0' ;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        if (!$script){
        $data = preg_replace("#<script(.*?)>(.*?)</script>#is", "", $data);
        }
        preg_match_all('/([-a-z0-9_\/:.]+\.(jpg|jpeg|png|gif))/i', $data, $img);
        $allImages = array_values(array_unique($img[0])); 
        if(!isset($allImages)){
            $allImages = array(
                '0' =>'Картинки не найдены, либо неккоректный url'
            );
        }
       
        return $this->render('allimages/index.html.twig', array(
           'allImages' => $allImages,
           'url' => $url,
           'form' => $form->createView()
        ));
    }
}