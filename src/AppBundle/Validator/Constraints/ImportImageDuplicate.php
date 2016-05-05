<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 05.05.16
 * Time: 23:34
 */

namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * Class ImportImageDuplicate .
 *
 * @Annotation
 */
class ImportImageDuplicate extends Constraint
{
    public $message = 'Файлы дубликаты';

    public function validatedBy()
    {
        return 'import_image_duplicate';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}