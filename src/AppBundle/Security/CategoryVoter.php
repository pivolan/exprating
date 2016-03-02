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

class CategoryVoter extends Voter
{
    // these strings are just invented: you can use anything
    const EDIT = 'EDIT';

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
        if (!in_array($attribute, [self::EDIT])) {
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
            case self::EDIT:
                return $this->canEdit($category, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Category $category, TokenInterface $token)
    {
        //Админу можно редактировать все
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }
        //Если нет прав админа категорий значит точно нельзя
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CATEGORY_ADMIN])) {
            return false;
        }
        /** @var User $user */
        $user = $token->getUser();

        //Если категория разрешена этому админу катгеорий
        if ($user->getAdminCategories()->contains($category)) {
            return true;
        }

        return false;
    }
}
