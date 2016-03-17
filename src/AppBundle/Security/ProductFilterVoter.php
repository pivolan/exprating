<?php

/**
 * Date: 15.02.16
 * Time: 17:17.
 */

namespace AppBundle\Security;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductFilterVoter extends Voter
{
    const VIEW_WAIT = 'SHOW_WAIT';
    const VIEW_FREE = 'SHOW_FREE';
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
        if (!in_array($attribute, [self::VIEW_FREE, self::VIEW_WAIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Category) {
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
        /** @var Category $category */
        $category = $subject;

        switch ($attribute) {
            case self::VIEW_FREE:
                return $this->canViewFree($category, $token);
            case self::VIEW_WAIT:
                return $this->canViewWait($category, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canViewFree(Category $category, TokenInterface $token)
    {
        //Админу можно редактировать все
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }
        //Если нет прав эксперта значит точно нельзя
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT])) {
            return false;
        }
        /** @var User $user */
        $user = $token->getUser();

        //Если товар не имеет эксперта, и у эксперта есть права на категорию в которой находится товар
        if ($user->getCategories()->contains($category)) {
            return true;
        }

        return false;
    }

    private function canViewWait(Category $category, TokenInterface $token)
    {
        //Админу можно редактировать все
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }
        //Если нет прав куратора значит точно нельзя
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return false;
        }
        /** @var User $user */
        $user = $token->getUser();

        //Если товар не имеет эксперта, и у эксперта есть права на категорию в которой находится товар
        if ($user->getCategories()->contains($category)) {
            return true;
        }

        return false;
    }
}
