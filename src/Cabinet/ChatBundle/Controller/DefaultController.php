<?php

namespace Cabinet\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CabinetChatBundle:Default:index.html.twig', array('name' => $name));
    }
}
