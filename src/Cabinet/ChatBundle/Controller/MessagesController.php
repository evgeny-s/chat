<?php

namespace Cabinet\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Cabinet\ChatBundle\Entity\User;
use Cabinet\ChatBundle\Form\MessageType;
use Cabinet\ChatBundle\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $current_user = $this->getUser();
        $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');
        $users = $messages_repo->getListSenders($current_user);

        return array(
            'users' => $users
        );
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
        if ($request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message->setIsRead(0);
                $message->setRecipient($user);
                $message->setSender($current_user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                $response = new JsonResponse();
                $response->setData(array(
                    'status' => 1
                ));
                return $response;
            }
        }


        $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');
        $oMessages = $messages_repo->getBySenderAndRecipient($user, $current_user);

        return array(
            'messages' => $oMessages,
            'form'     => $form->createView(),
            'id'       => $id
        );
    }

    /**
     * @Route("/messages/item/get_last/{id}", name="_cabinet_messages_get_last")
     */
    public function getLastAction($id, Request $request)
    {
        $user_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:User');
        $user = $user_repo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User does not exist!');
        }
        $current_user = $this->getUser();
        $last_id = $request->get('last_id');

        $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');
        $oMessages = $messages_repo->getBySenderAndRecipient($user, $current_user, $last_id);

        if (count($oMessages)) {
            $result = array(
                "status" => true,
                "html" => $this->renderView("CabinetChatBundle:Messages:messages_block.html.twig", array("messages" => $oMessages))
            );
        } else {
            $result = array("status" => false);
        }

        return new JsonResponse($result);
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
