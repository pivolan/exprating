<?php
/**
 * Date: 16.02.16
 * Time: 17:00
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use SensioLabs\Security\SecurityChecker;
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
    }
}