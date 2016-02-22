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
class RatingSettingsSumWeight extends Constraint
{
    public $message = 'Сумма всех весов для оценки должна быть равна 100';

    public function validatedBy()
    {
        return 'rating_settings_sum_weight_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}