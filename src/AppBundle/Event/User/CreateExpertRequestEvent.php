<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Event\User;

use Symfony\Component\EventDispatcher\Event;

class CreateExpertRequestEvent extends Event implements UserEventInterface
{
    public function getEmail()
    {
        // TODO: extends Event implement getEmail() method.
    }
}
