<?php
/**
 * Date: 04.02.16
 * Time: 12:37
 */

namespace Exprating\CharacteristicBundle\Exceptions;


use Exception;

class CharacteristicTypeException extends \DomainException
{
    public function __construct($type = "", $code = 0, Exception $previous = null)
    {
        $message = 'Для характеристики товара использован несуществующий тип значения: ' . $type;
        parent::__construct($message, $code, $previous);
    }
}