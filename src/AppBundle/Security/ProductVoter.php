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

class ProductVoter extends Voter
{
    const EXPERTISE = 'EXPERTISE';
    // these strings are just invented: you can use anything
    const VIEW = 'VIEW';
    const PUBLISH = 'PUBLISH';
    const RESERVE = 'RESERVE';
    const MODERATE = 'MODERATE';
    const CHANGE_EXPERT = 'CHANGE_EXPERT';
    const CATEGORY_CHANGE = 'CATEGORY_CHANGE';

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
                self::EXPERTISE,
                self::VIEW,
                self::PUBLISH,
                self::RESERVE,
                self::MODERATE,
                self::CHANGE_EXPERT,
                self::CATEGORY_CHANGE,
            ]
        )
        ) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Product) {
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
        /** @var Product $product */
        $product = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($product, $token);
            case self::EXPERTISE:
                return $this->canExpertise($product, $token);
            case self::PUBLISH:
                return $this->canPublish($product, $token);
            case self::RESERVE:
                return $this->canReserve($product, $token);
            case self::MODERATE:
                return $this->canModerate($product, $token);
            case self::CHANGE_EXPERT:
                return $this->canChangeExpert($product, $token);
            case self::CATEGORY_CHANGE:
                return $this->canCategoryChange($product, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canModerate(Product $product, TokenInterface $token)
    {
        if ($product->getIsEnabled()) {
            return false;
        }
        if ($product->getExpertUser() == null) {
            return false;
        }

        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        //Если товар принадлежит подчиненному эксперту
        if ($user != $product->getExpertUser()->getCurator()) {
            return false;
        }

        foreach ($product->getCuratorDecisions() as $decision) {
            if ($decision->getStatus() == CuratorDecision::STATUS_WAIT
                && $decision->getCurator() == $user
            ) {
                return true;
            }
        }

        return false;
    }

    private function canChangeExpert(Product $product, TokenInterface $token)
    {
        if ($product->getExpertUser() == null) {
            return false;
        }

        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT_CURATOR])) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        //Если товар принадлежит подчиненному эксперту
        if ($user != $product->getExpertUser()->getCurator()) {
            return false;
        }

        return true;
    }

    private function canView(Product $product, TokenInterface $token)
    {
        //Если товар опубликован, смотреть можно всем
        if ($product->getIsEnabled()) {
            return true;
        }
        // if they can edit, they can view
        if ($this->canExpertise($product, $token)) {
            return true;
        }
        if ($product->getExpertUser() == null) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        //Если товар принадлежит подчиненному эксперту
        if ($user == $product->getExpertUser()->getCurator()) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return $product->getIsEnabled();
    }

    private function canExpertise(Product $product, TokenInterface $token)
    {
        //Если нет прав эксперта значит точно нельзя
        if (!$this->decisionManager->decide($token, [User::ROLE_EXPERT])) {
            return false;
        }
        /** @var User $user */
        $user = $token->getUser();

        //Если товар уже привязан к эксперту
        if ($user === $product->getExpertUser()) {
            return true;
        }

        //Если товар не имеет эксперта, и у эксперта есть права на категорию в которой находится товар
        if (($product->getExpertUser() == null) && $user->getCategories()->contains($product->getCategory())) {
            return true;
        }

        return false;
    }

    private function canPublish(Product $product, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        //Если товар уже привязан к эксперту, не был опубликован, и не в ожидании решения куратора
        if ($user === $product->getExpertUser() && ($product->getIsEnabled() == false)) {
            $decisions = $product->getCuratorDecisions();
            foreach ($decisions as $decision) {
                if (in_array($decision->getStatus(), [CuratorDecision::STATUS_WAIT])) {
                    return false;
                }
                if ($decision->getStatus() == CuratorDecision::STATUS_APPROVE) {
                    throw new \LogicException(
                        'Во время публикации найдена ошибка. Одобренный куратором товар,
                    не опубликован'
                    );
                }
            }

            return true;
        }

        return false;
    }

    private function canReserve(Product $product, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        //Можно если товар свободен, есть роль эксперта, есть права на категорию
        return ($product->getExpertUser() == null)
               &&
               ($this->decisionManager->decide($token, [User::ROLE_EXPERT]))
               &&
               ($user->getCategories()->contains($product->getCategory()));
    }

    private function canCategoryChange(Product $product, TokenInterface $token)
    {
        return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
    }
}
