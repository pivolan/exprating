<?php
/**
 * Date: 10.03.16
 * Time: 4:11
 */

namespace AppBundle\Event\Invite;

use AppBundle\Event\User\UserEventInterface;
use Symfony\Component\EventDispatcher\Event;

class InviteRequestRightsEvent extends Event implements UserEventInterface
{
    public function getEmail()
    {

    }
}
