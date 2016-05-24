<?php

/**
 * Date: 15.02.16
 * Time: 17:17.
 */

namespace AppBundle\Security;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class NotificationVoter extends Voter
{
    const DELETE = 'DELETE';

    /** @var  AccessDecisionManagerInterface */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array(
            $attribute,
            [
                self::DELETE,
            ]
        )
        ) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Notification) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Notification $notification */
        $notification = $subject;
        switch ($attribute) {
            case self::DELETE:
                return $this->canDeleteNotification($notification, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDeleteNotification(Notification $notification, TokenInterface $token)
    {
        return ($notification->getUser() == $token->getUser());
    }
}
