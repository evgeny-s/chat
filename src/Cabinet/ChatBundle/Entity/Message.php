<?php

namespace Cabinet\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Cabinet\ChatBundle\Entity\MessageRepository")
 */
class Message
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
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set idSender
     *
     * @param integer $idSender
     * @return Message
     */
    public function setIdSender($idSender)
    {
        $this->idSender = $idSender;

        return $this;
    }

    /**
     * Get idSender
     *
     * @return integer 
     */
    public function getIdSender()
    {
        return $this->idSender;
    }

    /**
     * Set idRecipient
     *
     * @param integer $idRecipient
     * @return Message
     */
    public function setIdRecipient($idRecipient)
    {
        $this->idRecipient = $idRecipient;

        return $this;
    }

    /**
     * Get idRecipient
     *
     * @return integer 
     */
    public function getIdRecipient()
    {
        return $this->idRecipient;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Message
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Message
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set Sender
     *
     * @param \Cabinet\ChatBundle\Entity\User $sender
     * @return Message
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
     * @return Message
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

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
}
