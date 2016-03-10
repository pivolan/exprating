<?php

/**
 * Date: 15.02.16
 * Time: 17:17.
 */

namespace AppBundle\Security;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class InviteVoter extends Voter
{
    const SEND_INVITE = 'SEND_INVITE';
    const ACTIVATE_INVITE = 'ACTIVATE_INVITE';
    const APPROVE_RIGHTS_TO_SEND_INVITES = 'APPROVE_RIGHTS_TO_SEND_INVITES';

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
                self::SEND_INVITE,
                self::ACTIVATE_INVITE,
                self::APPROVE_RIGHTS_TO_SEND_INVITES,
            ]
        )
        ) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Invite) {
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
        /** @var Invite $invite */
        $invite = $subject;
        switch ($attribute) {
            case self::SEND_INVITE:
                return $this->canSendInvite($token);
            case self::ACTIVATE_INVITE:
                return $this->canActivateInvite($invite);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canSendInvite(TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return true;
        }

        return false;
    }

    private function canActivateInvite(Invite $invite)
    {
        if (!$invite->getIsActivated()) {
            return true;
        }

        return false;
    }
}
