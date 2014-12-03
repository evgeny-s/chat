<?php

namespace Cabinet\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/cabinet")
 */

class MessagesController extends Controller
{

    /**
     * @Route("/messages/{name}", name="_cabinet_chat")
     * @Template("ChatBundle:Messages:index.html.twig")
     */
    public function messagesAction($name)
    {
        return $this->render('CabinetChatBundle:Messages:index.html.twig', array('name' => $name));
    }
}
