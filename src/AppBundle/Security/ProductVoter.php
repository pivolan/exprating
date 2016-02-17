<?php
/**
 * Date: 15.02.16
 * Time: 17:17
 */

namespace AppBundle\Security;

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
        if (!in_array($attribute, [self::EXPERTISE, self::VIEW, self::PUBLISH])) {
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
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Product $product, TokenInterface $token)
    {
        // if they can edit, they can view
        if ($this->canExpertise($product, $token)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return $product->getIsEnabled();
    }

    private function canExpertise(Product $product, TokenInterface $token)
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
                if($decision->getStatus() == CuratorDecision::STATUS_APPROVE)
                {
                    throw new \LogicException('Во время публикации найдена ошибка. Одобренный куратором товар,
                    не опубликован');
                }
            }
            return true;
        }
        return false;
    }
}