<?php

namespace Cabinet\ChatBundle\Controller;

use Cabinet\ChatBundle\Form\ProfileType;
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

class UserController extends Controller
{

    /**
     * @Route("/profile", name="_cabinet_profile")
     * @Template("CabinetChatBundle:User:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $request = $this->get('request');
        $form = $this->createForm(new ProfileType(), $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user->uploadImage();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->set('type', 'Saved successfully!');

                return $this->redirect($this->generateUrl('_cabinet_profile'));
            }
        }

        return array('form' => $form->createView());
    }
}
