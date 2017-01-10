<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Service\YahooWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
    /**
     * @Route(
     *        path = "/ajax/{city}",
     *        name = "BloggerBlogBundle_getWeatherYahoo",
     *        methods ={"GET"},
     *     )
     */
    public function getWeatherYahooAction($city)
    {
        $cityCurl = YahooWeatherService::getCityCurlData($city);
        return $this->render(
            'BloggerBlogBundle:Page:weather-central-block.html.twig',
            array("cityCurl" => $cityCurl)
        );
    }
}