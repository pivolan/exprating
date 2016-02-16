<?php
/**
 * Date: 16.02.16
 * Time: 17:00
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Security\ProductFilterVoter;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Asset\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class FilterAccessRightsValidator extends ConstraintValidator
{
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @param AuthorizationChecker $authorizationChecker
     */
    function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param ProductFilter $productsFilter The value that should be validated
     * @param Constraint    $constraint     The constraint for the validation
     */
    public function validate($productsFilter, Constraint $constraint)
    {
        $this->authorizationChecker;
        $status = $productsFilter->getStatus();
        if ($status == ProductFilter::STATUS_FREE) {
            return $this->authorizationChecker->isGranted(ProductFilterVoter::VIEW_FREE, $productsFilter->getCategory());
        }
        if ($status == ProductFilter::STATUS_WAIT) {
            return $this->authorizationChecker->isGranted(ProductFilterVoter::VIEW_WAIT, $productsFilter->getCategory());
        }
        if (is_null($status)) {
            return true;
        }
        throw new \LogicException('Status is invalid');
    }
}