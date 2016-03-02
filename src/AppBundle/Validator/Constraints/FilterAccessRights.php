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
class FilterAccessRights extends Constraint
{
    public $message = 'Нет доступа';

    public function validatedBy()
    {
        return 'filter_access_rights';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
