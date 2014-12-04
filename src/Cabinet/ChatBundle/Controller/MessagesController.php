<?php

namespace Cabinet\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Lsw\SecureControllerBundle\Annotation\Secure;
use Cabinet\ChatBundle\Entity\User;
use Cabinet\ChatBundle\Form\MessageType;
use Cabinet\ChatBundle\Entity\Message;

/**
 * @Route("/cabinet")
 */

class MessagesController extends Controller
{

    /**
     * @Route("/messages", name="_cabinet_messages")
     * @Template("CabinetChatBundle:Messages:messages.html.twig")
     */
    public function messagesAction()
    {
        return array('');
    }

    /**
     * @Route("/messages/item/{id}", name="_cabinet_messages_item")
     * @Template("CabinetChatBundle:Messages:item.html.twig")
     */
    public function itemAction($id, Request $request)
    {
        $user_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:User');
        $user = $user_repo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User does not exist!');
        }
        $current_user = $this->getUser();

        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $message->setIsRead(0);
            $message->setRecipient($user);
            $message->setSender($current_user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl('_cabinet_messages_item', array('id' => $id)));
        }

        $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');
        $oMessages = $messages_repo->getBySenderAndRecipient($user, $current_user);

        return array(
            'messages' => $oMessages,
            'form'     =>$form->createView()
        );
    }

    /**
     * @Route("/", name="_cabinet_index")
     * @Template("CabinetChatBundle:Common:index.html.twig")
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        return array('');
    }
}
