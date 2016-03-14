<?php

/**
 * Date: 15.02.16
 * Time: 17:17.
 */

namespace AppBundle\Security;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const EXPERT_APPROVE_RIGHTS = 'EXPERT_APPROVE_RIGHTS';
    const INVITE_COMPLETE_REGISTRATION = 'INVITE_COMPLETE_REGISTRATION';
    const DETAIL_VIEW = 'DETAIL_VIEW';
    const EDIT = 'EDIT';
    const ADD_ROLE_CURATOR = 'ADD_ROLE_CURATOR';

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
        if ($attribute == self::INVITE_COMPLETE_REGISTRATION) {
            return true;
        }

        // if the attribute isn't one we support, return false
        if (!in_array(
            $attribute,
            [
                self::EXPERT_APPROVE_RIGHTS,
                self::DETAIL_VIEW,
                self::ADD_ROLE_CURATOR,
                self::EDIT,
            ]
        )
        ) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof User) {
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
        // you know $subject is a Post object, thanks to supports
        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            case self::EXPERT_APPROVE_RIGHTS:
                return $this->canApprove($user, $token);
            case self::INVITE_COMPLETE_REGISTRATION:
                return $this->canComplete($token);
            case self::DETAIL_VIEW:
                return $this->canDetailView($user, $token);
            case self::ADD_ROLE_CURATOR:
                return $this->canAddRoleCurator($user, $token);
            case self::EDIT:
                return $this->canEdit($user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canApprove(User $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return false;
        }

        if ($token->getUser() == $user->getCurator()) {
            return true;
        }

        return false;
    }

    private function canComplete(TokenInterface $token)
    {
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT])) {
            return false;
        }
        $user = $token->getUser();

        return !$user->getIsActivated();
    }

    /**
     * @param $user
     * @param $token
     *
     * @return bool
     */
    private function canDetailView(User $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        if ($user == $token->getUser()) {
            return true;
        }

        if (!$user->getCurator()) {
            return false;
        }

        if ($user->getCurator() == $token->getUser()) {
            return true;
        }

        if ($user->getCurator()->getCurator() == $token->getUser()) {
            return true;
        }

        return false;
    }

    private function canAddRoleCurator(User $user, TokenInterface $token)
    {
        if ($user->hasRole(User::ROLE_EXPERT_CURATOR)) {
            return false;
        }
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return false;
        }
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        if ($user->getCurator() == $token->getUser()) {
            return true;
        }

        if ($user->getCurator()->getCurator() == $token->getUser()) {
            return true;
        }

        return false;
    }

    private function canEdit(User $user, TokenInterface $token)
    {
        if ($user == $token->getUser()) {
            return true;
        }

        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        return false;
    }
}
