<?php

namespace Cabinet\ChatBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CabinetExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct($em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'get_unread_messages' => new \Twig_Function_Method($this, 'getUnreadMessages'),
            'user_pic_path' => new \Twig_Function_Method($this, 'getUserPicPath')
        );
    }

    /* get count unread messages */
    public function getUnreadMessages($user, $sender = null)
    {
        $repo = $this->em->getRepository('CabinetChatBundle:Message');
        $count = $repo->getUnreadMessages($user, $sender);

        return $count;
    }

    public function getName()
    {
        return 'cabinet_extension';
    }

    public function getUserPicPath($user)
    {
        if ($user->getImage()) {
            return $user->getRelativePath();
        } else {
            return $this->container->get('templating.helper.assets')->getUrl("assets/cabinet/images/photos/anonym.png");
        }
    }
}