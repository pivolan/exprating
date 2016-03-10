<?php
/**
 * Date: 10.03.16
 * Time: 4:12
 */

namespace AppBundle\Event\Invite;

use AppBundle\Event\User\UserEventInterface;
use Symfony\Component\EventDispatcher\Event;

class InviteApproveRightsEvent extends Event implements UserEventInterface
{

    public function getEmail()
    {
        // TODO: extends Event implement getEmail() method.
    }
}
