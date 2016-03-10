<?php
/**
 * Date: 10.03.16
 * Time: 3:59
 */

namespace AppBundle\Event\User;

use Symfony\Component\EventDispatcher\Event;

class ApproveCreateExpertEvent extends Event implements UserEventInterface
{
    public function getEmail()
    {
        // TODO: extends Event implement getEmail() method.
    }
}
