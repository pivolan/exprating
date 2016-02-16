<?php
/**
 * Date: 16.02.16
 * Time: 17:00
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * Class FilterAccessRights
 * @Annotation
 * @package AppBundle\Validator\Constraints
 */
class FilterAccessRights extends Constraint
{
    public function validatedBy()
    {
        return 'filter_access_rights';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}