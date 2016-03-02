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
