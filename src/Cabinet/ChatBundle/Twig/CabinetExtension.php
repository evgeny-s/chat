<?php

namespace Cabinet\ChatBundle\Twig;

use Doctrine\ORM\EntityManager;

class CabinetExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'get_unread_messages' => new \Twig_Function_Method($this, 'getUnreadMessages')
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
}