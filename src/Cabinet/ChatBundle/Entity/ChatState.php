<?php

namespace Cabinet\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatState
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ChatState
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_sender", referencedColumnName="id")
     **/
    private $Sender;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_recipient", referencedColumnName="id")
     **/
    private $Recipient;

    /**
     * @var string
     *
     * @ORM\Column(name="typing", type="string", length=255, nullable=true)
     */
    private $typing;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typing
     *
     * @param string $typing
     * @return ChatState
     */
    public function setTyping($typing)
    {
        $this->typing = $typing;

        return $this;
    }

    /**
     * Get typing
     *
     * @return string 
     */
    public function getTyping()
    {
        return $this->typing;
    }

    /**
     * Set Sender
     *
     * @param \Cabinet\ChatBundle\Entity\User $sender
     * @return ChatState
     */
    public function setSender(\Cabinet\ChatBundle\Entity\User $sender = null)
    {
        $this->Sender = $sender;

        return $this;
    }

    /**
     * Get Sender
     *
     * @return \Cabinet\ChatBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->Sender;
    }

    /**
     * Set Recipient
     *
     * @param \Cabinet\ChatBundle\Entity\User $recipient
     * @return ChatState
     */
    public function setRecipient(\Cabinet\ChatBundle\Entity\User $recipient = null)
    {
        $this->Recipient = $recipient;

        return $this;
    }

    /**
     * Get Recipient
     *
     * @return \Cabinet\ChatBundle\Entity\User 
     */
    public function getRecipient()
    {
        return $this->Recipient;
    }
}
