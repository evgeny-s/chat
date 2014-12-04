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
     * @Route("/messages", name="_cabinet_chat")
     * @Template("CabinetChatBundle:Messages:messages.html.twig")
     */
    public function messagesAction()
    {
        return array('');
    }

    /**
     * @Route("/", name="_cabinet_index")
     * @Template("CabinetChatBundle:Common:index.html.twig")
     */
    public function indexAction()
    {
        return array('');
    }
}
