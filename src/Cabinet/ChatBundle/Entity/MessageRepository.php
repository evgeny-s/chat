<?php

namespace Cabinet\ChatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cabinet\ChatBundle\Entity\User;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
    public function getBySenderAndRecipient(User $sender, User $recipient, $last_id = null, $get_all = null) {
        $today = null;
        if (! $get_all) {
            $today = date('Y') . '-' . date('m') . '-' . date('d') .' 00:00:00';
        }

        return $this->getEntityManager()
            ->createQuery(
                "SELECT m FROM CabinetChatBundle:Message m
                  JOIN m.Sender s
                    JOIN m.Recipient r
                      WHERE ((s.id = :id_sender
                        AND r.id = :id_recipient) OR
                            (r.id = :id_sender
                            AND s.id = :id_recipient))
                        "
                            . ($last_id ? "AND m.id > $last_id" : "")
                            . ($get_all ? "" : "AND m.createdAt > '$today'") .
                " ORDER BY m.createdAt ASC"
            )->setParameters(array('id_sender' => $sender->getId(), 'id_recipient' => $recipient->getId()))->getResult();
    }

    public function getListSenders($user) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM CabinetChatBundle:User u
                  WHERE u.id <> :user_id"
            )->setParameter("user_id", $user->getId())->getResult();
    }

    public function makeReadBySenderAndRecipient(User $sender, User $recipient) {
        return $this->getEntityManager()
            ->createQuery("
                UPDATE CabinetChatBundle:Message m SET m.isRead = 1 WHERE m.Sender = :id_sender AND m.Recipient = :id_recipient AND m.isRead = 0
            ")->setParameters(array('id_sender' => $sender->getId(), 'id_recipient' => $recipient->getId()))
            ->execute();
    }

    public function getUnreadMessages(User $user, $sender) {
        $result = $this->getEntityManager()
            ->createQuery("
                SELECT COUNT(m) as m_count FROM CabinetChatBundle:Message m
                  JOIN m.Recipient r
                    JOIN m.Sender s
                      WHERE r.id = :id_user "
                         . ($sender ? "AND s.id = {$sender->getId()}" : "") .
                            " AND m.isRead = 0
            ")
            ->setParameter('id_user', $user->getId())
            ->getArrayResult();

        return $result[0]['m_count'];
    }

    public function getReadMessagesByList(User $user, $aList) {
        $result = $this->getEntityManager()
            ->createQuery("SELECT m.id FROM CabinetChatBundle:Message m
                            JOIN m.Sender s
                              WHERE s.id = :id_sender AND m.isRead = 1 AND m.id IN (:list)
                        ")
            ->setParameters(array('list' => $aList, 'id_sender' => $user->getId()))
            ->getArrayResult();

        return array_map(function($el) {
            return $el['id'];
        }, $result);
    }
}
