<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Security\ProductFilterVoter;
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
    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param ProductFilter $productsFilter The value that should be validated
     * @param Constraint    $constraint     The constraint for the validation
     *
     * @return null
     */
    public function validate($productsFilter, Constraint $constraint)
    {
        $this->authorizationChecker;
        $status = $productsFilter->getStatus();
        if ($status == ProductFilter::STATUS_FREE) {
            if (!$this->authorizationChecker->isGranted(
                ProductFilterVoter::VIEW_FREE,
                $productsFilter->getCategory()
            )
            ) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
            return;
        }
        if ($status == ProductFilter::STATUS_WAIT) {
            if (!$this->authorizationChecker->isGranted(
                ProductFilterVoter::VIEW_WAIT,
                $productsFilter->getCategory()
            )
            ) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
            return;
        }
        if (is_null($status)) {
            return;
        }
        throw new \LogicException('Status is invalid');
    }
}
