<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Service\YahooWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $url = $this->getParameter('finance_ua_url');
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
            array("resultArray" => $resultArray, "curr" => $curr, "resultStringJson" => $resultStringJson)
        );
    }

    /**
     * @Route(
     *        path = "/contact",
     *        name = "BloggerBlogBundle_contact",
     *        methods ={"GET","POST" }
     *     )
     */
    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('enquiries@symblog.co.uk')
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));


                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));

            }

        }

        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *        path = "/weather/{city}",
     *        name = "BloggerBlogBundle_weather",
     *        methods ={"GET"},
     *     defaults = { "city" = "kyiv" }
     *     )
     */
    public function weatherAction($city)
    {
        $cityCurl = YahooWeatherService::getCityCurlData($city);
        return $this->render(
            'BloggerBlogBundle:Page:weather.html.twig',
            array("cityCurl" => $cityCurl)
        );
    }


}