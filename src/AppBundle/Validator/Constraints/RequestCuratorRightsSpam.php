<?php
/**
 * Date: 31.03.16
 * Time: 1:19
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class RequestCuratorRightsSpam
 *
 * @Annotation
 */
class RequestCuratorRightsSpam extends Constraint
{
    public function validatedBy()
    {
        return 'request_curator_rights_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}