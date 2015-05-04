<?php

namespace Cabinet\ChatBundle\Controller;

use Cabinet\ChatBundle\Entity\ChatState;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Cabinet\ChatBundle\Entity\User;
use Cabinet\ChatBundle\Form\MessageType;
use Cabinet\ChatBundle\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/cabinet")
 */
class ChatController extends Controller
{

    /**
     * @Route("/messages", name="_cabinet_chat")
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
     * @Route("/messages/item/{id}", name="_cabinet_chat_item")
     * @Template("CabinetChatBundle:Messages:item.html.twig")
     * @param         $id
     * @param Request $request
     *
     * @return array|null|JsonResponse
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

        $response = $this->processAjax($message, $user, $current_user, $request);
        if ($response) {
            return $response;
        }

        $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');
        $get_all = $request->get('get_all');
        $oMessages = $messages_repo->getBySenderAndRecipient($user, $current_user, null, $get_all);

        return array(
            'messages' => $oMessages,
            'form'     => $form->createView(),
            'id'       => $id,
            'sender'   => $user
        );
    }

    /**
     * @param Message $message
     * @param User    $user
     * @param User    $current_user
     * @param Request $request
     *
     * @return null|JsonResponse
     * @internal param $form
     */
    private function processAjax(Message $message, User $user, User $current_user, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $response = array();

            $send_message = $request->get('send_message');
            $make_message_read = $request->get('make_message_read');
            $last_id = $request->get('last_id');
            $not_read_messages = $request->get('not_read_messages');
            $send_message_data = $request->get('send_message_data');
            $typing = $request->get('typing');
            $status = 1;
            $messages_repo = $this->getDoctrine()->getRepository('CabinetChatBundle:Message');

            if ($send_message) {
                if ($send_message_data) {
                    $message->setIsRead(0);
                    $message->setRecipient($user);
                    $message->setSender($current_user);
                    $message->setText($send_message_data);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($message);
                    $em->flush();
                }
                $response["send_message"] = true;
            }

            $chatState = $this->getDoctrine()->getManager()->getRepository("CabinetChatBundle:ChatState")->findOneBy(array('Sender' => $current_user, 'Recipient' => $user));
            if (! is_object($chatState)) {
                $chatState = new ChatState();
                $chatState->setRecipient($user);
                $chatState->setSender($current_user);

                $this->getDoctrine()->getManager()->persist($chatState);
                $this->getDoctrine()->getManager()->flush();
            }
            if ($typing != $chatState->getTyping()) {
                $chatState->setTyping($typing);
                $this->getDoctrine()->getManager()->flush();
            }
            $reverseChatState = $this->getDoctrine()->getManager()->getRepository("CabinetChatBundle:ChatState")->findOneBy(array('Sender' => $user, 'Recipient' => $current_user));
            if (! is_object($reverseChatState)) {
                $reverseChatState = new ChatState();
                $reverseChatState->setRecipient($user);
                $reverseChatState->setSender($current_user);

                $this->getDoctrine()->getManager()->persist($reverseChatState);
                $this->getDoctrine()->getManager()->flush();
            }
            if ($reverseChatState->getTyping()) {
                $toMeTyping = true;
            } else {
                $toMeTyping = false;
            }

            if ($make_message_read) {
                $messages_repo->makeReadBySenderAndRecipient($user, $current_user);

                $response["make_message_read_count"] = $messages_repo->getUnreadMessages($current_user, null);
                $response["make_message_read"] = true;
            }


            $oMessages = $messages_repo->getBySenderAndRecipient($user, $current_user, $last_id);

            $read_ids = $messages_repo->getReadMessagesByList($current_user, $not_read_messages);

            if (count($oMessages)) {
                $response["messages_html"] = $this->renderView("CabinetChatBundle:Messages:messages_block.html.twig", array("messages" => $oMessages));
                $response["messages_status"] = true;
            } else {
                $response["messages_status"] = false;
            }
            $response["not_read_ids"] = $read_ids;

            $response["status"] = $status;
            $response["to_me_typing"] = $toMeTyping;
            return new JsonResponse($response);
        } else {
            return null;
        }
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
