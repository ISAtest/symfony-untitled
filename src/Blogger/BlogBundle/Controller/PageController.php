<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{

    /**
     * @Route("/", name="BloggerBlogBundle_homepage")
     */
    public function indexAction()
    {
        return $this->render('BloggerBlogBundle:Page:index.html.twig');
    }

    /**

     * @Route(
     *       path     = "/about/{slug}",
     *       name     = "BloggerBlogBundle_about",
     *       defaults = { "slug" = "hello" },
     *       requirements = { "slug" = "[a-z0-9]+" },
     *     methods      = { "GET", "POST" },
     *       schemes      = { "https" }
     *     )
     */
    public function aboutAction($slug)
    {
   var_dump($slug);
        exit;
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }


    /**
     * @Route(
     *        path = "/currency/{curr}",
     *        name = "BloggerBlogBundle_currency",
     *      requirements = { "curr" = "(?i:EUR|USD|RUB)" },
     *        defaults = { "curr" = "EUR" }
     *     )
     */
    public function currencyAction($curr)
    {
        $url =  $this->getParameter('finance_ua_url');
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);

        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        $resultStringJson = curl_exec($cURL);
        curl_close($cURL);
        $resultArray = json_decode($resultStringJson, true);
        return $this->render(
            'BloggerBlogBundle:Page:curr.html.twig',
            array("resultArray"=>$resultArray, "curr"=>$curr, "resultStringJson"=>$resultStringJson)
        );
    }

}