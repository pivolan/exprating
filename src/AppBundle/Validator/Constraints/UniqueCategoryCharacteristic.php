<?php

/**
 * Date: 16.02.16
 * Time: 17:00.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class FilterAccessRights.
 *
 * @Annotation
 */
class UniqueCategoryCharacteristic extends Constraint
{
    public $message = 'Такой пользователь уже зарегестрирован в системе';

    public function validatedBy()
    {
        return 'unique_category_characteristic_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
